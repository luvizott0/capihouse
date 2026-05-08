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
            'birth' => '2001-10-15',
        ]);

        User::factory()->admin()->create([
            'name' => 'Capivara Rogéria',
            'username' => 'capivara.rogeria',
            'password' => 'password',
            'status' => UserStatuses::APPROVED,
            'birth' => '2023-12-10',
        ]);

        if (! app()->isProduction()) {
            User::factory(10)->create();
        }
    }
}
