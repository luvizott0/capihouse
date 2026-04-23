<?php

namespace App\Livewire\Auth;

use Auth;
use Illuminate\View\View;
use Livewire\Component;
use Session;

class Logout extends Component
{
    public bool $asButton = true;

    public string $class = '';

    public bool $withIcon = false;

    public function logout(): void
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirect('/', true);
    }

    public function render(): string
    {
        return <<<'blade'
            <button wire:click="logout" class="btn-primary flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                </svg>
                {{ __('Logout') }}
            </button>
        blade;
    }
}
