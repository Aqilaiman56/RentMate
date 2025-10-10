<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'taxes';
    protected $primaryKey = 'TaxID';

    protected $fillable = [
        'BookingID',
        'UserID',
        'TaxAmount',
        'DateCollected',
        'TaxType',
        'Description'
    ];

    protected $casts = [
        'TaxAmount' => 'decimal:2',
        'DateCollected' => 'date',
    ];

    /**
     * Get the booking associated with this tax
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    /**
     * Get the user who paid this tax
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}