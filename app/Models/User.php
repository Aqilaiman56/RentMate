<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // Custom table name
    protected $primaryKey = 'UserID'; // Custom primary key
    public $timestamps = false;

    protected $fillable = [
        'UserName', 'Email', 'PasswordHash', 'ProfileImage', 'UserType', 'IsAdmin'
    ];

    protected $hidden = [
        'PasswordHash'
    ];

    // Override default password field
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