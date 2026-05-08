<?php

namespace App\Livewire\App\Profile;

use App\Models\Interest;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class PersonalInfo extends Component
{
    public User $user;

    public bool $isOwner = false;

    public ?string $interest = null;

    public ?string $bio = null;

    public ?string $birth = null;

    public bool $isEditingBio = false;

    public bool $isEditingBirth = false;

    public function mount(User $user, bool $isOwner = false): void
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
        $this->bio = $this->user->bio;
        $this->birth = $this->user->birth?->format('Y-m-d');
        $this->user->load('interests');
    }

    public function startEditingBio(): void
    {
        if (! $this->canEditProfile()) {
            return;
        }

        $this->bio = $this->user->bio;
        $this->isEditingBio = true;
    }

    public function cancelEditingBio(): void
    {
        $this->bio = $this->user->bio;
        $this->isEditingBio = false;
        $this->resetValidation('bio');
    }

    public function saveBio(): void
    {
        if (! $this->canEditProfile()) {
            return;
        }

        $validated = $this->validate([
            'bio' => ['nullable', 'string', 'max:255'],
        ]);

        $this->user->forceFill([
            'bio' => $validated['bio'],
        ])->save();

        $this->user->refresh();
        $this->bio = $this->user->bio;
        $this->isEditingBio = false;
    }

    public function startEditingBirth(): void
    {
        if (! $this->canEditProfile()) {
            return;
        }

        $this->birth = $this->user->birth?->format('Y-m-d');
        $this->isEditingBirth = true;
    }

    public function cancelEditingBirth(): void
    {
        $this->birth = $this->user->birth?->format('Y-m-d');
        $this->isEditingBirth = false;
        $this->resetValidation('birth');
    }

    public function saveBirth(): void
    {
        if (! $this->canEditProfile()) {
            return;
        }

        $validated = $this->validate([
            'birth' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $this->user->forceFill([
            'birth' => $validated['birth'],
        ])->save();

        $this->user->refresh();
        $this->birth = $this->user->birth?->format('Y-m-d');
        $this->isEditingBirth = false;
    }

    public function addInterest(): void
    {
        if (! $this->canEditProfile()) {
            return;
        }

        $validated = $this->validate([
            'interest' => ['required', 'string', 'max:50'],
        ]);

        $name = trim($validated['interest']);

        if ($name === '') {
            return;
        }

        $interest = Interest::query()->firstWhere('name', $name);

        if (! $interest) {
            $interest = new Interest;
            $interest->name = $name;
            $interest->save();
        }

        $this->user->interests()->syncWithoutDetaching([$interest->id]);

        $this->interest = null;
        $this->user->load('interests');
    }

    public function removeInterest(int $interestId): void
    {
        if (! $this->canEditProfile()) {
            return;
        }

        $this->user->interests()->detach($interestId);
        $this->user->load('interests');
    }

    private function canEditProfile(): bool
    {
        return $this->isOwner && auth()->id() === $this->user->id;
    }

    public function render(): View
    {
        return view('livewire.app.profile.personal-info');
    }
}
