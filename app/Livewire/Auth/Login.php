<?php

namespace App\Livewire\Auth;

use Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use RateLimiter;

#[Title('Login')]
class Login extends Component
{
    public ?string $email = '';

    public ?string $password = '';

    public bool $remember = false;

    public function tryLogin(): void
    {
        if ($this->ensureIsNotRateLimited()) {
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            $this->addError('credentials', __('Credenciais inválidas. Tente novamente.'));

            return;
        }

        $user = Auth::user();

        if (! $user->isApproved()) {
            Auth::logout();

            $this->addError('email', __('Por favor, verifique seu email.'));
        }

        RateLimiter::clear($this->throttleKey());

        $this->redirect(route('feed'), navigate: true);
    }

    public function ensureIsNotRateLimited(): bool
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return false;
        }

        $this->addError('rateLimiter',
            __('Muitas tentativas de login. Tem certeza que você sabe o que está fazendo?')
        );

        return true;
    }

    public function throttleKey(): string
    {
        return \Str::transliterate(strtolower($this->email)) . '|' . request()->ip();
    }

    #[Layout('components.layouts.auth')]
    public function render(): View
    {
        return view('livewire.auth.login');
    }
}
