<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomerContactPerson extends Model
{
    protected $table = 'customer_contact_person';
    protected $fillable = [
        'uuid',
        'customer_id',
        'name',
        'email',
        'phone_number',
        'alternate_phone_number',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customerContactPerson) {
            $customerContactPerson->uuid = Str::uuid();
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
