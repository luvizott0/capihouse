<?php

use App\Livewire\App\Post\Create;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('can create a post with media', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);

    $file1 = UploadedFile::fake()->image('photo1.jpg');
    $file2 = UploadedFile::fake()->image('photo2.jpg');

    Livewire::test(Create::class)
        ->set('content', 'Post with media')
        ->set('feeling', 'Happy')
        ->set('mediaFiles', [$file1, $file2])
        ->call('createPost')
        ->assertHasNoErrors();

    $post = $user->posts()->first();
    expect($post->content)->toBe('Post with media');
    expect($post->media)->toHaveCount(2);
    expect($post->media[0]['type'])->toBe('image');

    Storage::disk('public')->assertExists($post->media[0]['path']);
    Storage::disk('public')->assertExists($post->media[1]['path']);
});

it('can create a post with video', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);

    $video = UploadedFile::fake()->create('video.mp4', 1000, 'video/mp4');

    Livewire::test(Create::class)
        ->set('content', 'Post with video')
        ->set('feeling', 'Excited')
        ->set('mediaFiles', [$video])
        ->call('createPost')
        ->assertHasNoErrors();

    $post = $user->posts()->first();
    expect($post->media[0]['type'])->toBe('video');
    Storage::disk('public')->assertExists($post->media[0]['path']);
});

it('can remove media before posting', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $file = UploadedFile::fake()->image('photo.jpg');

    Livewire::test(Create::class)
        ->set('mediaFiles', [$file])
        ->call('removeMedia', 0)
        ->assertSet('mediaFiles', []);
});
