<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use App\Repository\MovieRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MovieController extends Controller
{
    protected MovieRepository $repository;
    protected TmdbService $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index()
    {
        $movies = $this->tmdbService->allMovies(1);

        if (Auth::check()) {
            return Inertia::render('Dashboard', [
                'movies' => $movies == [] ? $this->repository->getAllMovies() : $movies,
                'user' => Auth::user()->name
            ]);
        } else {
            return Inertia::render('Welcome', [
                'movies' => $movies
            ]);
        }
    }

    public function loadMoreMovies($page)
    {
        return $this->tmdbService->allMovies($page);
    }

    public function favorites()
    {
        $favorites = $this->tmdbService->favorites(Auth::user()->account_id, 1);
        $genres = $this->tmdbService->genres();

        if (Auth::check() && $favorites['results'] != [] && $genres['genres'] != []) {
            return Inertia::render(
                'Favorites',
                [
                    'favorites' => $favorites,
                    'genres' => $genres['genres'],
                    'user' => Auth::user()->name
                ],
            );
        }
        return Inertia::render(
            'Favorites',
            [
                'favorites' => [],
                'genres' => $genres['genres'] ?? [],
                'user' => Auth::user()->name
            ],
        );
    }

    public function addToFavorites($movieId)
    {
        return $this->tmdbService->addToFavorites(Auth::user()->account_id, $movieId);
    }

    public function removeFromFavorites($movieId)
    {
        return $this->tmdbService->removeFromFavorite(Auth::user()->account_id, $movieId);
    }
}
