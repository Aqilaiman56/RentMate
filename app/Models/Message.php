<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'MessageID';
    public $timestamps = false;

    protected $fillable = [
        'SenderID',
        'ReceiverID',
        'ItemID',
        'MessageContent',
        'IsRead',
        'SentAt'
    ];

    protected $casts = [
        'SentAt' => 'datetime',
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
     * Get the related item
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }

        public function sentMessages()
    {
        return $this->hasMany(Message::class, 'SenderID', 'UserID');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'ReceiverID', 'UserID');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'UserID', 'UserID');
    }

    /**
     * Scope to get conversation between two users
     */
    public function scopeConversation($query, $userId1, $userId2)
    {
        return $query->where(function($q) use ($userId1, $userId2) {
            $q->where('SenderID', $userId1)->where('ReceiverID', $userId2);
        })->orWhere(function($q) use ($userId1, $userId2) {
            $q->where('SenderID', $userId2)->where('ReceiverID', $userId1);
        })->orderBy('SentAt', 'asc');
    }
}