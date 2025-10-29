<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserID';
    
    // Only use CreatedAt, disable UpdatedAt
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null; // Disable UpdatedAt

    protected $fillable = [
        'UserName',
        'Email',
        'PasswordHash',
        'Password',
        'ProfileImage',
        'PhoneNumber',
        'BankName',
        'BankAccountNumber',
        'BankAccountHolderName',
        'Location',
        'UserType',
        'IsAdmin',
        'IsSuspended',
        'SuspendedUntil',
        'SuspensionReason',
        'SuspendedByAdminID'
    ];

    protected $hidden = [
        'PasswordHash',
        'Password',
        'remember_token'
    ];

    protected $casts = [
        'CreatedAt' => 'datetime',
        'IsAdmin' => 'boolean',
        'IsSuspended' => 'boolean',
        'SuspendedUntil' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->PasswordHash;
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