<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\PdfExportService;
use App\Services\NotificationService;
use App\Services\ImageOptimizationService;

class TicketController extends Controller
{
    protected $pdfExportService;
    protected $notificationService;
    protected $imageOptimizationService;

    public function __construct(
        PdfExportService $pdfExportService, 
        NotificationService $notificationService,
        ImageOptimizationService $imageOptimizationService
    ) {
        $this->pdfExportService = $pdfExportService;
        $this->notificationService = $notificationService;
        $this->imageOptimizationService = $imageOptimizationService;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        $customers = Customer::all();
        $products = OrderProduct::with('order', 'product')->get();
        $staff = User::role('staff')->get(); // Assuming roles are used
        return view('tickets.create', compact('customers', 'products', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'customer_contact_person_id' => 'required|exists:customer_contact_person,id',
            'order_product_id' => 'required|exists:order_products,id',
            'assigned_to' => 'required|exists:users,id',
            'additional_staff' => 'nullable|array',
            'additional_staff.*' => 'exists:users,id'
        ]);

        $validated['attended_by'] = auth()->id();
        $validated['uuid'] = Str::uuid()->toString();
        $validated['type'] = 'service';

        $ticket = Ticket::create($validated);
        $ticket->additionalStaff()->sync($validated['additional_staff'] ?? []);

        // Send notification to assigned user
        try {
            $this->notificationService->sendTicketNotification($ticket, 'ticket_created');
        } catch (\Exception $e) {
            // Log the error but don't fail the ticket creation
            Log::error('Failed to send ticket notification', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('tickets.show', $ticket->uuid)->with('success', 'Ticket created successfully.');
    }

    public function show($uuid)
    {
        $ticket = Ticket::with([
            'customer', 
            'orderProduct.product', 
            'assignedTo', 
            'additionalStaff',
            'services',
            'ticketImages.uploadedBy'
        ])->where('uuid', $uuid)->firstOrFail();
        
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $customers = Customer::all();
        $products = OrderProduct::with('order', 'product')->get();
        $staff = User::role('staff')->get();
        $customerContacts =  $ticket->customer->contactPersons;
        return view('tickets.edit', compact('ticket', 'customers', 'products', 'staff','customerContacts'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'customer_contact_person_id' => 'required|exists:customer_contact_person,id',
            'order_product_id' => 'required|exists:order_products,id',
            'assigned_to' => 'required|exists:users,id',
            'additional_staff' => 'nullable|array',
            'additional_staff.*' => 'exists:users,id',
            'description' => 'nullable|string',
        ]);

        $ticket->update($validated);
        $ticket->additionalStaff()->sync($validated['additional_staff'] ?? []);

        return redirect()->route('tickets.show', $ticket->uuid)->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets')->with('success', 'Ticket deleted.');
    }

    public function exportCsv(Request $request)
    {
        $query = Ticket::query()
            ->join('customers', 'tickets.customer_id', '=', 'customers.id')
            ->leftJoin('users', 'tickets.assigned_to', '=', 'users.id')
            ->leftJoin('order_products', 'tickets.order_product_id', '=', 'order_products.id')
            ->leftJoin('products', 'order_products.product_id', '=', 'products.id')
            ->leftJoin('customer_contact_person', 'tickets.customer_contact_person_id', '=', 'customer_contact_person.id')
            ->select(
                'tickets.subject',
                'customers.name as customer_name',
                'customer_contact_person.name as contact_person',
                'products.name as product_name',
                'order_products.model_number',
                'order_products.serial_number',
                'users.name as assigned_staff',
                'tickets.created_at',
                'tickets.updated_at'
            )
            ->where('tickets.type', 'service');

        // Apply filters from request
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tickets.subject', 'like', "%{$search}%")
                    ->orWhere('customers.name', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('customerFilter') && $request->customerFilter !== 'all') {
            $query->where('tickets.customer_id', $request->customerFilter);
        }

        if ($request->filled('assignedStaffFilter') && $request->assignedStaffFilter !== 'all') {
            $query->where('tickets.assigned_to', $request->assignedStaffFilter);
        }

        if ($request->filled('yearFilter') && $request->yearFilter !== 'all') {
            $query->whereYear('tickets.created_at', $request->yearFilter);
        }

        if ($request->filled('monthFilter') && $request->monthFilter !== 'all') {
            $query->whereMonth('tickets.created_at', $request->monthFilter);
        }

        $tickets = $query->orderBy('tickets.created_at', 'desc')->get();

        $filename = 'tickets_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Subject',
                'Customer',
                'Contact Person',
                'Product',
                'Model Number',
                'Serial Number', 
                'Assigned Staff',
                'Created Date',
                'Last Updated'
            ]);

            // Add data rows
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->subject,
                    $ticket->customer_name,
                    $ticket->contact_person,
                    $ticket->product_name,
                    $ticket->model_number,
                    $ticket->serial_number,
                    $ticket->assigned_staff,
                    $ticket->created_at ? date('Y-m-d H:i:s', strtotime($ticket->created_at)) : '',
                    $ticket->updated_at ? date('Y-m-d H:i:s', strtotime($ticket->updated_at)) : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export tickets to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Ticket::with(['customer', 'assignedTo', 'attendedBy']);

        // Apply filters
        $filters = [];
        
        if ($request->filled('search')) {
            $query->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            $filters['search'] = $request->search;
        }

        if ($request->filled('status_filter') && $request->status_filter != 'all') {
            $query->where('status', $request->status_filter);
            $filters['status_filter'] = $request->status_filter;
        }

        if ($request->filled('priority_filter') && $request->priority_filter != 'all') {
            $query->where('priority', $request->priority_filter);
            $filters['priority_filter'] = $request->priority_filter;
        }

        if ($request->filled('assigned_to') && $request->assigned_to != 'all') {
            $query->where('assigned_to', $request->assigned_to);
            $filters['assigned_to'] = $request->assigned_to;
        }

        $tickets = $query->get();

        return $this->pdfExportService->generateTicketsPdf($tickets, $filters);
    }

    /**
     * Export single ticket to PDF
     */
    public function exportSinglePdf($uuid)
    {
        // Find the ticket by UUID and load all relationships
        $ticket = Ticket::with([
            'customer', 
            'assignedTo', 
            'attendedBy', 
            'contactPerson',
            'orderProduct.product',
            'orderProduct.order',
            'additionalStaff',
            'services',
            'ticketImages.uploadedBy'
        ])->where('uuid', $uuid)->firstOrFail();

        // Debug: Log what we're loading
        Log::info('PDF Export Debug', [
            'ticket_id' => $ticket->id,
            'ticket_uuid' => $ticket->uuid,
            'ticket_subject' => $ticket->subject,
            'customer_loaded' => $ticket->customer ? 'YES' : 'NO',
            'services_count' => $ticket->services->count(),
            'images_count' => $ticket->ticketImages->count(),
        ]);
        
        return $this->pdfExportService->generateSingleTicketPdf($ticket);
    }

    /**
     * Upload images for a ticket with optimization
     */
    public function uploadTicketImages(Request $request)
    {
        $request->validate([
            'ticket_uuid' => 'required|string|exists:tickets,uuid',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|string', // Base64 encoded images
        ]);

        try {
            $ticket = Ticket::where('uuid', $request->ticket_uuid)->firstOrFail();
            $uploadedImages = [];
            $failedUploads = [];
            $optimizationStats = [];

            foreach ($request->images as $index => $imageData) {
                try {
                    // Optimize the image
                    $optimizationResult = $this->imageOptimizationService->optimizeImage(
                        $imageData,
                        1200, // Max width
                        800,  // Max height
                        80    // Quality (0-100)
                    );

                    if (!$optimizationResult['success']) {
                        $failedUploads[] = "Image " . ($index + 1) . ": " . $optimizationResult['error'];
                        continue;
                    }

                    // Store the optimized image
                    $storageResult = $this->imageOptimizationService->storeOptimizedImage(
                        $optimizationResult['image_data'],
                        $ticket->uuid,
                        $index,
                        $optimizationResult['original_format']
                    );

                    if (!$storageResult['success']) {
                        $failedUploads[] = "Image " . ($index + 1) . ": " . $storageResult['error'];
                        continue;
                    }

                    // Create database record
                    $ticketImage = $ticket->ticketImages()->create([
                        'image_path' => $storageResult['file_path'],
                        'original_format' => $optimizationResult['original_format'],
                        'optimized_format' => 'webp',
                        'original_size' => $optimizationResult['original_size'],
                        'optimized_size' => $optimizationResult['optimized_size'],
                        'compression_ratio' => $optimizationResult['compression_ratio'],
                        'uploaded_by' => auth()->id(),
                    ]);

                    $uploadedImages[] = [
                        'id' => $ticketImage->id,
                        'image_url' => asset('storage/' . $storageResult['file_path']),
                        'original_format' => $optimizationResult['original_format'],
                        'optimized_format' => 'webp',
                        'original_size_formatted' => $this->imageOptimizationService->formatFileSize($optimizationResult['original_size']),
                        'optimized_size_formatted' => $this->imageOptimizationService->formatFileSize($optimizationResult['optimized_size']),
                        'compression_ratio' => $optimizationResult['compression_ratio'] . '%',
                        'dimensions' => $optimizationResult['optimized_dimensions']
                    ];

                    // Collect optimization stats
                    $optimizationStats[] = [
                        'original_size' => $optimizationResult['original_size'],
                        'optimized_size' => $optimizationResult['optimized_size'],
                        'compression_ratio' => $optimizationResult['compression_ratio']
                    ];

                } catch (\Exception $e) {
                    $failedUploads[] = "Image " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            // Calculate total optimization stats
            $totalOriginalSize = array_sum(array_column($optimizationStats, 'original_size'));
            $totalOptimizedSize = array_sum(array_column($optimizationStats, 'optimized_size'));
            $overallCompressionRatio = $totalOriginalSize > 0 ? 
                round((($totalOriginalSize - $totalOptimizedSize) / $totalOriginalSize) * 100, 2) : 0;

            $response = [
                'success' => true,
                'message' => 'Images processed and optimized successfully',
                'total_uploaded' => count($uploadedImages),
                'total_failed' => count($failedUploads),
                'uploaded_images' => $uploadedImages,
                'optimization_summary' => [
                    'total_original_size' => $this->imageOptimizationService->formatFileSize($totalOriginalSize),
                    'total_optimized_size' => $this->imageOptimizationService->formatFileSize($totalOptimizedSize),
                    'total_space_saved' => $this->imageOptimizationService->formatFileSize($totalOriginalSize - $totalOptimizedSize),
                    'overall_compression_ratio' => $overallCompressionRatio . '%'
                ]
            ];

            if (!empty($failedUploads)) {
                $response['failed_uploads'] = $failedUploads;
            }

            // Log successful uploads with optimization details
            if (count($uploadedImages) > 0) {
                Log::info('Optimized ticket images uploaded successfully', [
                    'ticket_uuid' => $ticket->uuid,
                    'uploaded_by' => auth()->user()->name ?? 'Unknown',
                    'image_count' => count($uploadedImages),
                    'total_original_size' => $this->imageOptimizationService->formatFileSize($totalOriginalSize),
                    'total_optimized_size' => $this->imageOptimizationService->formatFileSize($totalOptimizedSize),
                    'space_saved' => $this->imageOptimizationService->formatFileSize($totalOriginalSize - $totalOptimizedSize),
                    'compression_ratio' => $overallCompressionRatio . '%'
                ]);
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get images for a ticket
     */
    public function getTicketImages($ticketUuid)
    {
        try {
            $ticket = Ticket::where('uuid', $ticketUuid)
                ->with(['ticketImages.uploadedBy'])
                ->firstOrFail();

            $images = $ticket->ticketImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_url' => $image->image_url,
                    'original_format' => $image->original_format ?? 'unknown',
                    'optimized_format' => $image->optimized_format ?? 'webp',
                    'original_size_formatted' => $this->imageOptimizationService->formatFileSize($image->original_size ?? 0),
                    'optimized_size_formatted' => $this->imageOptimizationService->formatFileSize($image->optimized_size ?? 0),
                    'compression_ratio' => ($image->compression_ratio ?? 0) . '%',
                    'uploaded_by' => $image->uploadedBy->name ?? 'Unknown',
                    'uploaded_at' => $image->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'images' => $images,
                'total_count' => $images->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a ticket image
     */
    public function deleteTicketImage($imageId)
    {
        try {
            $image = \App\Models\TicketImage::findOrFail($imageId);
            
            // Check if user has permission to delete this image
            // Only allow the uploader or admin users to delete images
            if (auth()->id() !== $image->uploaded_by) {
                // You can add additional admin check here if you have role system
                // For now, we'll allow only the uploader to delete their own images
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this image'
                ], 403);
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete database record
            $image->delete();

            Log::info('Ticket image deleted successfully', [
                'image_id' => $imageId,
                'ticket_id' => $image->ticket_id,
                'deleted_by' => auth()->user()->name ?? 'Unknown',
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete ticket image', [
                'error' => $e->getMessage(),
                'image_id' => $imageId,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }
}
