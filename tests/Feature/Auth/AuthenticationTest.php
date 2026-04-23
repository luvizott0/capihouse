<?php

use App\Enums\UserStatuses;
use App\Livewire\Auth\Login;
use App\Models\User;
use Laravel\Fortify\Features;
use Livewire\Livewire;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

test('home route can be rendered', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('feed', absolute: false));

    $this->assertAuthenticated();
});

test('users can authenticate using username', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->username,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('feed', absolute: false));

    $this->assertAuthenticated();
});

test('users can authenticate in livewire login using username', function () {
    $user = User::factory()->create([
        'status' => UserStatuses::APPROVED,
    ]);

    Livewire::test(Login::class)
        ->set('email', $user->username)
        ->set('password', 'password')
        ->set('remember', false)
        ->call('tryLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('feed'));

    $this->assertAuthenticatedAs($user);
});

test('remember token is refreshed when remember me is enabled in livewire login', function () {
    $user = User::factory()->create([
        'status' => UserStatuses::APPROVED,
        'remember_token' => null,
    ]);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->set('remember', true)
        ->call('tryLogin')
        ->assertHasNoErrors();

    expect($user->refresh()->remember_token)->not->toBeNull();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

test('users with two factor enabled are redirected to two factor challenge', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));

    $this->assertGuest();
});
