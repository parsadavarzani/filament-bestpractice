<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
