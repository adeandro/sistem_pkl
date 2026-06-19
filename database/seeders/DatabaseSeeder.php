<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Placement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin Ariawan',
            'email' => 'ariawan.ade@gmail.com',
            'password' => Hash::make('s4tri4ni'),
        ]);
    }
}
