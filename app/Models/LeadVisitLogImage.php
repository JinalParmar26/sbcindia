<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadVisitLogImage extends Model
{
    use HasFactory;

    protected $table = 'lead_visit_log_images';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'uuid',
        'lead_visit_log_id',
        'user_id',
        'image',
    ];

    // ✅ Relationships
    public function visitLog()
    {
        return $this->belongsTo(LeadVisitLog::class, 'lead_visit_log_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
