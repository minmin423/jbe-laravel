<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'category';
    protected $fillable = ['name', 'description'];

    public function category()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}
