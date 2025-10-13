<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $primaryKey = 'ItemID';
    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'ItemName',
        'Description',
        'CategoryID',
        'LocationID',
        'DepositAmount',
        'PricePerDay',
        'ImagePath',
        'Availability',
        'DateAdded'
    ];

    protected $casts = [
        'DateAdded' => 'datetime',
        'Availability' => 'boolean',
        'PricePerDay' => 'decimal:2',
        'DepositAmount' => 'decimal:2'
    ];

    /**
     * Relationship to User (Owner)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Relationship to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }

    /**
     * Relationship to Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'LocationID', 'LocationID');
    }

    /**
     * Relationship to Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'ItemID', 'ItemID');
    }

    /**
     * Relationship to Reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'ItemID', 'ItemID');
    }

    /**
     * Relationship to Wishlists
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'ItemID', 'ItemID');
    }

    /**
     * Relationship to Messages (if applicable)
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'ItemID', 'ItemID');
    }

    /**
     * Check if item is in user's wishlist
     */
    public function isInWishlist($userId)
    {
        return $this->wishlists()->where('UserID', $userId)->exists();
    }

    /**
     * Scope for available items
     */
    public function scopeAvailable($query)
    {
        return $query->where('Availability', true);
    }

    /**
     * Scope for items by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('CategoryID', $categoryId);
    }

    /**
     * Scope for items by location
     */
    public function scopeByLocation($query, $locationId)
    {
        return $query->where('LocationID', $locationId);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('Rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Boot method to set default values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->DateAdded)) {
                $item->DateAdded = now();
            }
            if (!isset($item->Availability)) {
                $item->Availability = true;
            }
        });
    }
}