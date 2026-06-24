<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($category) => $category->slug = \Illuminate\Support\Str::slug($category->name));
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}