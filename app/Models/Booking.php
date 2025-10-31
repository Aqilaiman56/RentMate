<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Booking Model
 */
class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'BookingID';
    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'ItemID',
        'StartDate',
        'EndDate',
        'TotalAmount',
        'DepositAmount',
        'ServiceFeeAmount',
        'TotalPaid',
        'Status',
        'ReturnConfirmed',
        'BookingDate'
    ];

    protected $casts = [
        'StartDate' => 'date',
        'EndDate' => 'date',
        'BookingDate' => 'datetime',
        'ServiceFeeAmount' => 'decimal:2',
        'TotalPaid' => 'decimal:2',
        'ReturnConfirmed' => 'boolean'
    ];

    /**
     * Get the user who made the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Get the item being booked
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }

    /**
     * Payment & deposit
     */
        public function payment()
    {
        return $this->hasOne(Payment::class, 'BookingID', 'BookingID');
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class, 'BookingID', 'BookingID');
    }

    /**
     * Check if booking is active
     */
    public function isActive()
    {
        return $this->Status === 'Approved';
    }

    /**
     * Scope to get approved bookings
     */
    public function scopeApproved($query)
    {
        return $query->where('Status', 'Approved');
    }

        /**
     * Get penalties related to this booking
     */
    public function penalties()
    {
        return $this->hasMany(Penalty::class, 'BookingID', 'BookingID');
    }

        /**
     * Get the service fee for this booking
     */
    public function serviceFee()
    {
        return $this->hasOne(ServiceFee::class, 'BookingID', 'BookingID');
    }

    /**
     * Scope to get bookings for a specific date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('StartDate', [$startDate, $endDate])
              ->orWhereBetween('EndDate', [$startDate, $endDate])
              ->orWhere(function($q2) use ($startDate, $endDate) {
                  $q2->where('StartDate', '<=', $startDate)
                     ->where('EndDate', '>=', $endDate);
              });
        });
    }
}