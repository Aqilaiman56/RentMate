<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $table = 'deposits';
    protected $primaryKey = 'DepositID';

    protected $fillable = [
        'BookingID',
        'DepositAmount',
        'Status',
        'DateCollected',
        'DateRefunded',
        'RefundDate',
        'RefundMethod',
        'RefundReference',
        'Notes'
    ];

    protected $casts = [
        'DepositAmount' => 'decimal:2',
        'DateCollected' => 'date',
        'DateRefunded' => 'datetime',
        'RefundDate' => 'date',
    ];

    /**
     * Get the booking associated with this deposit
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    /**
     * Scope to get held deposits
     */
    public function scopeHeld($query)
    {
        return $query->where('Status', 'held');
    }

    /**
     * Scope to get refunded deposits
     */
    public function scopeRefunded($query)
    {
        return $query->where('Status', 'refunded');
    }

    /**
     * Scope to get forfeited deposits
     */
    public function scopeForfeited($query)
    {
        return $query->where('Status', 'forfeited');
    }

    /**
     * Check if deposit can be refunded
     */
    public function canRefund()
    {
        return in_array($this->Status, ['held', 'partial']);
    }
}