<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Exception;

class ImageOptimizationService
{
    /**
     * Optimize and convert image to WebP format
     * 
     * @param string $imageData Base64 encoded image data
     * @param int $maxWidth Maximum width for the image
     * @param int $maxHeight Maximum height for the image
     * @param int $quality Quality for WebP conversion (0-100)
     * @return array|false Returns optimized image data or false on failure
     */
    public function optimizeImage($imageData, $maxWidth = 1200, $maxHeight = 800, $quality = 80)
    {
        try {
            // Validate and decode base64 image
            if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                throw new Exception('Invalid base64 image format');
            }

            $originalFormat = $matches[1];
            $base64Data = substr($imageData, strpos($imageData, ',') + 1);
            $decodedImage = base64_decode($base64Data);

            if (!$decodedImage) {
                throw new Exception('Failed to decode base64 image');
            }

            // Get original file size
            $originalSize = strlen($decodedImage);

            // Create image instance using Intervention Image
            $image = Image::make($decodedImage);
            
            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Calculate new dimensions while maintaining aspect ratio
            $newDimensions = $this->calculateOptimalDimensions(
                $originalWidth, 
                $originalHeight, 
                $maxWidth, 
                $maxHeight
            );

            // Resize image if needed
            if ($newDimensions['width'] < $originalWidth || $newDimensions['height'] < $originalHeight) {
                $image->resize($newDimensions['width'], $newDimensions['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Prevent upsizing
                });
            }

            // Convert to WebP format
            $optimizedImage = $image->encode('webp', $quality);
            $optimizedSize = strlen($optimizedImage);

            // Calculate compression ratio
            $compressionRatio = round((($originalSize - $optimizedSize) / $originalSize) * 100, 2);

            return [
                'success' => true,
                'image_data' => $optimizedImage,
                'format' => 'webp',
                'original_format' => $originalFormat,
                'original_size' => $originalSize,
                'optimized_size' => $optimizedSize,
                'compression_ratio' => $compressionRatio,
                'original_dimensions' => [
                    'width' => $originalWidth,
                    'height' => $originalHeight
                ],
                'optimized_dimensions' => [
                    'width' => $image->width(),
                    'height' => $image->height()
                ]
            ];

        } catch (Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Calculate optimal dimensions while maintaining aspect ratio
     * 
     * @param int $originalWidth
     * @param int $originalHeight
     * @param int $maxWidth
     * @param int $maxHeight
     * @return array
     */
    private function calculateOptimalDimensions($originalWidth, $originalHeight, $maxWidth, $maxHeight)
    {
        // If image is already smaller than max dimensions, don't resize
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            return [
                'width' => $originalWidth,
                'height' => $originalHeight
            ];
        }

        // Calculate scaling ratios
        $widthRatio = $maxWidth / $originalWidth;
        $heightRatio = $maxHeight / $originalHeight;

        // Use the smaller ratio to ensure image fits within bounds
        $ratio = min($widthRatio, $heightRatio);

        return [
            'width' => (int) round($originalWidth * $ratio),
            'height' => (int) round($originalHeight * $ratio)
        ];
    }

    /**
     * Format file size for human readability
     * 
     * @param int $bytes
     * @return string
     */
    public function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Store optimized image and return storage information
     * 
     * @param string $imageData Binary image data
     * @param string $ticketUuid
     * @param int $index
     * @param string $originalFormat
     * @return array
     */
    public function storeOptimizedImage($imageData, $ticketUuid, $index, $originalFormat = 'unknown')
    {
        try {
            // Generate unique filename with WebP extension
            $filename = 'ticket_' . $ticketUuid . '_' . time() . '_' . $index . '.webp';
            $filePath = 'ticket_images/' . $filename;

            // Store optimized image
            $stored = Storage::disk('public')->put($filePath, $imageData);

            if (!$stored) {
                throw new Exception('Failed to store image to disk');
            }

            return [
                'success' => true,
                'file_path' => $filePath,
                'filename' => $filename,
                'storage_url' => asset('storage/' . $filePath)
            ];

        } catch (Exception $e) {
            Log::error('Failed to store optimized image: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
