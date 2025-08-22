<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';
    protected $primaryKey = 'lead_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'source',
        'industry',
        'company_name',
        'address',
        'country',
        'state',
        'city',
        'area',
        'pincode',
        'email',
        'contact',
        'whatsapp_number',
    ];

    // ✅ Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function visitLogs()
    {
        return $this->hasMany(LeadVisitLog::class, 'lead_id', 'lead_id');
    }
}
