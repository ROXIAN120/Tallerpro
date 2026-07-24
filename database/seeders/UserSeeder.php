<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Usuario Uno',
            'email' => 'user1@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        \App\Models\User::create([
            'name' => 'Usuario Dos',
            'email' => 'user2@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);
    }
}
