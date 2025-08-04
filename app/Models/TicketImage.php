<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketImage extends Model
{
    use HasFactory;

    protected $table = 'ticket_images';

    protected $fillable = [
        'ticket_id',
        'image_path',
        'description',
        'original_format',
        'optimized_format',
        'original_size',
        'optimized_size',
        'compression_ratio',
        'uploaded_by',
    ];

    protected $casts = [
        'original_size' => 'integer',
        'optimized_size' => 'integer',
        'compression_ratio' => 'decimal:2',
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    public function getFileSizeFormattedAttribute()
    {
        if ($this->optimized_size) {
            return $this->formatFileSize($this->optimized_size);
        }
        return 'Unknown';
    }

    public function getOriginalSizeFormattedAttribute()
    {
        if ($this->original_size) {
            return $this->formatFileSize($this->original_size);
        }
        return 'Unknown';
    }

    public function getSpaceSavedAttribute()
    {
        if ($this->original_size && $this->optimized_size) {
            return $this->original_size - $this->optimized_size;
        }
        return 0;
    }

    public function getSpaceSavedFormattedAttribute()
    {
        return $this->formatFileSize($this->getSpaceSavedAttribute());
    }

    // Helper methods
    private function formatFileSize($bytes)
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

    // Model events
    protected static function booted()
    {
        static::deleting(function ($ticketImage) {
            // Delete the physical file when the model is deleted
            if ($ticketImage->image_path && Storage::disk('public')->exists($ticketImage->image_path)) {
                Storage::disk('public')->delete($ticketImage->image_path);
            }
        });
    }
}
