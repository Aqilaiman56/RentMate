<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserID';

    public $timestamps = true;

    // Custom timestamp column names to match database
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'UserName',
        'Email',
        'PasswordHash',
        'PhoneNumber',
        'Location',
        'BankName',
        'BankAccountNumber',
        'BankAccountHolderName',
        'ProfileImage',
        'UserType',
        'IsAdmin',
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
        'SuspendedUntil' => 'datetime',
    ];

    // Map Laravel's expected attribute names to database column names
    public function getNameAttribute($value)
    {
        return $this->attributes['UserName'] ?? null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['UserName'] = $value;
    }

    public function getEmailAttribute($value)
    {
        return $this->attributes['Email'] ?? null;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['Email'] = $value;
    }

    public function getPasswordAttribute($value)
    {
        return $this->attributes['PasswordHash'] ?? null;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['PasswordHash'] = $value;
    }

    public function getAuthPassword()
    {
        return $this->PasswordHash;
    }

    public function getEmailVerifiedAtAttribute()
    {
        return $this->attributes['email_verified_at'] ?? null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value;
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
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