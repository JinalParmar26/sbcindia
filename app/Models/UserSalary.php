<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary_date',
        'check_in',
        'check_out',
        'normal_hours',
        'service_hours',
        'extra_hours',
        'normal_salary',
        'service_salary',
        'extra_salary',
        'total_salary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
