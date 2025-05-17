<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

class Ticket extends Model
{
    use HasFactory;

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

    public function additionalStaff()
    {
        return $this->belongsToMany(User::class, 'ticket_additional_staff', 'ticket_id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->uuid = Str::uuid();
        });
    }
}
