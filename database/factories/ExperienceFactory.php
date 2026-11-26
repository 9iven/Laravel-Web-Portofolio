<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => fake()->jobTitle(),
            'organization' => fake()->company(),
            'type' => fake()->randomElement(['Magang', 'Organisasi', 'Asisten Praktikum', 'Freelance']),
            'location' => fake()->city(),
            'start_date' => fake()->dateTimeBetween('-2 years', '-6 months')->format('Y-m-d'),
            'end_date' => null,
            'is_current' => true,
            'description' => fake()->sentence(12),
            'sort_order' => 0,
        ];
    }
}
