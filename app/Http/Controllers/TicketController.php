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

class TicketController extends Controller
{
    protected $pdfExportService;
    protected $notificationService;

    public function __construct(PdfExportService $pdfExportService, NotificationService $notificationService)
    {
        $this->pdfExportService = $pdfExportService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        try {
            $customers = Customer::all();
            $products = OrderProduct::with('order', 'product')->get();
            $staff = User::all(); // Get all users for now, fix role later

            \Log::info('TicketController@create - Data loaded successfully', [
                'customers_count' => $customers->count(),
                'products_count' => $products->count(),
                'staff_count' => $staff->count()
            ]);

            return view('tickets.create', compact('customers', 'products', 'staff'));
        } catch (\Exception $e) {
            \Log::error('Error in TicketController@create: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load the create ticket page. Please try again.');
        }
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

    public function show($id)
    {
        // Handle both UUID and ID parameters
        if (is_numeric($id)) {
            $ticket = Ticket::with([
                'customer', 
                'orderProduct.product', 
                'assignedTo', 
                'additionalStaff',
                'services',
                'ticketImages.uploadedBy'
            ])->findOrFail($id);
        } else {
            $ticket = Ticket::with([
                'customer', 
                'orderProduct.product', 
                'assignedTo', 
                'additionalStaff',
                'services',
                'ticketImages.uploadedBy'
            ])->where('uuid', $id)->firstOrFail();
        }
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        // Handle both UUID and ID parameters
        if (is_numeric($id)) {
            $ticket = Ticket::with(['customer.contactPersons'])->findOrFail($id);
        } else {
            $ticket = Ticket::with(['customer.contactPersons'])->where('uuid', $id)->firstOrFail();
        }
        
        $customers = Customer::all();
        $products = OrderProduct::with('order', 'product')->get();
        $staff = User::role('staff')->get();
        $customerContacts = $ticket->customer->contactPersons;
        return view('tickets.edit', compact('ticket', 'customers', 'products', 'staff','customerContacts'));
    }

    public function update(Request $request, $id)
    {
        // Handle both UUID and ID parameters
        if (is_numeric($id)) {
            $ticket = Ticket::findOrFail($id);
        } else {
            $ticket = Ticket::where('uuid', $id)->firstOrFail();
        }
        
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
    public function exportSinglePdf($id)
    {
        // Handle both UUID and ID parameters
        if (is_numeric($id)) {
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
            ])->findOrFail($id);
        } else {
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
            ])->where('uuid', $id)->firstOrFail();
        }

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
     * Upload images for a ticket
     */
    public function uploadTicketImages(Request $request)
    {
        $request->validate([
            'ticket_uuid' => 'required|string|exists:tickets,uuid',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|string', // Base64 encoded images
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'nullable|string|max:500'
        ]);

        try {
            $ticket = Ticket::where('uuid', $request->ticket_uuid)->firstOrFail();
            $uploadedImages = [];
            $failedUploads = [];

            foreach ($request->images as $index => $imageData) {
                try {
                    // Decode base64 image
                    if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                        $failedUploads[] = "Image " . ($index + 1) . ": Invalid base64 format";
                        continue;
                    }

                    $imageType = $matches[1];
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $imageData = base64_decode($imageData);

                    if (!$imageData) {
                        $failedUploads[] = "Image " . ($index + 1) . ": Failed to decode base64";
                        continue;
                    }

                    // Generate unique filename
                    $filename = 'ticket_' . $ticket->uuid . '_' . time() . '_' . $index . '.' . $imageType;
                    $filePath = 'ticket_images/' . $filename;

                    // Store image
                    Storage::disk('public')->put($filePath, $imageData);

                    // Create database record
                    $ticketImage = $ticket->ticketImages()->create([
                        'image_path' => $filePath,
                        'description' => $request->descriptions[$index] ?? null,
                        'uploaded_by' => auth()->id(),
                    ]);

                    $uploadedImages[] = $ticketImage;

                } catch (\Exception $e) {
                    $failedUploads[] = "Image " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            $response = [
                'success' => true,
                'message' => 'Images processed successfully',
                'total_uploaded' => count($uploadedImages),
                'total_failed' => count($failedUploads),
                'uploaded_images' => $uploadedImages,
            ];

            if (!empty($failedUploads)) {
                $response['failed_uploads'] = $failedUploads;
            }

            // Send notification if any images were uploaded successfully
            if (count($uploadedImages) > 0) {
                try {
                    // Log the image upload for future reference
                    Log::info('Ticket images uploaded successfully', [
                        'ticket_uuid' => $ticket->uuid,
                        'uploaded_by' => auth()->user()->name,
                        'image_count' => count($uploadedImages)
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to log ticket image upload: ' . $e->getMessage());
                }
            }

            return response()->json($response);

        } catch (\Exception $e) {
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
                    'description' => $image->description,
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
}
