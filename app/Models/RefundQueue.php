<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundQueue extends Model
{
    use HasFactory;

    protected $table = 'refund_queue';
    protected $primaryKey = 'RefundQueueID';

    protected $fillable = [
        'DepositID',
        'BookingID',
        'UserID',
        'RefundAmount',
        'Status',
        'BankName',
        'BankAccountNumber',
        'BankAccountHolderName',
        'RefundReference',
        'Notes',
        'ProcessedAt',
        'ProcessedBy',
        'ProofOfTransfer',
    ];

    protected $casts = [
        'ProcessedAt' => 'datetime',
        'RefundAmount' => 'decimal:2',
    ];

    /**
     * Get the deposit associated with this refund queue item
     */
    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'DepositID', 'DepositID');
    }

    /**
     * Get the booking associated with this refund queue item
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    /**
     * Get the user (renter) who will receive the refund
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Get the admin who processed the refund
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'ProcessedBy', 'UserID');
    }
}
