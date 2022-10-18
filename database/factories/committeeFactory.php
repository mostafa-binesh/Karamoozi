<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
// use Ybazli\Faker\Facades\Faker;
use Ybazli\Faker\Facades\Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\comm>
 */
class committeeFactory extends Factory
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
            // 'committee_name' => $this->faker->jobTitle(),
            // 'caption' => $this->faker->sentence(),
            // 'image' => $this->faker->imageUrl(),
            'committee_name' => Faker::word(),
            'caption' =>  Faker::sentence(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
