<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlist';
    protected $primaryKey = 'WishlistID';
    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'ItemID',
        'DateAdded'
    ];

    protected $casts = [
        'DateAdded' => 'datetime'
    ];

    /**
     * Get the user that owns the wishlist entry
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Get the item in the wishlist
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }
}