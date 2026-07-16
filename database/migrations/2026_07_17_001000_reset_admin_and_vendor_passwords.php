<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $password = Hash::make('password');

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@thaiyur.com'],
            [
                'name' => 'ThaiYur Admin',
                'password' => $password,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $vendors = [
            ['email' => 'food@thaiyur.com', 'name' => 'Somsak Kitchen', 'business_name' => 'Somsak Thai Kitchen', 'phone' => '9876500001'],
            ['email' => 'grocery@thaiyur.com', 'name' => 'FreshBasket Mart', 'business_name' => 'FreshBasket Grocery', 'phone' => '9876500002'],
            ['email' => 'shop@thaiyur.com', 'name' => 'Yur Lifestyle Store', 'business_name' => 'Yur Lifestyle', 'phone' => '9876500003'],
        ];

        foreach ($vendors as $vendor) {
            DB::table('vendors')->updateOrInsert(
                ['email' => $vendor['email']],
                [
                    'name' => $vendor['name'],
                    'business_name' => $vendor['business_name'],
                    'phone' => $vendor['phone'],
                    'password' => $password,
                    'status' => 'active',
                    'city' => 'Chennai',
                    'is_featured' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        //
    }
};
