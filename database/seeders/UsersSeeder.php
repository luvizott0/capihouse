<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserStatuses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => 'luvizotto',
            'password' => 'password',
            'status' => \App\Enums\UserStatuses::APPROVED,
        ]);

        User::factory()->create([
            'username' => 'capivara.rogeria',
            'password' => 'password',
            'status' => \App\Enums\UserStatuses::APPROVED,
        ]);
    }
}
