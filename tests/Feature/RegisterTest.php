<?php

use App\Enums\UserStatuses;
use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('shows the register page', function () {
    $this->get(route('register'))->assertOk();
});

it('creates a pending user on registration', function () {
    Livewire::test(Register::class)
        ->set('name', 'João Silva')
        ->set('username', 'joaosilva')
        ->set('email', 'joao@example.com')
        ->set('password', 'Password1!')
        ->set('password_confirmation', 'Password1!')
        ->call('register')
        ->assertSet('registered', true);

    $user = User::where('email', 'joao@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->status)->toBe(UserStatuses::PENDING);
});

it('shows validation errors for missing fields', function () {
    Livewire::test(Register::class)
        ->call('register')
        ->assertHasErrors(['name', 'username', 'email', 'password']);
});

it('prevents duplicate email registration', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('username', 'testuser')
        ->set('email', 'existing@example.com')
        ->set('password', 'Password1!')
        ->set('password_confirmation', 'Password1!')
        ->call('register')
        ->assertHasErrors(['email']);
});
