<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Providers\GenerateRandomId;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ! this model has not been submitted in DatabaseSeeder!
        // User::create([
        //     'first_name' => 'Mostafa',
        //     'last_name' => 'Binesh',
        //     'username' => '3981231020',
        //     'national_code' => '5300053265',
        //     'email' => 'mostafa@admin.com',
        //     'phone_number' => '09390565606',
        //     'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        // ])->assignRole('student');
        // $firstEmployee = User::create([
        //     'rand_id'=>GenerateRandomId::generateRandomId(),
        //     'first_name' => 'زهرا',
        //     'last_name' => 'شیرمحمدی',
        //     'username' => '5000',
        //     'national_code' => '5300053263',
        //     'email' => 'shir@admin.com',
        //     'phone_number' => '09390565603',
        //     'password' => '$2y$10$QeXM/HHqvc.O3l/Q0nnGwujblP8hAXj1aZ6e7LSAlSXHoax5eFLYS', // = 12345

        // ])->assignRole('master');
        // Employee::create([
        //     'user_id' => $firstEmployee->id,
        //     'faculty_id' => 1,
        // ]);
        $secondEmployee = User::create([
            'rand_id'=>GenerateRandomId::generateRandomId(),
            'first_name' => 'حامد',
            'last_name' => 'درستی',
            'username' => '5001',
            'national_code' => '1234567890',
            'email' => 'dorosti@admin.com',
            'phone_number' => '09390565605',
            'password' => Hash::make('1234567890'),
        ])->assignRole('master');
        Employee::create([
            'user_id' => $secondEmployee->id,
            'faculty_id' => 1,
        ]);
        // $thirdEmployee = User::create([
        //     'rand_id'=>GenerateRandomId::generateRandomId(),
        //     'first_name' => 'حسن علی',
        //     'last_name' => 'باقری',
        //     'username' => '4001',
        //     'national_code' => '53230053265',
        //     'email' => 'bagheri@admin.com',
        //     'phone_number' => '09299565605',
        //     'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        // ])->assignRole('employee');
        // Employee::create([
        //     'user_id' => $thirdEmployee->id,
        //     'faculty_id' => 1,
        // ]);
        // $fourthEmployee = User::create([
        //     'rand_id'=>GenerateRandomId::generateRandomId(),
        //     'first_name' => 'علی',
        //     'last_name' => 'علمداری',
        //     'username' => '4002',
        //     'national_code' => '53230053212',
        //     'email' => 'alamdari@admin.com',
        //     'phone_number' => '09291555605',
        //     'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        // ])->assignRole('master');
        // Employee::create([
        //     'user_id' => $fourthEmployee->id,
        //     'faculty_id' => 2,
        // ]);
        // $fifthEmployee = User::create([
        //     'rand_id'=>GenerateRandomId::generateRandomId(),
        //     'first_name' => 'مهدی',
        //     'last_name' => 'شکری',
        //     'username' => '4003',
        //     'national_code' => '5323005313',
        //     'email' => 'shekari@admin.com',
        //     'phone_number' => '09299561205',
        //     'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        // ])->assignRole('master');
        // Employee::create([
        //     'user_id' => $fifthEmployee->id,
        //     'faculty_id' => 2,
        // ]);
        // $sixEmployee = User::create([
        //     'rand_id'=>GenerateRandomId::generateRandomId(),
        //     'first_name' => 'علیرضا',
        //     'last_name' => 'منصوری',
        //     'username' => '4005',
        //     'national_code' => '5323054005',
        //     'email' => 'mansouri@admin.com',
        //     'phone_number' => '09295414005',
        //     'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        // ])->assignRole('master');
        // Employee::create([
        //     'user_id' => $sixEmployee->id,
        //     'faculty_id' => 3,
        // ]);
        // $seventhEmployee = User::create([
        //     'rand_id'=>GenerateRandomId::generateRandomId(),
        //     'first_name' => 'نجمه',
        //     'last_name' => 'صباغیان',
        //     'username' => '4006',
        //     'national_code' => '53230054006',
        //     'email' => 'sabbaghian@admin.com',
        //     'phone_number' => '092995414006',
        //     'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        // ])->assignRole('master');
        // Employee::create([
        //     'user_id' => $seventhEmployee->id,
        //     'faculty_id' => 4,
        // ]);
        // $eighthEmployee = User::create([
        //     'rand_id'=>GenerateRandomId::generateRandomId(),
        //     'first_name' => 'هستی',
        //     'last_name' => 'آزادمنش',
        //     'username' => '4007',
        //     'national_code' => '53230054007',
        //     'email' => 'azadmanesh@admin.com',
        //     'phone_number' => '092995414007',
        //     'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        // ])->assignRole('master');
        // Employee::create([
        //     'user_id' => $eighthEmployee->id,
        //     'faculty_id' => 4,
        // ]);
    }
}
