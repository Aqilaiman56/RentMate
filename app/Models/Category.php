<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Category Model
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey = 'CategoryID';
    public $timestamps = false;

    protected $fillable = ['CategoryName'];

    /**
     * Get items in this category
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'CategoryID', 'CategoryID');
    }
}
