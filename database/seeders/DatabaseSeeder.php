<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Movie::factory()->create([
            'movie_id' => 10,
            'title' => 'Fav Movie',
            'director' => 'Unknown',
            'release_year' => '2022-01-01',
            'genre' => 'Action',
            'rating' => 8.5,
            'favorite' => 1,
        ]);
    }
}
