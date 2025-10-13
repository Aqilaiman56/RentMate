<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'PaymentID';
    public $timestamps = false;

    protected $fillable = [
        'BookingID',
        'BillCode',
        'Amount',
        'PaymentMethod',
        'Status',
        'TransactionID',
        'PaymentResponse',
        'PaymentDate',
        'CreatedAt'
    ];

    protected $casts = [
        'PaymentDate' => 'datetime',
        'CreatedAt' => 'datetime',
        'Amount' => 'decimal:2'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }
}