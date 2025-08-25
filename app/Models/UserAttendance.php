<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAttendance extends Model
{
    use HasFactory;

    protected $table = 'user_attendance';

    protected $fillable = [
        'user_id',
        'check_in',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_location_name',
        'check_out',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_location_name',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
