<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Location Model
 */
class Location extends Model
{
    use HasFactory;

    protected $table = 'location';
    protected $primaryKey = 'LocationID';
    public $timestamps = false;

    protected $fillable = ['LocationName'];

    /**
     * Get items in this location
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'LocationID', 'LocationID');
    }
}
