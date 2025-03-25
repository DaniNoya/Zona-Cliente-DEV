<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Create admin user
        User::create([
            'name' => 'Administrator',
            'user' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
            'role_id' => 1, // Administrador role
            'status_id' => 1, // Active status
        ]);

        // Create 30 random users
        for ($i = 0; $i < 30; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            
            User::create([
                'name' => $firstName . ' ' . $lastName,
                'user' => strtolower($firstName . $lastName),
                'email' => strtolower($firstName . '.' . $lastName . '@example.com'),
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role_id' => 2, // Usuario role
                'status_id' => 1, // Active status
            ]);
        }
    }
}