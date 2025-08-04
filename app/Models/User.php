<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserLocation;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //  protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ]; 
    protected $guarded=[];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'notifications_enabled' => 'boolean',
        'ticket_notifications' => 'boolean',
        'attendance_notifications' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = Str::uuid();
            }
        });
    }

    public function requiresApproval(): bool
    {
        return in_array($this->role, ['staff', 'marketing']) && $this->approval_required == 'yes';
    }

    public function userAttendances()
    {
        return $this->hasMany(UserAttendance::class);
    }

    public function overtimeLogs()
    {
        return $this->hasMany(OvertimeLog::class);
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function attendedTickets()
    {
        return $this->hasMany(Ticket::class, 'attended_by');
    }

    /**
     * Get orders through tickets assigned to this user
     * This returns orders related to tickets assigned to this user
     */
    public function orders()
    {
        // Get orders through tickets assigned to this user
        return Order::whereHas('orderProducts.tickets', function($query) {
            $query->where('assigned_to', $this->id);
        });
    }

    /**
     * Get the user's location history
     */
    public function locations()
    {
        return $this->hasMany(UserLocation::class);
    }

    /**
     * Alias for locations relationship (for consistency)
     */
    public function userLocations()
    {
        return $this->hasMany(UserLocation::class);
    }
}
