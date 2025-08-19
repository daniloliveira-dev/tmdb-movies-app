<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => $this->faker->unique()->numberBetween(1, 100000),
            'title' => $this->faker->sentence(),
            'director' => $this->faker->name(),
            'release_year' => $this->faker->date(),
            'genre' => $this->faker->word(),
            'rating' => $this->faker->randomFloat(1, 1, 10),
            'favorite' => $this->faker->boolean(),
        ];
    }
}
