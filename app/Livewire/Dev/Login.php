<?php

namespace App\Livewire\Dev;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Login extends Component
{
    public ?int $selectedUser = null;

    public function mount(): void
    {
        $this->selectedUser = auth()->user()?->id;
    }

    #[Computed]
    public function users(): Collection
    {
        return User::query()->orderBy('id')->get();
    }

    public function login(): void
    {
        abort_if(app()->isProduction(), Response::HTTP_FORBIDDEN);

        if ($this->selectedUser === 0) {
            return;
        }

        $this->validate(['selectedUser' => 'required']);

        session()->put('fake_login', true);

        auth()->loginUsingId($this->selectedUser);

        $this->redirect(route('feed'));
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="gap-2 flex">
                <x-forms.select wire:model="selectedUser">
                    <option value="0">Select User</option>
                    @foreach($this->users() as $user)
                        <option value="{{ $user->id }}" >
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-forms.select>

                <button wire:click="login" class="join-item btn-primary">Login</button>
            </div>
        blade;
    }
}
