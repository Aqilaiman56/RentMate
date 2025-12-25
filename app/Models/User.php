<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserID';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'PhoneNumber',
        'Location',
        'BankName',
        'BankAccountNumber',
        'BankAccountHolderName',
        'IsSuspended',
        'SuspendedUntil',
        'SuspensionReason',
        'SuspendedByAdminID'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relationships
    public function items()
    {
        return $this->hasMany(Item::class, 'UserID', 'UserID');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'UserID', 'UserID');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'UserID', 'UserID');
    }

    public function reportsMade()
    {
        return $this->hasMany(Penalty::class, 'ReportedByID', 'UserID');
    }

    public function reportsReceived()
    {
        return $this->hasMany(Penalty::class, 'ReportedUserID', 'UserID');
    }

    /**
     * Check if user is currently suspended
     */
    public function isCurrentlySuspended()
    {
        if (!$this->IsSuspended) {
            return false;
        }

        // If no expiry date, suspension is permanent
        if (!$this->SuspendedUntil) {
            return true;
        }

        // Check if suspension has expired
        if (now()->greaterThan($this->SuspendedUntil)) {
            // Auto-unsuspend if expired
            $this->update([
                'IsSuspended' => false,
                'SuspendedUntil' => null,
            ]);
            return false;
        }

        return true;
    }

    /**
     * Admin who suspended this user
     */
    public function suspendedBy()
    {
        return $this->belongsTo(User::class, 'SuspendedByAdminID', 'UserID');
    }
}