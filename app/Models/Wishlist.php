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
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Relationship to Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }

    /**
     * Scope to get wishlist for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('UserID', $userId);
    }

    /**
     * Check if an item is in user's wishlist
     */
    public static function isInWishlist($userId, $itemId)
    {
        return self::where('UserID', $userId)
                   ->where('ItemID', $itemId)
                   ->exists();
    }

    /**
     * Toggle wishlist item (add if not exists, remove if exists)
     */
    public static function toggle($userId, $itemId)
    {
        $wishlist = self::where('UserID', $userId)
                        ->where('ItemID', $itemId)
                        ->first();

        if ($wishlist) {
            $wishlist->delete();
            return ['added' => false, 'message' => 'Removed from wishlist'];
        } else {
            self::create([
                'UserID' => $userId,
                'ItemID' => $itemId,
                'DateAdded' => now()
            ]);
            return ['added' => true, 'message' => 'Added to wishlist'];
        }
    }

    /**
     * Boot method to set default values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wishlist) {
            if (empty($wishlist->DateAdded)) {
                $wishlist->DateAdded = now();
            }
        });
    }
}