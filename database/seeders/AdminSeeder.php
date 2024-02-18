<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Form2s;
use App\Models\IndustrySupervisor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Providers\GenerateRandomId;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $industrySupervisorUser = User::create([
            'rand_id'=>GenerateRandomId::generateRandomId(),
            'first_name' => 'حسن',
            'last_name' => 'جعفری',
            'username' => '5003',
            'national_code' => '5003',
            'email' => 'jafari@admin.com',
            'phone_number' => '09390565106',
            'password' => '$2y$10$gN91w/NwB5ivA/jLmJMZceDhwe0aQSNuLr5uLcnBKS22ZzgidiX7e',
        ])->assignRole('industry_supervisor');
        $industrySupervisor = IndustrySupervisor::create([
            'verified' => true,
            'user_id' => $industrySupervisorUser->id,
            'company_id'=> 1
        ]);
        $firstStudent = User::create([
            'rand_id'=>GenerateRandomId::generateRandomId(),
            'first_name' => 'مصطفی',
            'last_name' => 'بینش',
            'username' => '3981231020',
            'national_code' => '5300053260',
            'email' => 'mostafa@admin.com',
            'phone_number' => '09390565600',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('student');
        Student::create([
            'user_id' => $firstStudent->id,
            'student_number' => $firstStudent->username,
            'entrance_year' => Student::university_entrance_year_static($firstStudent->username),
            'supervisor_id' => $industrySupervisor->id,
            // 'internship_type' => 1,
            'term_id' => 1,
        ]);
        $secondStudent = User::create([
            'rand_id'=>GenerateRandomId::generateRandomId(),
            'first_name' => 'حسن',
            'last_name' => 'جلیل آذری',
            'username' => '3981231021',
            'national_code' => '5300053261',
            'email' => 'hasan@admin.com',
            'phone_number' => '09390565601',
            'password' => '$2y$10$qJ0ZjeCzCgUqphfLDXg7GuyRKENRvfzMAueszq37Qk5pZU8ijWWmy', // 123
        ])->assignRole('student');
        Student::create([
            'user_id' => $secondStudent->id,
            'student_number' => $secondStudent->username,
            'entrance_year' => Student::university_entrance_year_static($secondStudent->username),
            'supervisor_id' => $industrySupervisor->id,
            // 'internship_type' => 1,
            'term_id' => 1,
        ]);
        $thirdStudent = User::create([
            'rand_id'=>GenerateRandomId::generateRandomId(),
            'first_name' => 'احسان',
            'last_name' => 'سورگی',
            'username' => '3981231022',
            'national_code' => '5300053262',
            'email' => 'ehsan@admin.com',
            'phone_number' => '09390565602',
            'password' => '$2y$10$qJ0ZjeCzCgUqphfLDXg7GuyRKENRvfzMAueszq37Qk5pZU8ijWWmy', // 123
        ])->assignRole('student');
        Student::create([
            'user_id' => $thirdStudent->id,
            'student_number' => $thirdStudent->username,
            'entrance_year' => Student::university_entrance_year_static($thirdStudent->username),
            'supervisor_id' => $industrySupervisor->id,
            // 'internship_type' => 1,
            'term_id' => 1,
        ]);
        // Mohsen Nouri
        $fourthStudent = User::create([
            'rand_id'=>GenerateRandomId::generateRandomId(),
            'first_name' => 'محسن',
            'last_name' => 'نوری',
            'username' => '3991231111',
            'national_code' => '1234',
            'email' => 'mohsen@admin.com',
            'phone_number' => '09852145',
            'password' => '$2y$10$qJ0ZjeCzCgUqphfLDXg7GuyRKENRvfzMAueszq37Qk5pZU8ijWWmy', // 123
        ])->assignRole('student');
        // ? should i remove supervisorID for $fs
        $fs = Student::create([
            'user_id' => $fourthStudent->id,
            'student_number' => $fourthStudent->username,
            'entrance_year' => Student::university_entrance_year_static($fourthStudent->username),
            'supervisor_id' => $industrySupervisorUser->id,
            // 'internship_type' => 1,
            'term_id' => 1,
        ]);
        $firstAdmin = User::create([
            'rand_id'=>GenerateRandomId::generateRandomId(),
            'first_name' => 'کیارش',
            'last_name' => 'کسائیان',
            'username' => '8000',
            'national_code' => '8000',
            'email' => 'kkasaei@admin.com',
            'phone_number' => '092995418000',
            'password' => '$2y$10$VTcsdGgndglxgSoMh1Qxc.Werk8WvrK0osrA.MdFkLoFlKMo6dYqK', // 8000
        ])->assignRole('admin');
        // Employee::create([
        //     'user_id' => $firstAdmin->id,
        //     'faculty_id' => 4,
        // ]);
    }
}
