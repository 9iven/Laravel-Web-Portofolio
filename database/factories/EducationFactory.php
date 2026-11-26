<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution' => fake()->company().' University',
            'degree' => 'S1 / D-IV Teknik Informatika',
            'field_of_study' => 'Teknologi Rekayasa Perangkat Lunak',
            'start_year' => '2025',
            'end_year' => null,
            'is_current' => true,
            'description' => fake()->sentence(10),
            'sort_order' => 0,
        ];
    }
}
