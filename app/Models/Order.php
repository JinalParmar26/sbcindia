<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['uuid', 'title', 'customer_id'];

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->uuid = Str::uuid();
        });
    }
}
