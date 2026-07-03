<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
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
        User::create([
            'name' => 'Capivara Rogéria',
            'username' => 'capivara.rogeria',
            'password' => 'password',
            'status' => UserStatuses::APPROVED,
            'birth' => '2023-12-10',
            'role' => UserRoles::Admin,
        ]);

        if (! app()->isProduction()) {
            User::factory(10)->create();
        }
    }
}
