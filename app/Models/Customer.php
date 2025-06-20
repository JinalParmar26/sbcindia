<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'company_name',
        'email',
        'phone_number',
        'address',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            $customer->uuid = Str::uuid();
        });
    }

    public function contactPersons()
    {
        return $this->hasMany(CustomerContactPerson::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
