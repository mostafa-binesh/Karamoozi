<?php

namespace Database\Seeders;

use App\Models\committee;
use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker;

class comm extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // comm::factory(5)->create();
        // $factory=Factory::create();
        // for($i=0;$i<15;$i++){
        //     committee::create(
        //         'committee_name' => $factory[$i],
        //         'caption'=>
        //     );
        // }
        committee::factory()->count(10)->create();
        // committee::create([
        //     'committee_name' => 
        //     'caption' => 'admin@gmail.com',
        //     'image' => 'asdas',
        // ]);
    }
}
