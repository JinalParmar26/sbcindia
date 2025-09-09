<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Service;
use App\Models\Delivery;
use App\Models\ServiceItem;
use App\Models\TicketImage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function assignedTickets(Request $request)
    {

        $tickets = Ticket::with([
                'customer', 
                'orderProduct.product',
                'assignedTo',
                'attendedBy'
            ])
            ->where('assigned_to', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['tickets' => $tickets]);
    }

    public function recentCompletedTickets(Request $request)
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $tickets = Ticket::with(['customer', 'orderProduct.product'])
            ->where('assigned_to', $request->user()->id)
            ->whereNotNull('end') // Only completed tickets
            ->where('end', '>=', $thirtyDaysAgo)
            ->latest('end') // Order by end date, not created_at
            ->get();

        return response()->json(['tickets' => $tickets]);
    }

    public function show($uuid)
    {

        try {
            $ticket = Ticket::where('uuid', $uuid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No ticket found for the provided UUID.',
            ], 404);
        }
        $ticket = Ticket::with([
            'customer',
            'contactPerson',
            'assignedTo',
            'orderProduct.product',
        ])->where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'ticket' => $ticket
        ]);
    }

    public function storeTicketEntry(Request $request, $uuid)
    {

        try {
            $ticket = Ticket::where('uuid', $uuid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No ticket found for the provided UUID.',
            ], 404);
        }
        try{
            $data = json_decode($request->input('data'), true);

            $rules = [];

            if ($request->has('type')) {
                $rules['type'] = 'required|in:delivery,service';
            }
            if ($request->has('start') && $request->input('start') != '') {
                $rules['start'] = 'required|date';
            }
            if ($request->has('end') && $request->input('end') != '') {
                $rules['end'] = 'nullable|date';
            }
            if ($request->has('log') && $request->input('log') != '') {
                $rules['log'] = 'nullable|string';
            }
            if ($request->has('data')) {
                //$rules['data'] = 'required|array';

                if (isset($request->data['items'])) {
                    //$rules['data.items'] = 'array';
                    $rules['data.items.*.item'] = 'required_with:data.items|string';
                    $rules['data.items.*.qty'] = 'required_with:data.items|numeric';
                    $rules['data.items.*.rate'] = 'required_with:data.items|numeric';
                    $rules['data.items.*.amount'] = 'required_with:data.items|numeric';
                }
            }

            $validator = $request->validate($rules);


            // if ($validator->fails()) {
            //     return response()->json(['errors' => $validator->errors()], 422);
            // }

            $type = $request->input('type');
            $items = $data['items'] ?? [];

            $updateData = [];

            if ($request->has('start')) {
                $updateData['start'] = $request->input('start');
            }

            if ($request->has('end')) {
                $updateData['end'] = $request->input('end');
            }

            if (isset($data['log'])) {
                $updateData['log'] = $data['log'];
            }


            $ticket->update($updateData);

            if ($type === 'service') {
                // Remove items before inserting service record
                unset($data['items']);
                $user = auth()->user();
                $service = Service::updateOrCreate(
                    ['ticket_id' => $ticket->id],
                    ['user_id' => $user->id],
                    array_merge($data, ['ticket_id' => $ticket->id])
                );

                if($items) {
                    $service->serviceItems()->delete();


                    foreach ($items as $item) {
                        $service->serviceItems()->create($item);
                    }
                }

            } elseif ($type === 'delivery') {
                unset($data['items']);

                $delivery = Delivery::updateOrCreate(['ticket_id' => $ticket->id],array_merge($data, [
                    'ticket_id' => $ticket->id,
                ]));


                if($items) {
                    $delivery->serviceItems()->delete();


                    foreach ($items as $item) {
                        $delivery->serviceItems()->create($item);
                    }
                }
            }

            return response()->json([
                'message' => ucfirst($type) . ' entry saved successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([

                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadTicketImages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_uuid' => 'required|string|exists:tickets,uuid',
            'images' => 'required|array|min:1|max:10', // Allow up to 10 images
            'images.*' => 'required|string', // Each image should be base64 string
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the ticket
        $ticket = Ticket::where('uuid', $request->input('ticket_uuid'))->first();
        
        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        // Check if user has permission to upload images for this ticket
        $user = $request->user();
        if ($ticket->assigned_to !== $user->id && $ticket->attended_by !== $user->id) {
            return response()->json(['error' => 'You do not have permission to upload images for this ticket'], 403);
        }

        $images = $request->input('images');
        $descriptions = $request->input('descriptions', []);
        $uploadedImages = [];
        $failedUploads = [];

        foreach ($images as $index => $imageData) {
            try {
                $imageInfo = $this->uploadBase64Image($imageData, 'ticket_images');
                
                if ($imageInfo) {
                    // Create ticket image record
                    $ticketImage = TicketImage::create([
                        'ticket_id' => $ticket->id,
                        'uploaded_by' => $user->id,
                        'image_path' => $imageInfo['path'],
                        'original_filename' => $imageInfo['filename'],
                        'mime_type' => $imageInfo['mime_type'],
                        'file_size' => $imageInfo['file_size'],
                        'description' => $descriptions[$index] ?? null,
                    ]);

                    $uploadedImages[] = [
                        'uuid' => $ticketImage->uuid,
                        'image_url' => $ticketImage->image_url,
                        'description' => $ticketImage->description,
                        'file_size' => $ticketImage->file_size_formatted,
                        'uploaded_at' => $ticketImage->created_at,
                    ];
                } else {
                    $failedUploads[] = "Image " . ($index + 1) . ": Invalid image format";
                }
            } catch (Exception $e) {
                $failedUploads[] = "Image " . ($index + 1) . ": Upload failed";
            }
        }

        $response = [
            'message' => count($uploadedImages) . ' image(s) uploaded successfully',
            'uploaded_images' => $uploadedImages,
            'total_uploaded' => count($uploadedImages),
            'total_failed' => count($failedUploads),
        ];

        if (!empty($failedUploads)) {
            $response['failed_uploads'] = $failedUploads;
        }

        return response()->json($response, 200);
    }

    public function getTicketImages(Request $request, $ticketUuid)
    {
        $ticket = Ticket::where('uuid', $ticketUuid)->first();
        
        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        // Check if user has permission to view images for this ticket
        $user = $request->user();
        if ($ticket->assigned_to !== $user->id && $ticket->attended_by !== $user->id) {
            return response()->json(['error' => 'You do not have permission to view images for this ticket'], 403);
        }

        $images = TicketImage::where('ticket_id', $ticket->id)
            ->with('uploadedBy:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($image) {
                return [
                    'uuid' => $image->uuid,
                    'image_url' => $image->image_url,
                    'description' => $image->description,
                    'file_size' => $image->file_size_formatted,
                    'uploaded_by' => $image->uploadedBy->name,
                    'uploaded_at' => $image->created_at,
                ];
            });

        return response()->json([
            'ticket_uuid' => $ticketUuid,
            'images' => $images,
            'total_images' => $images->count(),
        ]);
    }

    private function uploadBase64Image($base64Data, $directory)
    {
        try {
            // Match base64 with data URI scheme
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $image = substr($base64Data, strpos($base64Data, ',') + 1);
                $imageDecoded = base64_decode($image);
                $extension = strtolower($type[1]); // jpg, png, etc.

                // Validate file extension
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return false;
                }

                $filename = uniqid($directory . '_', true) . '.' . $extension;
                $directoryPath = storage_path("app/public/{$directory}");
                
                // Create directory if it doesn't exist
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0755, true);
                }
                
                $filePath = $directoryPath . '/' . $filename;
                file_put_contents($filePath, $imageDecoded);

                return [
                    'path' => $directory . '/' . $filename,
                    'filename' => $filename,
                    'mime_type' => 'image/' . $extension,
                    'file_size' => strlen($imageDecoded),
                ];
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
