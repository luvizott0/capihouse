<?php

use App\Enums\UserStatuses;
use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('blocks pending users from logging in', function () {
    $user = User::factory()->pending()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('tryLogin');

    $this->assertGuest();
});

it('blocks banned users from logging in', function () {
    $user = User::factory()->banned()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('tryLogin');

    $this->assertGuest();
});

it('allows approved users to log in', function () {
    $user = User::factory()->create(['status' => UserStatuses::APPROVED]);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('tryLogin');

    $this->assertAuthenticatedAs($user);
});

it('redirects non-approved users away from protected routes', function () {
    $user = User::factory()->pending()->create();

    $this->actingAs($user)
        ->get(route('feed'))
        ->assertRedirect(route('login'));
});
