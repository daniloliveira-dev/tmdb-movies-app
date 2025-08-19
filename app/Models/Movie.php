<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'director',
        'release_year',
        'genre',
        'rating',
    ];

    protected $casts = [
        'title' => 'string',
        'director' => 'string',
        'release_year' => 'date',
        'genre' => 'string',
        'rating' => 'float',
    ];

    public function getMovies()
    {
        return Movie::all();
    }

    public function getMovieById($id)
    {
        return Movie::find($id);
    }

    public function updateMovie($id, array $data)
    {
        $movie = Movie::find($id);
        if ($movie) {
            $movie->update($data);
            return $movie;
        }
        return false;
    }

    public function deleteMovie($id)
    {
        $movie = Movie::find($id);
        if ($movie) {
            $movie->delete();
            return true;
        }
        return false;
    }
}
