<?php

namespace App\Providers;


class GenerateRandomId
{
    static function generateRandomId()
    {
        $timestamp = time();
        $random_number = mt_rand(1000000000, 9999999999);
        $random_id = $timestamp . $random_number;
        $uuid = md5($random_id);
        return $uuid;
    }
}
