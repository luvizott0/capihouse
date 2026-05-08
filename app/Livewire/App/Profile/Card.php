<?php

namespace App\Livewire\App\Profile;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Card extends Component
{
    public User $user;

    public bool $isOwner = false;

    public function mount(User $user, bool $isOwner = false): void
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
    }

    public function isUserOnline(): bool
    {
        return DB::table('sessions')
            ->where('user_id', $this->user->id)
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->exists();
    }

    #[On('profile::image-updated')]
    public function refreshUser(): void
    {
        $this->user->refresh();
    }

    public function render(): View
    {
        return view('livewire.app.profile.card', [
            'isOnline' => $this->isUserOnline(),
        ]);
    }
}
