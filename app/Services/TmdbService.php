<?php

namespace App\Services;

use GuzzleHttp\Client;

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

    public function addToFavorites($accountId, $movieId)
    {
        $response = $this->client->request('POST', "account/{$accountId}/favorite", [
            'json' => [
                'media_type' => 'movie',
                'media_id' => $movieId,
                'favorite' => true
            ]
        ]);

        $addFavorite = json_decode($response->getBody()->getContents(), true);
        if (isset($addFavorite['success'])) {
            return response()->json(['message' => 'Filme adicionado aos favoritos.']);
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
            return response()->json(['message' => 'Filme removido dos favoritos.']);
        }
        return response()->json(['message' => 'Houve um erro ao remover o filme dos favoritos.']);
    }
}
