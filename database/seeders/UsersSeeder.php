<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Mostafa',
            'last_name' => 'Binesh',
            'username' => '3981231020',
            'national_code' => '5300053260',
            'email' => 'mostafa@admin.com',
            'phone_number' => '09390565606',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('student');
        User::create([
            'first_name' => 'زهرا',
            'last_name' => 'شیرمحمدی',
            'username' => '5000',
            'national_code' => '5300053260',
            'email' => 'shir@admin.com',
            'phone_number' => '09390565606',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        User::create([
            'first_name' => 'حامد',
            'last_name' => 'درستی',
            'username' => '5001',
            'national_code' => '5300053260',
            'email' => 'dorosti@admin.com',
            'phone_number' => '09390565606',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('employee');
    }
}
