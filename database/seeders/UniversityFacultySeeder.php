<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\University_faculty;

class UniversityFacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        University_faculty::insert([
            [
                'faculty_name' => 'دانشکده کامپیوتر'
            ],
            [
                'faculty_name' => 'دانشکده عمران'
            ],
            [
                'faculty_name' => 'دانشکده مکانیک'
            ],
            [
                'faculty_name' => 'دانشکده مواد متالوژی'
            ],
        ]);
    }
}
