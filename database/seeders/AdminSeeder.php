<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@thaiyur.com'],
            [
                'name' => 'ThaiYur Admin',
                'password' => 'password', // User model hashed cast will hash once
            ]
        );
    }
}
