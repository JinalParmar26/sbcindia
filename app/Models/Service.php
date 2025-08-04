<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'uuid',
        'ticket_id',
        'service_type',
        'start_date_time',
        'start_location_lat',
        'start_location_long',
        'start_location_name',
        'end_date_time',
        'end_location_lat',
        'end_location_long',
        'end_location_name',
        'contact_person_name',
        'payment_type',
        'log',
        'unit_model_number',
        'unit_sr_no',
        'payment_status',
        'service_description',
        'refrigerant',
        'voltage',
        'amp_r',
        'amp_y',
        'amp_b',
        'standing_pressure',
        'suction_pressure',
        'discharge_pressure',
        'suction_temp',
        'discharge_temp',
        'exv_opening',
        'chilled_water_in',
        'chilled_water_out',
        'con_water_in',
        'con_water_out',
        'water_tank_temp',
        'cabinet_temp',
        'room_temp',
        'room_supply_air_temp',
        'room_return_air_temp',
        'lp_setting',
        'hp_setting',
        'aft',
        'thermostat_setting',
    ];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function serviceItems()
    {
        return $this->morphMany(ServiceItem::class, 'serviceable');
    }

    protected static function booted()
    {
        static::creating(function ($service) {
            $service->uuid = (string) Str::uuid();
        });
    }
}
