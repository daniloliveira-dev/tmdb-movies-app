<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\MovieController::class, 'index'])->name('movie.index');
Route::get('/load-movies/{page}', [App\Http\Controllers\MovieController::class, 'loadMoreMovies'])->name('movies.load');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(App\Http\Controllers\MovieController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::get('/favorites', 'favorites')->name('favorites');
    Route::post('/favorites/{movieId}', 'addToFavorites')->name('favorites.add');
    Route::post('/remove-favorites/{movieId}', 'removeFromFavorites')->name('favorites.remove');
});
