<?php

use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

test('post create accepts emoji-only content through target property', function () {
    $emoji = "\u{1F642}";

    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('post.create')
        ->set('content', null)
        ->set('emoji', $emoji)
        ->set('feeling', 'Feliz')
        ->call('createPost');

    $post = Post::query()->latest('id')->first();

    expect($post)
        ->not->toBeNull()
        ->and($post->content)->toBe($emoji)
        ->and($post->user_id)->toBe($user->id);
});
