<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\committee>
 */
class commfactoryFactory extends Factory
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
            'committee_name' => $this->faker->jobTitle(),
            'caption' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
