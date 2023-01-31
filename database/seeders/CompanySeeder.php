<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::insert([
            ['company_name' => 'مه پویا',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
        // 'submitted_by_student' => false
    ],
            ['company_name' => 'رایان اندیش',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
        // 'submitted_by_student' => false
    ],
            ['company_name' => 'خندق کاران',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
            // 'submitted_by_student' => false
            ]
        ]);
    }
}
