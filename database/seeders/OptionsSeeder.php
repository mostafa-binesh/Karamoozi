<?php

namespace Database\Seeders;

use App\Models\Options;
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
        // Options::factory()->times(5)->create();
        Options::insert(
            [
                [
                    'type' => 'internship_evaluation',
                    'name' => 'استفاده از توان علمی در کار های علمی',
                ],
                [
                    'type' => 'internship_evaluation',
                    'name' => 'استعداد و علاقه به یادگیری',
                ],
                [
                    'type' => 'internship_evaluation',
                    'name' => 'سرعت و دقت در انجام کار های محوله',
                ],
                [
                    'type' => 'internship_evaluation',
                    'name' => 'کارآفرین بودن، خلاقیت و نوآوری',
                ],
                [
                    'type' => 'internship_evaluation',
                    'name' => 'پشتکار و پیگیری وظایف و امور محوله',
                ],
                [
                    'type' => 'internship_evaluation',
                    'name' => 'حضور به موقع و نظم',
                ],
                [
                    'type' => 'internship_evaluation',
                    'name' => 'هماهنگی و همکاری با تیم',
                ],
                [
                    'type' => 'internship_evaluation',
                    'name' => 'رعایت شئونات اسلامی',
                ]
            ],
        );
    }
}
