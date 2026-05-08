<?php

namespace App\Livewire\App\Profile;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Card extends Component
{
    public User $user;

    public bool $isOwner = false;

    public ?string $bannerUrl = null;

    public ?string $avatarUrl = null;

    public function mount(User $user, bool $isOwner = false): void
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
        $this->bannerUrl = $this->user->banner_url;
        $this->avatarUrl = $this->user->avatar_url;
    }

    public function isUserOnline(): bool
    {
        return DB::table('sessions')
            ->where('user_id', $this->user->id)
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->exists();
    }

    public function saveBanner(): void
    {
        if (! $this->canEditProfile()) {
            return;
        }

        $validated = $this->validate([
            'bannerUrl' => ['nullable', 'string', 'url', 'max:2048'],
        ]);

        $this->user->forceFill([
            'banner_url' => $validated['bannerUrl'],
        ])->save();

        $this->user->refresh();
        $this->dispatch('profile::close-banner-modal');
    }

    public function saveAvatar(): void
    {
        if (! $this->canEditProfile()) {
            return;
        }

        $validated = $this->validate([
            'avatarUrl' => ['nullable', 'string', 'url', 'max:2048'],
        ]);

        $this->user->forceFill([
            'avatar_url' => $validated['avatarUrl'],
        ])->save();

        $this->user->refresh();
        $this->dispatch('profile::close-avatar-modal');
    }

    private function canEditProfile(): bool
    {
        return $this->isOwner && auth()->id() === $this->user->id;
    }

    public function render(): View
    {
        return view('livewire.app.profile.card', [
            'isOnline' => $this->isUserOnline(),
        ]);
    }
}
