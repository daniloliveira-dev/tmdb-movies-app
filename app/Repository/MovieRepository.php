<?php

namespace App\Repository;

use App\Models\Movie;

class MovieRepository extends Movie
{
    public static function getAllMovies()
    {
        return Movie::all();
    }

    public static function getMovieByMovieId($movieId)
    {
        return Movie::where('movie_id', $movieId)->first();
    }

    public static function createFavoriteMovie(array $data)
    {
        return Movie::create($data);
    }

    public static function updateFavoriteMovie($movieId, array $data)
    {
        $movie = Movie::where('movie_id', $movieId)->first();
        if ($movie) {
            return $movie->update($data);
        }
        return false;
    }

    public static function deleteFavoriteMovie($movieId)
    {
        $movie = Movie::where('movie_id', $movieId)->first();
        if ($movie) {
            $movie->update(['favorite' => false]);
            return true;
        }
        return false;
    }
}
