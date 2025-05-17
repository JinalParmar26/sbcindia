<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'order_id', 'product_id', 'serial_number', 'configurations'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderProduct) {
            $orderProduct->uuid = Str::uuid();
        });
    }
}

