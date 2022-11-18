<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\student;
use App\Models\Car;
class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Car::create([
            'user_id' => 1,
            'verified' => true
        ]);
        Car::create([
            'user_id' => 1,
            'verified' => true
        ]);
        Car::create([
            'user_id' => 1,
            'verified' => false
        ]);
        Car::create([
            'user_id' => 1,
            'verified' => false
        ]);
    }
}
