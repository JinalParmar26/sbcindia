<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $imageOptimizationService;

    public function __construct(ImageOptimizationService $imageOptimizationService)
    {
        $this->imageOptimizationService = $imageOptimizationService;
    }

    /**
     * Upload optimized images for a ticket via API
     */
    public function uploadImages(Request $request)
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
                    // Optimize the image with API-specific settings
                    $optimizationResult = $this->imageOptimizationService->optimizeImage(
                        $imageData,
                        1024, // Smaller max width for API
                        768,  // Smaller max height for API
                        75    // Slightly lower quality for smaller files
                    );

                    if (!$optimizationResult['success']) {
                        $failedUploads[] = [
                            'index' => $index + 1,
                            'error' => $optimizationResult['error']
                        ];
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
                        $failedUploads[] = [
                            'index' => $index + 1,
                            'error' => $storageResult['error']
                        ];
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
                        'uploaded_by' => Auth::id(),
                    ]);

                    $uploadedImages[] = [
                        'id' => $ticketImage->id,
                        'image_url' => asset('storage/' . $storageResult['file_path']),
                        'original_format' => $optimizationResult['original_format'],
                        'optimized_format' => 'webp',
                        'original_size' => $optimizationResult['original_size'],
                        'optimized_size' => $optimizationResult['optimized_size'],
                        'original_size_formatted' => $this->imageOptimizationService->formatFileSize($optimizationResult['original_size']),
                        'optimized_size_formatted' => $this->imageOptimizationService->formatFileSize($optimizationResult['optimized_size']),
                        'compression_ratio' => $optimizationResult['compression_ratio'],
                        'space_saved' => $optimizationResult['original_size'] - $optimizationResult['optimized_size'],
                        'space_saved_formatted' => $this->imageOptimizationService->formatFileSize($optimizationResult['original_size'] - $optimizationResult['optimized_size']),
                        'dimensions' => $optimizationResult['optimized_dimensions'],
                        'uploaded_at' => $ticketImage->created_at->toISOString()
                    ];

                    // Collect optimization stats
                    $optimizationStats[] = [
                        'original_size' => $optimizationResult['original_size'],
                        'optimized_size' => $optimizationResult['optimized_size'],
                        'compression_ratio' => $optimizationResult['compression_ratio']
                    ];

                } catch (\Exception $e) {
                    $failedUploads[] = [
                        'index' => $index + 1,
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Calculate total optimization stats
            $totalOriginalSize = array_sum(array_column($optimizationStats, 'original_size'));
            $totalOptimizedSize = array_sum(array_column($optimizationStats, 'optimized_size'));
            $totalSpaceSaved = $totalOriginalSize - $totalOptimizedSize;
            $overallCompressionRatio = $totalOriginalSize > 0 ? 
                round((($totalOriginalSize - $totalOptimizedSize) / $totalOriginalSize) * 100, 2) : 0;

            $response = [
                'success' => true,
                'message' => 'Images processed and optimized successfully',
                'data' => [
                    'ticket_uuid' => $ticket->uuid,
                    'total_uploaded' => count($uploadedImages),
                    'total_failed' => count($failedUploads),
                    'uploaded_images' => $uploadedImages,
                    'optimization_summary' => [
                        'total_images_processed' => count($request->images),
                        'total_original_size' => $totalOriginalSize,
                        'total_optimized_size' => $totalOptimizedSize,
                        'total_space_saved' => $totalSpaceSaved,
                        'total_original_size_formatted' => $this->imageOptimizationService->formatFileSize($totalOriginalSize),
                        'total_optimized_size_formatted' => $this->imageOptimizationService->formatFileSize($totalOptimizedSize),
                        'total_space_saved_formatted' => $this->imageOptimizationService->formatFileSize($totalSpaceSaved),
                        'overall_compression_ratio' => $overallCompressionRatio,
                        'average_compression_per_image' => count($optimizationStats) > 0 ? 
                            round(array_sum(array_column($optimizationStats, 'compression_ratio')) / count($optimizationStats), 2) : 0
                    ]
                ]
            ];

            if (!empty($failedUploads)) {
                $response['failed_uploads'] = $failedUploads;
            }

            // Log successful uploads with detailed optimization stats
            if (count($uploadedImages) > 0) {
                Log::info('API: Optimized ticket images uploaded successfully', [
                    'ticket_uuid' => $ticket->uuid,
                    'uploaded_by' => Auth::user()->name ?? 'Unknown',
                    'user_id' => Auth::id(),
                    'image_count' => count($uploadedImages),
                    'optimization_stats' => [
                        'total_original_size' => $totalOriginalSize,
                        'total_optimized_size' => $totalOptimizedSize,
                        'space_saved' => $totalSpaceSaved,
                        'compression_ratio' => $overallCompressionRatio
                    ]
                ]);
            }

            return response()->json($response, 201);

        } catch (\Exception $e) {
            Log::error('API: Image upload failed', [
                'error' => $e->getMessage(),
                'ticket_uuid' => $request->ticket_uuid ?? 'unknown',
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
                'error_code' => 'UPLOAD_FAILED'
            ], 500);
        }
    }

    /**
     * Get optimized images for a ticket via API
     */
    public function getImages($ticketUuid)
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
                    'original_size' => $image->original_size ?? 0,
                    'optimized_size' => $image->optimized_size ?? 0,
                    'original_size_formatted' => $this->imageOptimizationService->formatFileSize($image->original_size ?? 0),
                    'optimized_size_formatted' => $this->imageOptimizationService->formatFileSize($image->optimized_size ?? 0),
                    'compression_ratio' => $image->compression_ratio ?? 0,
                    'space_saved' => ($image->original_size ?? 0) - ($image->optimized_size ?? 0),
                    'space_saved_formatted' => $this->imageOptimizationService->formatFileSize(
                        ($image->original_size ?? 0) - ($image->optimized_size ?? 0)
                    ),
                    'uploaded_by' => [
                        'id' => $image->uploadedBy->id ?? null,
                        'name' => $image->uploadedBy->name ?? 'Unknown'
                    ],
                    'uploaded_at' => $image->created_at->toISOString(),
                    'updated_at' => $image->updated_at->toISOString(),
                ];
            });

            // Calculate summary statistics
            $totalOriginalSize = $images->sum('original_size');
            $totalOptimizedSize = $images->sum('optimized_size');
            $totalSpaceSaved = $totalOriginalSize - $totalOptimizedSize;
            $averageCompressionRatio = $images->count() > 0 ? $images->avg('compression_ratio') : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'ticket_uuid' => $ticket->uuid,
                    'images' => $images,
                    'summary' => [
                        'total_images' => $images->count(),
                        'total_original_size' => $totalOriginalSize,
                        'total_optimized_size' => $totalOptimizedSize,
                        'total_space_saved' => $totalSpaceSaved,
                        'total_original_size_formatted' => $this->imageOptimizationService->formatFileSize($totalOriginalSize),
                        'total_optimized_size_formatted' => $this->imageOptimizationService->formatFileSize($totalOptimizedSize),
                        'total_space_saved_formatted' => $this->imageOptimizationService->formatFileSize($totalSpaceSaved),
                        'average_compression_ratio' => round($averageCompressionRatio, 2)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('API: Failed to retrieve images', [
                'error' => $e->getMessage(),
                'ticket_uuid' => $ticketUuid,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve images: ' . $e->getMessage(),
                'error_code' => 'RETRIEVAL_FAILED'
            ], 500);
        }
    }

    /**
     * Delete an image
     */
    public function deleteImage($imageId)
    {
        try {
            $image = \App\Models\TicketImage::findOrFail($imageId);
            
            // Check if user has permission to delete this image
            if (Auth::id() !== $image->uploaded_by && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this image',
                    'error_code' => 'UNAUTHORIZED'
                ], 403);
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete database record
            $image->delete();

            Log::info('API: Image deleted successfully', [
                'image_id' => $imageId,
                'deleted_by' => Auth::user()->name ?? 'Unknown',
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('API: Failed to delete image', [
                'error' => $e->getMessage(),
                'image_id' => $imageId,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage(),
                'error_code' => 'DELETE_FAILED'
            ], 500);
        }
    }
}
