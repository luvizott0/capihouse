<?php

use App\Livewire\App\Profile\ImageUploadCropper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('renders the avatar uploader component', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ImageUploadCropper::class, ['user' => $user, 'type' => 'avatar'])
        ->assertStatus(200);
});

it('renders the banner uploader component', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ImageUploadCropper::class, ['user' => $user, 'type' => 'banner'])
        ->assertStatus(200);
});

it('saves an avatar image and updates avatar_url', function () {
    $user = User::factory()->create(['avatar_url' => null]);
    $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

    Livewire::actingAs($user)
        ->test(ImageUploadCropper::class, ['user' => $user, 'type' => 'avatar'])
        ->set('imageFile', $file)
        ->call('save')
        ->assertDispatched('profile::close-avatar-modal')
        ->assertDispatched('profile::image-updated');

    $user->refresh();
    expect($user->avatar_url)->not->toBeNull();

    $relativePath = str_replace('/storage/', '', parse_url($user->avatar_url, PHP_URL_PATH));
    Storage::disk('public')->assertExists($relativePath);
});

it('saves a banner image and updates banner_url', function () {
    $user = User::factory()->create(['banner_url' => null]);
    $file = UploadedFile::fake()->image('banner.jpg', 1200, 400);

    Livewire::actingAs($user)
        ->test(ImageUploadCropper::class, ['user' => $user, 'type' => 'banner'])
        ->set('imageFile', $file)
        ->call('save')
        ->assertDispatched('profile::close-banner-modal')
        ->assertDispatched('profile::image-updated');

    $user->refresh();
    expect($user->banner_url)->not->toBeNull();
});

it('validates that imageFile is required', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ImageUploadCropper::class, ['user' => $user, 'type' => 'avatar'])
        ->call('save')
        ->assertHasErrors(['imageFile' => 'required']);
});

it('validates that imageFile must be an image', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    Livewire::actingAs($user)
        ->test(ImageUploadCropper::class, ['user' => $user, 'type' => 'avatar'])
        ->set('imageFile', $file)
        ->call('save')
        ->assertHasErrors(['imageFile' => 'image']);
});

it('does not allow another user to upload to someone else profile', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $file = UploadedFile::fake()->image('hack.jpg', 200, 200);

    Livewire::actingAs($attacker)
        ->test(ImageUploadCropper::class, ['user' => $owner, 'type' => 'avatar'])
        ->set('imageFile', $file)
        ->call('save');

    $owner->refresh();
    expect($owner->avatar_url)->toBeNull();
});

it('deletes old image when replacing avatar', function () {
    Storage::disk('public')->put('avatars/old.jpg', 'fake');
    $oldUrl = Storage::url('avatars/old.jpg');

    $user = User::factory()->create(['avatar_url' => $oldUrl]);
    $file = UploadedFile::fake()->image('new.jpg', 200, 200);

    Livewire::actingAs($user)
        ->test(ImageUploadCropper::class, ['user' => $user, 'type' => 'avatar'])
        ->set('imageFile', $file)
        ->call('save');

    Storage::disk('public')->assertMissing('avatars/old.jpg');
});
