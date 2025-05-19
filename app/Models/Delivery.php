<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'ticket_id',
        'challan_no',
        'vehical_no',
        'delivered_by',
        'log',
        'start_date_time',
        'start_location_lat',
        'start_location_long',
        'start_location_name',
        'end_date_time',
        'end_location_lat',
        'end_location_long',
        'end_location_name',
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function serviceItems()
    {
        return $this->morphMany(ServiceItem::class, 'serviceable');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($delivery) {
            $delivery->uuid = Str::uuid();
        });
    }
}
