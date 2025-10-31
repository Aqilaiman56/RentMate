<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFee extends Model
{
    use HasFactory;

    protected $table = 'service_fees';
    protected $primaryKey = 'ServiceFeeID';

    protected $fillable = [
        'BookingID',
        'UserID',
        'ServiceFeeAmount',
        'DateCollected',
        'ServiceFeeType',
        'Description'
    ];

    protected $casts = [
        'ServiceFeeAmount' => 'decimal:2',
        'DateCollected' => 'date',
    ];

    /**
     * Get the booking associated with this service fee
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    /**
     * Get the user who paid this service fee
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}
