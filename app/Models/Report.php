<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $primaryKey = 'ReportID';

    protected $fillable = [
        'ReportedByID',
        'ReportedUserID',
        'BookingID',
        'ItemID',
        'ReportType',
        'Priority',
        'Subject',
        'Description',
        'EvidencePath',
        'Status',
        'ReviewedByAdminID',
        'AdminNotes',
        'DateReported',
        'DateResolved'
    ];

    protected $casts = [
        'DateReported' => 'date',
        'DateResolved' => 'date',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'ReportedByID', 'UserID');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'ReportedUserID', 'UserID');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'ReviewedByAdminID', 'UserID');
    }

    public function penalty()
    {
        return $this->hasOne(Penalty::class, 'ReportID', 'ReportID');
    }

    public function scopePending($query)
    {
        return $query->where('Status', 'pending');
    }

    public function scopeResolved($query)
    {
        return $query->where('Status', 'resolved');
    }

    public function hasPenalty()
    {
        return $this->penalty()->exists();
    }
}