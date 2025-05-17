<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TicketAdditionalStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'ticket_id',
        'user_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticketAdditionalStaff) {
            $ticketAdditionalStaff->uuid = Str::uuid();
        });
    }
}
