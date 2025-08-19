<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use App\Services\TmdbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $tmdbServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tmdbServiceMock = Mockery::mock(TmdbService::class);
        $this->app->instance(TmdbService::class, $this->tmdbServiceMock);
    }

    /** @test */
    public function it_shows_dashboard_when_user_is_authenticated()
    {
        $user = User::factory()->create();

        $this->tmdbServiceMock
            ->shouldReceive('allMovies')
            ->with(1)
            ->andReturn(['results' => [['id' => 1, 'title' => 'Movie Test']]]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Dashboard')
                    ->has('movies')
                    ->where('user', $user->name)
            );
    }

    /** @test */
    public function it_shows_welcome_when_user_is_guest()
    {
        $this->tmdbServiceMock
            ->shouldReceive('allMovies')
            ->with(1)
            ->andReturn(['results' => [['id' => 1, 'title' => 'Movie Test']]]);

        $this->get('/')
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Welcome')
                    ->has('movies')
            );
    }

    /** @test */
    public function it_loads_more_movies()
    {
        $this->tmdbServiceMock
            ->shouldReceive('allMovies')
            ->with(2)
            ->andReturn(['results' => [['id' => 2, 'title' => 'Another Movie']]]);

        $response = $this->get("/load-movies/2");
        $response->assertJson(['results' => [['id' => 2, 'title' => 'Another Movie']]]);
    }

    /** @test */
    public function it_shows_favorites_for_authenticated_user()
    {
        $user = User::factory()->create(['account_id' => 22235988]);

        $this->tmdbServiceMock
            ->shouldReceive('favorites')
            ->with(22235988, 1)
            ->andReturn(['results' => [[
                'movie_id' => 5,
                'title' => 'Fav Movie',
                'director' => 'Unknown',
                'release_year' => '2022-01-01',
                'genre' => 'Action',
                'rating' => 8.5,
                'favorite' => 0,
            ]]]);

        $this->tmdbServiceMock
            ->shouldReceive('genres')
            ->andReturn(['genres' => [['id' => 28, 'name' => 'Action']]]);

        $this->actingAs($user)
            ->get('/favorites')
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Favorites')
                    ->has('favorites')
                    ->has('genres')
                    ->where('user', $user->name)
            );
    }

    /** @test */
    public function it_adds_to_favorites()
    {
        $user = User::factory()->create(['account_id' => 22235988]);

        $this->tmdbServiceMock
            ->shouldReceive('addToFavorites')
            ->with(22235988, 10)
            ->andReturn(response()->json(['message' => 'Filme adicionado aos favoritos.']));

        $response = $this->actingAs($user)->post('/favorites/10');
        $response->assertJson(['message' => 'Filme adicionado aos favoritos.']);
    }

    /** @test */
    public function it_removes_from_favorites()
    {
        $user = User::factory()->create(['account_id' => 22235988]);

        $movie = Movie::factory()->create();

        $this->tmdbServiceMock
            ->shouldReceive('removeFromFavorite')
            ->with(22235988, 10)
            ->andReturn(response()->json(['message' => 'Filme removido dos favoritos.']));

        $this->assertDatabaseHas('movies', $movie->toArray());

        $response = $this->actingAs($user)->post('/remove-favorites/10');
        $response->assertJson(['message' => 'Filme removido dos favoritos.']);
    }
}
