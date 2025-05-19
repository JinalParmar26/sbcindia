<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Marketing extends Model
{
    use HasFactory;

    protected $table = 'marketing';

    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'company_phone_number',
        'contact_person_name',
        'contact_person_phone_number',
        'visit_date',
        'visit_start_time',
        'visit_start_latitude',
        'visit_start_longitude',
        'visit_start_location_name',
        'visit_end_time',
        'visit_end_latitude',
        'visit_end_longitude',
        'visit_end_location_name',
        'notes',
        'presented_products',
    ];

    protected $casts = [
        'presented_products' => 'array',
        'visit_date' => 'date',
        'visit_start_time' => 'datetime:H:i',
        'visit_end_time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($marketing) {
            $marketing->uuid = Str::uuid();
        });
    }
}
