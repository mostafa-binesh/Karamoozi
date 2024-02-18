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
        $this->call([
            TermSeeder::class,
            RoleAndPermissionSeeder::class,
            OptionsSeeder::class,
            UniversityFacultySeeder::class,
            UsersSeeder::class,
            CompanySeeder::class,
            AdminSeeder::class,
            MasterTermSeeder::class,
        ]);
    }
}
