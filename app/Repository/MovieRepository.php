<?php

namespace App\Repository;

use App\Models\Movie;

class MovieRepository extends Movie
{
    public function getAllMovies()
    {
        return Movie::all();
    }

    public function getMovieById($id)
    {
        return Movie::find($id);
    }

    public function createMovie(array $data)
    {
        return Movie::create($data);
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
