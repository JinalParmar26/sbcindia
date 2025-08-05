<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'address',
        'accuracy',
        'altitude',
        'speed',
        'provider',
        'location_timestamp',
        'additional_data'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'altitude' => 'decimal:2',
        'speed' => 'decimal:2',
        'location_timestamp' => 'datetime',
        'additional_data' => 'array'
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
        return $query->whereDate('location_timestamp', $date);
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
