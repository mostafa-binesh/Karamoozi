<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\student;
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
        $first_user = User::create([
            'first_name' => 'Mostafa',
            'last_name' => 'Binesh',
            'username' => '3981231020',
            'national_code' => '5300053261',
            'email' => 'mostafa@admin.com',
            'phone_number' => '09390565601',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('student');
        Student::create([
            'user_id' => $first_user->id,
        ]);
        User::create([
            'first_name' => 'زهرا',
            'last_name' => 'شیرمحمدی',
            'username' => '5000',
            'national_code' => '5300053262',
            'email' => 'shir@admin.com',
            'phone_number' => '09390565602',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        User::create([
            'first_name' => 'حامد',
            'last_name' => 'درستی',
            'username' => '5001',
            'national_code' => '5300053263',
            'email' => 'dorosti@admin.com',
            'phone_number' => '09390565603',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
    }
}
