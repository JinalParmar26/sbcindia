<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadVisitLog extends Model
{
    use HasFactory;

    protected $table = 'lead_visit_logs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'uuid',
        'lead_id',
        'user_id',
        'lead_type',
        'rating',
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

    // ✅ Relationships
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'lead_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(LeadVisitLogImage::class, 'lead_visit_log_id', 'id');
    }
}
