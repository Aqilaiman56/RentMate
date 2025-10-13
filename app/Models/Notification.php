<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'NotificationID';
    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'Type',
        'Title',
        'Content',
        'RelatedID',
        'RelatedType',
        'IsRead',
        'CreatedAt'
    ];

    protected $casts = [
        'CreatedAt' => 'datetime',
        'IsRead' => 'boolean'
    ];

    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('IsRead', false);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['IsRead' => true]);
    }
}