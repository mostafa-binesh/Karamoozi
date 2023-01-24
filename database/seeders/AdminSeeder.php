<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\IndustrySupervisor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;

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
            'first_name' => 'حسن',
            'last_name' => 'جعفری',
            'username' => '5003',
            'national_code' => '5003',
            'email' => 'jafari@admin.com',
            'phone_number' => '09390565606',
            'password' => '$2y$10$gN91w/NwB5ivA/jLmJMZceDhwe0aQSNuLr5uLcnBKS22ZzgidiX7e',
        ])->assignRole('industry_supervisor');
        $industrySupervisor = IndustrySupervisor::create([
            'verified' => true,
            'user_id' => $industrySupervisorUser->id,
        ]);
        $firstStudent = User::create([
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
            'supervisor_id' => $industrySupervisor->id,
            'internship_type' => 1,
        ]);
        $secondStudent = User::create([
            'first_name' => 'حسن',
            'last_name' => 'جلیل آذری',
            'username' => '3981231021',
            'national_code' => '5300053261',
            'email' => 'hasan@admin.com',
            'phone_number' => '09390565601',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('student');
        Student::create([
            'user_id' => $secondStudent->id,
            'student_number' => $secondStudent->username,
            'supervisor_id' => $industrySupervisor->id,
            'internship_type' => 1,
        ]);
        $thirdStudent = User::create([
            'first_name' => 'احسان',
            'last_name' => 'سورگی',
            'username' => '3981231022',
            'national_code' => '5300053262',
            'email' => 'ehsan@admin.com',
            'phone_number' => '09390565602',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('student');
        Student::create([
            'user_id' => $thirdStudent->id,
            'student_number' => $thirdStudent->username,
            'supervisor_id' => $industrySupervisor->id,
            'internship_type' => 1,
        ]);
        $fourthStudent = User::create([
            'first_name' => 'محسن',
            'last_name' => 'نوری',
            'username' => '3981231111',
            'national_code' => '1234',
            'email' => 'mohsen@admin.com',
            'phone_number' => '09852145',
            'password' => '$2y$10$GZG/xGdZ1QuEeiWgpbI8BecvE5VQeo0GJV5ksiv3DctY8D06XhdK.', // 1234
        ])->assignRole('student');
        Student::create([
            'user_id' => $fourthStudent->id,
            'student_number' => $fourthStudent->username,
            'supervisor_id' => $fourthStudent->id,
            'internship_type' => 1,
        ]);
        $firstEmployee = User::create([
            'first_name' => 'زهرا',
            'last_name' => 'شیرمحمدی',
            'username' => '5000',
            'national_code' => '5300053263',
            'email' => 'shir@admin.com',
            'phone_number' => '09390565603',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        Employee::create([
            'user_id' => $firstEmployee->id,
            'faculty_id' => 1,
        ]);
        $secondEmployee = User::create([
            'first_name' => 'حامد',
            'last_name' => 'درستی',
            'username' => '5001',
            'national_code' => '5300053265',
            'email' => 'dorosti@admin.com',
            'phone_number' => '09390565605',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        Employee::create([
            'user_id' => $secondEmployee->id,
            'faculty_id' => 1,
        ]);
    }
}
