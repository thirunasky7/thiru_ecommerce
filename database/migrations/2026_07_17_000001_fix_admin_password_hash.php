<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // Bypass Eloquent "hashed" cast to avoid double-hashing
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@thaiyur.com'],
            [
                'name' => 'ThaiYur Admin',
                'password' => Hash::make('password'),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        //
    }
};
