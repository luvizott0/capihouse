<?php

namespace Database\Seeders;

use App\Enums\UserStatuses;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Calebe Luvizotto',
            'username' => 'luvizotto',
            'password' => 'password',
            'status' => UserStatuses::APPROVED,
        ]);

        User::factory()->create([
            'name' => 'Capivara Rogéria',
            'username' => 'capivara.rogeria',
            'password' => 'password',
            'status' => UserStatuses::APPROVED,
        ]);

        User::factory(10)->create();
    }
}
