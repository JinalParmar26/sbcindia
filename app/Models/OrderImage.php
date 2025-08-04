<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrderImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'image_path',
        'image_name',
        'image_size',
        'image_type',
        'sort_order',
        'description'
    ];

    protected $casts = [
        'image_size' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Get the order that owns the image
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the full URL of the image
     */
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    /**
     * Get the image file size in human readable format
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->image_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Delete the image file when the model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($orderImage) {
            if (Storage::exists($orderImage->image_path)) {
                Storage::delete($orderImage->image_path);
            }
        });
    }
}
