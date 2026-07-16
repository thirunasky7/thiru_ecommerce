<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('site_settings')->updateOrInsert(
            ['id' => 1],
            [
                'site_name' => 'ThaiYur',
                'tagline' => 'Food · Grocery · Marketplace',
                'meta_title' => 'ThaiYur — Multivendor Marketplace',
                'meta_description' => 'Order food, groceries and products from trusted local vendors.',
                'meta_keywords' => 'thaiyur, food, grocery, marketplace, multivendor',
                'logo' => null,
                'favicon' => 'favicon.ico',
                'contact_email' => 'hello@thaiyur.com',
                'contact_phone' => '+91 98765 43210',
                'address' => 'ThaiYur Hub, Chennai, India',
                'footer_text' => '© ' . date('Y') . ' ThaiYur. All rights reserved.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
