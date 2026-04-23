<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
class Login extends Component
{
    public ?string $email = '';

    public ?string $password = '';

    public bool $remember = false;

    public function tryLogin(): void
    {
        $this->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($this->ensureIsNotRateLimited()) {
            return;
        }

        if (! Auth::attempt([
            $this->loginField() => $this->loginIdentifier(),
            'password' => $this->password,
        ], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            $this->addError('credentials', __('Credenciais inválidas. Tente novamente.'));

            return;
        }

        $user = Auth::user();

        if (! $user->isApproved()) {
            Auth::logout();

            $this->addError('email', __('Por favor, verifique seu email.'));

            return;
        }

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }
        RateLimiter::clear($this->throttleKey());

        $this->redirect(route('feed'), navigate: true);
    }

    public function ensureIsNotRateLimited(): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return false;
        }

        $this->addError('rateLimiter',
            __('Muitas tentativas de login. Tem certeza que você sabe o que está fazendo?')
        );

        return true;
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->loginIdentifier())).'|'.request()->ip();
    }

    private function loginField(): string
    {
        return filter_var($this->loginIdentifier(), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    private function loginIdentifier(): string
    {
        return trim((string) $this->email);
    }

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.login');
    }
}
