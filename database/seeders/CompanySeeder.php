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
            ['company_name' => 'شرکت اول',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
        'submitted_by_student' => false],
            ['company_name' => 'شرکت دوم',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
        'submitted_by_student' => false],
            ['company_name' => 'شرکت سوم',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
            'submitted_by_student' => false]
        ]);
    }
}
