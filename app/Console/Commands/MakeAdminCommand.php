<?php

namespace App\Console\Commands;

use App\Enums\UserRoles;
use App\Enums\UserStatuses;
use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    protected $signature = 'make:admin {email : E-mail do usuário a ser promovido}';

    protected $description = 'Promove um usuário existente ao papel de administrador';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Usuário com e-mail [{$email}] não encontrado.");

            return self::FAILURE;
        }

        $user->update([
            'role' => UserRoles::Admin,
            'status' => UserStatuses::APPROVED,
        ]);

        $this->info("Usuário [{$user->name}] agora é administrador.");

        return self::SUCCESS;
    }
}
