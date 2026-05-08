<?php

use App\Livewire\App\Feed\Post\Card;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

test('interaction buttons except like use yellow tone', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    $post->feeling()->create([
        'name' => 'Feliz',
        'emoji' => ':)',
    ]);

    $this->actingAs($user);

    Livewire::test(Card::class, ['post' => $post])
        ->assertSee('text-primary-200 hover:text-primary-100', false);
});

test('comments button gets background when comments are open', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    $post->feeling()->create([
        'name' => 'Animado',
        'emoji' => ':D',
    ]);

    $this->actingAs($user);

    Livewire::test(Card::class, ['post' => $post])
        ->set('commentsOpen', true)
        ->assertSee('bg-primary-200 text-primary-900', false);
});
