<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $fillable = [
        'serviceable_type',
        'serviceable_id',
        'item',
        'qty',
        'rate',
        'amount',
    ];

    public function serviceable()
    {
        return $this->morphTo();
    }
}
