<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'student_number' => fake()->name(),
            'student_number' => fake()->unique()->name(),
            'user_id' => fake()->unique()->numberBetween(1, 5000),
            'supervisor_id' => 1,
        ];
    }
}
