<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::insert(
            [
                [
                    'type' => 'industry_supervisor_evaluation',
                    'name' => 'استفاده از توان علمی در کار های علمی',
                ],
                [
                    'type' => 'industry_supervisor_evaluation',
                    'name' => 'استعداد و علاقه به یادگیری',
                ],
                [
                    'type' => 'industry_supervisor_evaluation',
                    'name' => 'سرعت و دقت در انجام کار های محوله',
                ],
                [
                    'type' => 'industry_supervisor_evaluation',
                    'name' => 'کارآفرین بودن، خلاقیت و نوآوری',
                ],
                [
                    'type' => 'industry_supervisor_evaluation',
                    'name' => 'پشتکار و پیگیری وظایف و امور محوله',
                ],
                [
                    'type' => 'industry_supervisor_evaluation',
                    'name' => 'حضور به موقع و نظم',
                ],
                [
                    'type' => 'industry_supervisor_evaluation',
                    'name' => 'هماهنگی و همکاری با تیم',
                ],
                [
                    'type' => 'industry_supervisor_evaluation',
                    'name' => 'رعایت شئونات اسلامی',
                ],
                [
                    'type' => 'student_company_evaluation',
                    'name' => 'استعداد و علاقه به یادگیری',
                ],
                [
                    'type' => 'student_company_evaluation',
                    'name' => 'هماهنگی و همکاری با تیم',
                ],
                [
                    'type' => 'student_company_evaluation',
                    'name' => 'پشتکار و پیگیری وظایف و امور محوله',
                ],
                [
                    'type' => 'student_company_evaluation',
                    'name' => 'کارآفرین بودن، خلاقیت و نوآوری',
                ],
                [
                    'type' => 'student_company_evaluation',
                    'name' => 'سرعت و دقت در انجام کار های محوله',
                ],
                [
                    'type' => 'student_company_evaluation',
                    'name' => 'حضور به موقع و نظم',
                ],
                [
                    'type' => 'student_company_evaluation',
                    'name' => 'استفاده از توان علمی در کار های علمی',
                ],
                [
                    'type' => 'student_company_evaluation',
                    'name' => 'رعایت شئونات اسلامی',
                ]
            ],
        );
    }
}
