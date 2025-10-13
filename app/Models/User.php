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
        'Location',
        'UserType', 
        'IsAdmin'
    ];

    protected $hidden = [
        'PasswordHash',
        'Password',
        'remember_token'
    ];

    protected $casts = [
        'CreatedAt' => 'datetime',
        'IsAdmin' => 'boolean',
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
}