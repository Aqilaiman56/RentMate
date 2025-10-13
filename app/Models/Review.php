<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Review Model
 */
class Review extends Model
{
    use HasFactory;

    protected $table = 'review';
    protected $primaryKey = 'ReviewID';
    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'ItemID',
        'Rating',
        'Comment',
        'DatePosted',
        'IsReported'
    ];

    protected $casts = [
        'DatePosted' => 'datetime',
        'Rating' => 'integer',
        'IsReported' => 'boolean'
    ];

    /**
     * Get the user who wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Get the item being reviewed
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }

    /**
     * Scope to get only non-reported reviews
     */
    public function scopeNotReported($query)
    {
        return $query->where('IsReported', 0);
    }

    /**
     * Scope to order by most recent
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('DatePosted', 'desc');
    }
}