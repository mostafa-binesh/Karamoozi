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
        Company::create([
            'company_name' => 'رایادرس',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
            'company_address' => "تهران، تجریش، کوچه جاویدان، پلاک 233",
            'company_postal_code' => "3561963485",
            'company_phone' => "02835437323",
            'company_number'=>'1234567890',
            'company_registry_code'=>'2345678902',
            'image'=>'image.png'
        ]);
        Company::create([
            'company_name' => 'ایز ایران',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
            'company_address' => "تهران، تجریش، کوچه جاویدان، پلاک 233",
            'company_postal_code' => "3561963485",
            'company_phone' => "02835437323",
            'company_number'=>'1234567890',
            'company_registry_code'=>'2345678902',
            'image'=>'image.png'
        ]);
        Company::create([
            'company_name' => 'بیمه ایران',
            'company_boss_id' => 1,
            'verified' => true,
            'company_type' => 1,
            'company_address' => "تهران، تجریش، کوچه جاویدان، پلاک 233",
            'company_postal_code' => "3561963485",
            'company_phone' => "02835437323",
            'company_number'=>'1234567890',
            'company_registry_code'=>'2345678902',
            'image'=>'image.png'
        ]);
        // Company::create([
        //     'company_name' => 'رایان اندیش',
        //     'company_boss_id' => 1,
        //     'verified' => true,
        //     'company_type' => 1,
        // ]);
        // Company::create([
        //     'company_name' => 'خندق کاران',
        //     'company_boss_id' => 1,
        //     'verified' => true,
        //     'company_type' => 1,
        // ]);
    }
}
