<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specific user
        User::factory()->create([
            'name' => 'Mahbub Hussain',
            'email' => 'mahbubhussaincse@gmail.com',
        ]);
    }
}
