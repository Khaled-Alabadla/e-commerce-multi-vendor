<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);
        return [
            'name' => $name,
            'slug'=> Str::slug($name),
            'cover_image' => fake()->imageUrl(600, 600),
            'logo_image' => fake()->imageUrl(600, 600),
            'description' => fake()->sentences(5, true),
        ];
    }
}
