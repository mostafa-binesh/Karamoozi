<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\committee;
use Illuminate\Database\Seeder;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(comm::class);
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);
        $this->call([
            AdminSeeder::class,
        ]);
        $this->call([
            UniversityFacultySeeder::class,
        ]);
        // $this->call([
        //     UsersSeeder::class,
        // ]);
    }
}
