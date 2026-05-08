<?php

namespace App\Livewire\App\Profile;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Card extends Component
{
    public User $user;

    public bool $isOwner = false;

    public string $settingsName = '';

    public string $settingsUsername = '';

    public string $settingsCurrentPassword = '';

    public string $settingsPassword = '';

    public string $settingsPasswordConfirmation = '';

    public function mount(User $user, bool $isOwner = false): void
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
        $this->settingsName = $user->name;
        $this->settingsUsername = $user->username;
    }

    public function saveSettings(): void
    {
        if (! $this->isOwner || auth()->id() !== $this->user->id) {
            return;
        }

        $rules = [
            'settingsName' => ['required', 'string', 'max:255'],
            'settingsUsername' => ['required', 'string', 'max:255', 'unique:users,username,'.$this->user->id],
        ];

        $changingPassword = filled($this->settingsPassword) || filled($this->settingsCurrentPassword);

        if ($changingPassword) {
            $rules['settingsCurrentPassword'] = ['required', 'string', 'current_password'];
            $rules['settingsPassword'] = ['required', 'string', Password::default(), 'same:settingsPasswordConfirmation'];
            $rules['settingsPasswordConfirmation'] = ['required', 'string'];
        }

        $validated = $this->validate($rules, [], [
            'settingsName' => 'nome',
            'settingsUsername' => 'nome de usuário',
            'settingsCurrentPassword' => 'senha atual',
            'settingsPassword' => 'nova senha',
            'settingsPasswordConfirmation' => 'confirmação de senha',
        ]);

        $data = [
            'name' => $validated['settingsName'],
            'username' => $validated['settingsUsername'],
        ];

        if ($changingPassword) {
            $data['password'] = Hash::make($validated['settingsPassword']);
        }

        $this->user->forceFill($data)->save();
        $this->user->refresh();

        $this->settingsCurrentPassword = '';
        $this->settingsPassword = '';
        $this->settingsPasswordConfirmation = '';

        $this->dispatch('profile::close-settings-modal');
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
