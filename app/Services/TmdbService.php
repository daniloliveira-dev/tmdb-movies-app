<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Repository\MovieRepository;

class TmdbService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.themoviedb.org/3/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('TMDB_API_KEY'),
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function allMovies($page)
    {
        $response = $this->client->request('GET', 'discover/movie?language=pt-BR&page=' . $page . '&sort_by=popularity.desc');

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getMovieById($movieId)
    {
        $response = $this->client->request('GET', "movie/{$movieId}?language=pt-BR");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function addToFavorites($accountId, $movieId)
    {
        $getMovieById = $this->getMovieById($movieId);
        $response = $this->client->request('POST', "account/{$accountId}/favorite", [
            'json' => [
                'media_type' => 'movie',
                'media_id' => $movieId,
                'favorite' => true
            ]
        ]);

        $addFavorite = json_decode($response->getBody()->getContents(), true);
        if (isset($addFavorite['success'])) {

            $getMovieByMovieId = MovieRepository::getMovieByMovieId($movieId);
            if ($getMovieByMovieId) {

                $update = MovieRepository::updateFavoriteMovie($getMovieByMovieId['movie_id'], [
                    'favorite' => true
                ]);

                if ($update) {
                    return response()->json(['message' => 'Filme adicionado aos favoritos.']);
                }
            } else {
                $save = MovieRepository::createFavoriteMovie([
                    'movie_id' => $getMovieById['id'],
                    'title' => $getMovieById['title'],
                    'director' => $getMovieById['director'] ?? 'Unknown',
                    'release_year' => $getMovieById['release_date'],
                    'genre' => implode(', ', array_column($getMovieById['genres'], 'name')),
                    'rating' => $getMovieById['vote_average']
                ]);
                if ($save) {
                    return response()->json(['message' => 'Filme adicionado aos favoritos.']);
                }
            }
        }
        return response()->json(['message' => 'Houve um erro ao adicionar o filme aos favoritos.']);
    }

    public function genres()
    {
        $response = $this->client->request('GET', 'genre/movie/list?language=pt-BR');
        return json_decode($response->getBody()->getContents(), true);
    }

    public function favorites($accountId, $page)
    {
        $response = $this->client->request('GET', "account/{$accountId}/favorite/movies?language=pt-BR&page={$page}&sort_by=popularity.desc");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function removeFromFavorite($accountId, $movieId)
    {
        $response = $this->client->request('POST', "account/{$accountId}/favorite", [
            'json' => [
                'media_type' => 'movie',
                'media_id' => $movieId,
                'favorite' => false
            ]
        ]);

        $removeFavorite = json_decode($response->getBody()->getContents(), true);
        if (isset($removeFavorite['success'])) {
            $remove = MovieRepository::deleteFavoriteMovie($movieId, [
                'favorite' => false
            ]);
            if ($remove) {
                return response()->json(['message' => 'Filme removido dos favoritos.']);
            }
        }
        return response()->json(['message' => 'Houve um erro ao remover o filme dos favoritos.']);
    }
}
