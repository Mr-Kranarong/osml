<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
                'option' => 'paypal_payment',
                'value' => 'AfXppLzJU8GoWT4VegoOpVKU0PFkzIj-EL8OhKLn-VKhADW-xbImRCSLZ5BSTQy7iYaXSoLuNV312qW7'
            ],
            [
                'option' => 'paypal_secret',
                'value' => 'EAxKkhdUMDX7tjF0q2LMiVQnRVbFRpTuyLrT3I9vBpAteoEw1ciqG9TjuibKdEWiJJCZYyH3R_C04-B2'
            ],
            [
                'option' => 'apriori_support',
                'value' => '0.1'
            ],
            [
                'option' => 'apriori_confidence',
                'value' => '0.1'
            ],
            [
                'option' => 'store_name',
                'value' => 'DEMOSTORE'
            ],
            [
                'option' => 'store_address',
                'value' => '123/45 Something road, Basmic ASD, sdadp aldw adwad,a wddwalk awd, 1231415'
            ],
            [
                'option' => 'store_telephone',
                'value' => '05618508'
            ],
            [
                'option' => 'store_email',
                'value' => 'dogshit@mail.com'
            ]
        );
    }
}
