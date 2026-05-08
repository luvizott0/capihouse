<?php

namespace App\Livewire\Admin\Users;

use App\Enums\UserRoles;
use App\Enums\UserStatuses;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Usuários')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function approve(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => UserStatuses::APPROVED]);

        session()->flash('success', "Usuário {$user->name} aprovado.");
    }

    public function reject(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => UserStatuses::REJECTED]);

        session()->flash('success', "Usuário {$user->name} rejeitado.");
    }

    public function ban(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => UserStatuses::BANNED]);

        session()->flash('success', "Usuário {$user->name} banido.");
    }

    public function unban(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => UserStatuses::APPROVED]);

        session()->flash('success', "Usuário {$user->name} desbanido.");
    }

    public function promoteToAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['role' => UserRoles::Admin]);

        session()->flash('success', "Usuário {$user->name} promovido a admin.");
    }

    public function demoteFromAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['role' => UserRoles::User]);

        session()->flash('success', "Usuário {$user->name} rebaixado para usuário comum.");
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $users = User::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.admin.users.index', ['users' => $users]);
    }
}
