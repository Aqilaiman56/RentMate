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
}
