<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Message Model
 */
class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'MessageID';
    public $timestamps = false;

    protected $fillable = [
        'SenderID',
        'ReceiverID',
        'Content',
        'Timestamp',
        'IsRead',
        'BookingID'
    ];

    protected $casts = [
        'Timestamp' => 'datetime',
        'IsRead' => 'boolean'
    ];

    /**
     * Get the sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'SenderID', 'UserID');
    }

    /**
     * Get the receiver of the message
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'ReceiverID', 'UserID');
    }

    /**
     * Get the related booking (if any)
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->IsRead = true;
        $this->save();
    }

    /**
     * Scope to get unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('IsRead', 0);
    }

    /**
     * Scope to get messages between two users
     */
    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function($q) use ($userId1, $userId2) {
            $q->where(function($q2) use ($userId1, $userId2) {
                $q2->where('SenderID', $userId1)
                   ->where('ReceiverID', $userId2);
            })->orWhere(function($q2) use ($userId1, $userId2) {
                $q2->where('SenderID', $userId2)
                   ->where('ReceiverID', $userId1);
            });
        })->orderBy('Timestamp', 'asc');
    }
}