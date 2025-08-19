<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'title',
        'director',
        'release_year',
        'genre',
        'rating',
        'favorite',
    ];

    protected $casts = [
        'movie_id' => 'integer',
        'title' => 'string',
        'director' => 'string',
        'release_year' => 'date',
        'genre' => 'string',
        'rating' => 'float',
        'favorite' => 'boolean'
    ];
}
