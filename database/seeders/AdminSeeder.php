<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Form2s;
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
            'entrance_year' => Student::university_entrance_year_static($firstStudent->username),
            'supervisor_id' => $industrySupervisor->id,
            'internship_type' => 1,
            'term_id' => 1,
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
            'entrance_year' => Student::university_entrance_year_static($secondStudent->username),
            'supervisor_id' => $industrySupervisor->id,
            'internship_type' => 1,
            'term_id' => 1,
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
            'entrance_year' => Student::university_entrance_year_static($thirdStudent->username),
            'supervisor_id' => $industrySupervisor->id,
            'internship_type' => 1,
            'term_id' => 1,
        ]);
        // Mohsen Nouri
        $fourthStudent = User::create([
            'first_name' => 'محسن',
            'last_name' => 'نوری',
            'username' => '3991231111',
            'national_code' => '1234',
            'email' => 'mohsen@admin.com',
            'phone_number' => '09852145',
            'password' => '$2y$10$GZG/xGdZ1QuEeiWgpbI8BecvE5VQeo0GJV5ksiv3DctY8D06XhdK.', // 1234
        ])->assignRole('student');
        // ? should i remove supervisorID for $fs
        $fs = Student::create([
            'user_id' => $fourthStudent->id,
            'student_number' => $fourthStudent->username,
            'entrance_year' => Student::university_entrance_year_static($fourthStudent->username),
            'supervisor_id' => $industrySupervisorUser->id,
            'internship_type' => 1,
            'term_id' => 1,
        ]);
        // $form2 = Form2s::create([
        //     'industry_supervisor_id' => $industrySupervisor->id,
        //     'student_id' => $fs->id,
        //     // ! fix later, dry
        //     'schedule_table' =>  [
        //         "04:00,04:00,04:00,04:00",
        //         "00:00,00:00,00:00,00:00",
        //         "04:00,04:00,04:00,04:00",
        //         "00:00,00:00,00:00,00:00",
        //         "04:00,04:00,04:00,04:00",
        //         "04:00,04:00,04:00,04:00"
        //     ],
        //     'introduction_letter_number' => '5000',
        //     'introduction_letter_date' => '2020/5/12',
        //     'internship_department' => 'sadas',
        //     'supervisor_position' => 'sadasd',
        //     'internship_start_date' => '2020/5/12',
        //     'internship_website' => 'http://google.com',
        //     'description' => '5000',
        // ]);
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
        $thirdEmployee = User::create([
            'first_name' => 'حسن علی',
            'last_name' => 'باقری',
            'username' => '4001',
            'national_code' => '53230053265',
            'email' => 'bagheri@admin.com',
            'phone_number' => '09299565605',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('employee');
        Employee::create([
            'user_id' => $thirdEmployee->id,
            'faculty_id' => 1,
        ]);
        $fourthEmployee = User::create([
            'first_name' => 'علی',
            'last_name' => 'علمداری',
            'username' => '4002',
            'national_code' => '53230053212',
            'email' => 'alamdari@admin.com',
            'phone_number' => '09291555605',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        Employee::create([
            'user_id' => $fourthEmployee->id,
            'faculty_id' => 2,
        ]);
        $fifthEmployee = User::create([
            'first_name' => 'مهدی',
            'last_name' => 'شکری',
            'username' => '4003',
            'national_code' => '5323005313',
            'email' => 'shekari@admin.com',
            'phone_number' => '09299561205',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        Employee::create([
            'user_id' => $fifthEmployee->id,
            'faculty_id' => 2,
        ]);
        $sixEmployee = User::create([
            'first_name' => 'علیرضا',
            'last_name' => 'منصوری',
            'username' => '4005',
            'national_code' => '53230054005',
            'email' => 'mansouri@admin.com',
            'phone_number' => '092995414005',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        Employee::create([
            'user_id' => $sixEmployee->id,
            'faculty_id' => 3,
        ]);
        $seventhEmployee = User::create([
            'first_name' => 'نجمه',
            'last_name' => 'صباغیان',
            'username' => '4006',
            'national_code' => '53230054006',
            'email' => 'sabbaghian@admin.com',
            'phone_number' => '092995414006',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        Employee::create([
            'user_id' => $seventhEmployee->id,
            'faculty_id' => 4,
        ]);
        $eighthEmployee = User::create([
            'first_name' => 'هستی',
            'last_name' => 'آزادمنش',
            'username' => '4007',
            'national_code' => '53230054007',
            'email' => 'azadmanesh@admin.com',
            'phone_number' => '092995414007',
            'password' => '$2y$10$QNMSMZ1NLq7HwrRXjLCjC.nZbWbOseajSIS4k6IY.aimBXS/wocPq',
        ])->assignRole('master');
        Employee::create([
            'user_id' => $eighthEmployee->id,
            'faculty_id' => 4,
        ]);
        $firstAdmin = User::create([
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
