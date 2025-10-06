<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Penalty Model
 */
class Penalty extends Model
{
    use HasFactory;

    protected $table = 'penalties';
    protected $primaryKey = 'PenaltyID';
    public $timestamps = false;

    protected $fillable = [
        'ReportedByID',
        'ReportedUserID',
        'BookingID',
        'ItemID',
        'ApprovedByAdminID',
        'Description',
        'EvidencePath',
        'PenaltyAmount',
        'ResolvedStatus',
        'DateReported'
    ];

    protected $casts = [
        'DateReported' => 'datetime',
        'PenaltyAmount' => 'decimal:2',
        'ResolvedStatus' => 'boolean'
    ];

    /**
     * Get the user who reported
     */
    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'ReportedByID', 'UserID');
    }

    /**
     * Get the user who was reported
     */
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'ReportedUserID', 'UserID');
    }

    /**
     * Get the item related to this penalty
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }

    /**
     * Get the booking related to this penalty
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    /**
     * Get the admin who approved/resolved this penalty
     */
    public function approvedByAdmin()
    {
        return $this->belongsTo(User::class, 'ApprovedByAdminID', 'UserID');
    }

    /**
     * Scope to get pending penalties
     */
    public function scopePending($query)
    {
        return $query->where('ResolvedStatus', 0);
    }

    /**
     * Scope to get resolved penalties
     */
    public function scopeResolved($query)
    {
        return $query->where('ResolvedStatus', 1);
    }

    /**
     * Scope to get penalties with amounts
     */
    public function scopeWithPenalty($query)
    {
        return $query->whereNotNull('PenaltyAmount')
                     ->where('PenaltyAmount', '>', 0);
    }
}