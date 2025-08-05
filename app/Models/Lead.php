<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';
    protected $primaryKey = 'lead_id';

    protected $fillable = [
        'uuid',
        'lead_name',
        'lead_owner_id',
        'collaborators',
        'status',
        'industry',
        'lead_source',
        'price_group',
        'title',
        'address',
        'country',
        'pincode',
        'email',
        'visit_started_at',
        'visit_ended_at',
        'visit_status',
        'file_url',
        'deal_title',
        'deal_amount',
        'deal_status',
    ];

    protected $casts = [
        'visit_started_at' => 'datetime',
        'visit_ended_at' => 'datetime',
        'deal_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lead) {
            if (empty($lead->uuid)) {
                $lead->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get the user who owns this lead
     */
    public function leadOwner()
    {
        return $this->belongsTo(User::class, 'lead_owner_id');
    }

    /**
     * Get collaborators as an array
     */
    public function getCollaboratorsArrayAttribute()
    {
        return $this->collaborators ? explode(',', $this->collaborators) : [];
    }

    /**
     * Set collaborators from an array
     */
    public function setCollaboratorsArrayAttribute($value)
    {
        $this->attributes['collaborators'] = is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by lead owner
     */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('lead_owner_id', $userId);
    }

    /**
     * Get visit duration in minutes
     */
    public function getVisitDurationAttribute()
    {
        if ($this->visit_started_at && $this->visit_ended_at) {
            return $this->visit_started_at->diffInMinutes($this->visit_ended_at);
        }
        return null;
    }

    /**
     * Check if visit is ongoing
     */
    public function getIsVisitOngoingAttribute()
    {
        return $this->visit_status === 'Started' && $this->visit_started_at && !$this->visit_ended_at;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        switch (strtolower($this->status)) {
            case 'new':
                return 'badge-primary';
            case 'converted':
                return 'badge-success';
            case 'qualified':
                return 'badge-info';
            case 'contacted':
                return 'badge-warning';
            case 'lost':
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Get visit status badge class
     */
    public function getVisitStatusBadgeClassAttribute()
    {
        switch (strtolower($this->visit_status)) {
            case 'not started':
                return 'badge-secondary';
            case 'started':
                return 'badge-warning';
            case 'ended':
                return 'badge-success';
            default:
                return 'badge-secondary';
        }
    }
}
