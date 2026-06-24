<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'location',
        'image',
        'slug',
        'is_published',
    ];

    protected $casts = [
        'price' => 'integer',
    ];
}
