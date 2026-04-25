<?php

namespace App\Livewire\App\Profile;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class PersonalInfo extends Component
{
    public User $user;

    public bool $isOwner = false;

    public function mount(User $user, bool $isOwner = false): void
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
    }

    public function render(): View
    {
        return view('livewire.app.profile.personal-info');
    }
}
