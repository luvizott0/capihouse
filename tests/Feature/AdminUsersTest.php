<?php

use App\Enums\UserStatuses;
use App\Livewire\Admin\Users\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('blocks non-admins from accessing admin panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.users'))
        ->assertForbidden();
});

it('blocks guests from accessing admin panel', function () {
    $this->get(route('admin.users'))->assertRedirect(route('login'));
});

it('allows admins to access user list', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.users'))
        ->assertOk();
});

it('admin can approve a pending user', function () {
    $admin = User::factory()->admin()->create();
    $pending = User::factory()->pending()->create();

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('approve', $pending->id);

    expect($pending->fresh()->status)->toBe(UserStatuses::APPROVED);
});

it('admin can reject a pending user', function () {
    $admin = User::factory()->admin()->create();
    $pending = User::factory()->pending()->create();

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('reject', $pending->id);

    expect($pending->fresh()->status)->toBe(UserStatuses::REJECTED);
});

it('admin can ban an approved user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('ban', $user->id);

    expect($user->fresh()->status)->toBe(UserStatuses::BANNED);
});

it('admin can unban a banned user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->banned()->create();

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('unban', $user->id);

    expect($user->fresh()->status)->toBe(UserStatuses::APPROVED);
});
