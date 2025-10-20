<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandId = DB::table('brands')->insertGetId([
            'slug' => 'awesome-brand',
            'logo_url' => 'https://example.com/logos/awesome-brand.png',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create translation for the brand
        DB::table('brand_translations')->insert([
            'brand_id' => $brandId,
            'locale' => 'en',
            'name' => 'Awesome Brand',
            'description' => 'A high-quality brand known for its awesome products.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
