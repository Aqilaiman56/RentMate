<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $primaryKey = 'ItemID';
    public $timestamps = false; // Since you have DateAdded instead

    protected $fillable = [
        'UserID',
        'ItemName',
        'Description',
        'CategoryID',
        'LocationID',
        'Availability',
        'ImagePath',
        'DepositAmount',
        'PricePerDay', // Add this if you have it in your table
        'DateAdded'
    ];

    protected $casts = [
        'DateAdded' => 'datetime',
        'DepositAmount' => 'decimal:2',
        'PricePerDay' => 'decimal:2',
        'Availability' => 'boolean'
    ];

    /**
     * Get the user that owns the item
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Get the category of the item
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }

    /**
     * Get the location of the item
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'LocationID', 'LocationID');
    }

    /**
     * Get the bookings for the item
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'ItemID', 'ItemID');
    }

    /**
     * Get the reviews for the item
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'ItemID', 'ItemID');
    }

    /**
     * Get users who wishlisted this item
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlist', 'ItemID', 'UserID')
            ->withTimestamps();
    }

    /**
     * Get average rating for the item
     */
    public function averageRating()
    {
        return $this->reviews()->avg('Rating');
    }

    /**
     * Get total reviews count
     */
    public function totalReviews()
    {
        return $this->reviews()->count();
    }

    /**
     * Check if item is available
     */
    public function isAvailable()
    {
        return $this->Availability == 1;
    }

    /**
     * Scope to get only available items
     */
    public function scopeAvailable($query)
    {
        return $query->where('Availability', 1);
    }

    /**
     * Scope to get items by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('CategoryID', $categoryId);
    }

    /**
     * Scope to search items
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('ItemName', 'LIKE', "%{$term}%")
                    ->orWhere('Description', 'LIKE', "%{$term}%");
    }
}