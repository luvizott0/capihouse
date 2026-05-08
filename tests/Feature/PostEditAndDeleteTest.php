<?php

use App\Enums\MediaType;
use App\Livewire\App\Feed\Post\Card;
use App\Livewire\App\Feed\Post\Edit;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('post owner can edit content, hashtags and media', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $post = Post::factory()->for($owner)->create([
        'content' => 'Conteudo antigo',
    ]);
    $post->feeling()->create([
        'name' => 'Feliz',
        'emoji' => ':)',
    ]);
    $post->hashtags()->create([
        'name' => 'antiga',
    ]);

    $oldMediaPath = 'posts/media/old-photo.jpg';
    Storage::disk('public')->put($oldMediaPath, 'old-content');
    $oldMedia = $post->media()->create([
        'path' => $oldMediaPath,
        'type' => MediaType::IMAGE,
        'collection_name' => 'posts',
    ]);

    $this->actingAs($owner);

    $newPhoto = UploadedFile::fake()->image('new-photo.jpg');

    Livewire::test(Edit::class)
        ->call('openEditor', $post->id)
        ->set('content', 'Conteudo atualizado')
        ->set('feeling', 'Animado')
        ->set('emoji', ':D')
        ->set('hashtags', ['nova'])
        ->call('removeExistingMedia', $oldMedia->id)
        ->set('mediaFiles', [$newPhoto])
        ->call('savePost')
        ->assertHasNoErrors();

    $post->refresh();

    expect($post->content)->toBe('Conteudo atualizado');
    expect($post->feeling()->first()?->name)->toBe('Animado');
    expect($post->hashtags()->pluck('name')->all())->toBe(['nova']);
    expect($post->media()->count())->toBe(1);

    $this->assertDatabaseMissing('media', ['id' => $oldMedia->id]);
    Storage::disk('public')->assertMissing($oldMediaPath);
});

test('non owner cannot open post editor', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $post = Post::factory()->for($owner)->create();
    $post->feeling()->create([
        'name' => 'Feliz',
        'emoji' => ':)',
    ]);

    $this->actingAs($otherUser);

    Livewire::test(Edit::class)
        ->call('openEditor', $post->id)
        ->assertSet('postId', null)
        ->assertSet('content', null);
});

test('post owner can delete own post', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $post = Post::factory()->for($owner)->create();
    $post->feeling()->create([
        'name' => 'Feliz',
        'emoji' => ':)',
    ]);

    $mediaPath = 'posts/media/to-delete.jpg';
    Storage::disk('public')->put($mediaPath, 'content');
    $post->media()->create([
        'path' => $mediaPath,
        'type' => MediaType::IMAGE,
        'collection_name' => 'posts',
    ]);

    $this->actingAs($owner);

    Livewire::test(Card::class, ['post' => $post])
        ->call('deletePost')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    Storage::disk('public')->assertMissing($mediaPath);
});

test('non owner cannot delete another users post', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $post = Post::factory()->for($owner)->create();
    $post->feeling()->create([
        'name' => 'Feliz',
        'emoji' => ':)',
    ]);

    $this->actingAs($otherUser);

    Livewire::test(Card::class, ['post' => $post])
        ->call('deletePost')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});
