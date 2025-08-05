<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserLocation extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'uuid',
        'user_id',
        'latitude',
        'longitude',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'location_type',
        'status',
        'notes',
        'recorded_at'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'recorded_at' => 'datetime'
    ];

    /**
     * Get the user that owns the location.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get locations for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('recorded_at', $date);
    }

    /**
     * Scope to get locations for a date range
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('location_timestamp', [$startDate, $endDate]);
    }

    /**
     * Scope to get locations for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get formatted coordinates for Google Maps
     */
    public function getCoordinatesAttribute()
    {
        return $this->latitude . ',' . $this->longitude;
    }

    /**
     * Calculate distance to another location (in kilometers)
     */
    public function distanceTo($lat, $lng)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat - $this->latitude);
        $dLng = deg2rad($lng - $this->longitude);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($lat)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}
