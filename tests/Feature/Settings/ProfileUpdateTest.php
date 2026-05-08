<?php

use App\Livewire\Settings\Profile;
use App\Models\User;
use Livewire\Livewire;

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/settings/profile')->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Profile::class)
        ->set('name', 'Test User')
        ->set('username', 'test.user')
        ->set('email', 'test@example.com')
        ->set('avatar_url', 'https://example.com/new-avatar.jpg')
        ->set('banner_url', 'https://example.com/new-banner.jpg')
        ->set('bio', 'Uma bio de teste')
        ->set('birth', '2000-04-17')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->username)->toEqual('test.user');
    expect($user->email)->toEqual('test@example.com');
    expect($user->avatar_url)->toEqual('https://example.com/new-avatar.jpg');
    expect($user->banner_url)->toEqual('https://example.com/new-banner.jpg');
    expect($user->bio)->toEqual('Uma bio de teste');
    expect($user->birth?->format('Y-m-d'))->toEqual('2000-04-17');
    expect($user->email_verified_at)->toBeNull();
});

test('profile banner url can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Profile::class)
        ->set('name', $user->name)
        ->set('username', $user->username)
        ->set('email', $user->email)
        ->set('banner_url', 'https://example.com/new-banner.jpg')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->banner_url)->toEqual('https://example.com/new-banner.jpg');
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Profile::class)
        ->set('name', 'Test User')
        ->set('username', $user->username)
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('username must be unique when updating profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create([
        'username' => 'username-in-use',
    ]);

    $this->actingAs($user);

    $response = Livewire::test(Profile::class)
        ->set('name', $user->name)
        ->set('username', $otherUser->username)
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasErrors(['username']);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('settings.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
