<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'uuid',
        'subject',
        'customer_id',
        'order_product_id',
        'customer_contact_person_id',
        'attended_by',
        'type',
        'assigned_to',
        'start',
        'end',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function contactPerson()
    {
        return $this->belongsTo(CustomerContactPerson::class, 'customer_contact_person_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attendedBy()
    {
        return $this->belongsTo(User::class, 'attended_by');
    }

    public function additionalStaff()
    {
        return $this->belongsToMany(User::class, 'ticket_additional_staff', 'ticket_id', 'user_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function service()
    {
        return $this->hasMany(Service::class); // changed from hasOne, since multiple services per ticket is logical
    }
    
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function ticketImages()
    {
        return $this->hasMany(TicketImage::class);
    }
    
    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->uuid = (string) Str::uuid();
        });
    }

    public function images()
    {
        return $this->hasMany(TicketImage::class, 'ticket_id', 'id');
    }
}
