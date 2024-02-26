<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    // equivalent to mySQL table    
    protected $collection = 'items';
    protected $fillable = ['name', 'description', 'price', 'quantity', 'category_id'];

    public function item()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
