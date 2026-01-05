<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForfeitQueue extends Model
{
    use HasFactory;

    protected $table = 'forfeit_queue';
    protected $primaryKey = 'ForfeitQueueID';

    protected $fillable = [
        'DepositID',
        'BookingID',
        'OwnerUserID',
        'RenterUserID',
        'ForfeitAmount',
        'Status',
        'BankName',
        'BankAccountNumber',
        'BankAccountHolderName',
        'ForfeitReference',
        'Reason',
        'Notes',
        'ProcessedAt',
        'ProcessedBy',
        'ProofOfTransfer',
    ];

    protected $casts = [
        'ProcessedAt' => 'datetime',
        'ForfeitAmount' => 'decimal:2',
    ];

    /**
     * Get the deposit associated with this forfeit queue item
     */
    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'DepositID', 'DepositID');
    }

    /**
     * Get the booking associated with this forfeit queue item
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    /**
     * Get the item owner who will receive the forfeit amount
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'OwnerUserID', 'UserID');
    }

    /**
     * Get the renter who forfeited the deposit
     */
    public function renter()
    {
        return $this->belongsTo(User::class, 'RenterUserID', 'UserID');
    }

    /**
     * Get the admin who processed the forfeit
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'ProcessedBy', 'UserID');
    }
}
