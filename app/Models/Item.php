<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'Availability',
        'Quantity',
        'AvailableQuantity',
        'DateAdded'
    ];

    protected $casts = [
        'DateAdded' => 'datetime',
        'Availability' => 'boolean',
        'PricePerDay' => 'decimal:2',
        'DepositAmount' => 'decimal:2',
        'Quantity' => 'integer',
        'AvailableQuantity' => 'integer',
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
     * Relationship to ItemImages (multiple images)
     */
    public function images()
    {
        return $this->hasMany(ItemImage::class, 'ItemID', 'ItemID')->orderBy('DisplayOrder');
    }

    /**
     * Check if item is in user's wishlist
     */
    public function isInWishlist($userId)
    {
        return $this->wishlists()->where('UserID', $userId)->exists();
    }

    /**
     * Check if item has available quantity
     */
    public function hasAvailableQuantity()
    {
        return $this->AvailableQuantity > 0;
    }

    /**
     * Get number of items currently booked (ACTIVE BOOKINGS NOW)
     */
    public function getBookedQuantity()
    {
        return $this->bookings()
            ->whereIn('Status', ['confirmed', 'Confirmed', 'ongoing', 'Ongoing'])
            ->where('StartDate', '<=', now())
            ->where('EndDate', '>=', now())
            ->count();
    }

    /**
     * Update available quantity based on active bookings
     * Also automatically sets Availability to false when AvailableQuantity reaches 0
     */
    public function updateAvailableQuantity()
    {
        $bookedQuantity = $this->getBookedQuantity();
        $availableQuantity = max(0, $this->Quantity - $bookedQuantity);

        // Update AvailableQuantity and auto-set Availability based on quantity
        $this->update([
            'AvailableQuantity' => $availableQuantity,
            'Availability' => $availableQuantity > 0
        ]);

        return $availableQuantity;
    }

    /**
     * Check if item is currently available (not booked)
     */
    public function isCurrentlyAvailable()
    {
        return $this->hasAvailableQuantity();
    }

    /**
     * Check if item is available for a specific date range
     */
    public function isAvailableForDates($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Count overlapping bookings
        $overlappingBookings = $this->bookings()
            ->whereIn('Status', ['confirmed', 'Confirmed', 'ongoing', 'Ongoing'])
            ->where(function($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    // New booking starts during existing booking
                    $q->where('StartDate', '<=', $start)
                      ->where('EndDate', '>=', $start);
                })
                ->orWhere(function($q) use ($start, $end) {
                    // New booking ends during existing booking
                    $q->where('StartDate', '<=', $end)
                      ->where('EndDate', '>=', $end);
                })
                ->orWhere(function($q) use ($start, $end) {
                    // New booking completely encompasses existing booking
                    $q->where('StartDate', '>=', $start)
                      ->where('EndDate', '<=', $end);
                });
            })
            ->count();

        // Check if we have enough quantity available
        $availableForPeriod = $this->Quantity - $overlappingBookings;
        
        return $availableForPeriod > 0;
    }

    /**
     * Get all booked date ranges for this item
     */
    public function getBookedDatesAttribute()
    {
        return $this->bookings()
            ->whereIn('Status', ['confirmed', 'Confirmed', 'ongoing', 'Ongoing'])
            ->where('EndDate', '>=', now())
            ->get(['StartDate', 'EndDate'])
            ->map(function($booking) {
                return [
                    'start' => $booking->StartDate->format('Y-m-d'),
                    'end' => $booking->EndDate->format('Y-m-d')
                ];
            });
    }

    /**
     * Auto-update availability based on bookings
     */
    public function updateAvailabilityStatus()
    {
        return $this->updateAvailableQuantity();
    }

    /**
     * Scope for available items (considering active bookings)
     */
    public function scopeAvailable($query)
    {
        return $query->where('Availability', true)
            ->where('AvailableQuantity', '>', 0);
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
            if (!isset($item->Quantity)) {
                $item->Quantity = 1;
            }
            if (!isset($item->AvailableQuantity)) {
                $item->AvailableQuantity = $item->Quantity;
            }
        });
    }
}