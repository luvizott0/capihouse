<?php

use App\Models\Post;
use App\Models\User;

test('authenticated user can see own profile header with edit action', function () {
    $user = User::factory()->create([
        'name' => 'Lucas Oliveira',
        'username' => 'alukinha',
        'banner_url' => 'https://example.com/banner.jpg',
    ]);

    $post = Post::factory()->for($user)->create([
        'content' => 'Post do meu perfil',
    ]);
    $post->feeling()->create([
        'name' => 'Feliz',
        'emoji' => '🙂',
    ]);

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertOk()
        ->assertSee('Lucas Oliveira')
        ->assertSee('@alukinha')
        ->assertSee('Editar')
        ->assertSee('https://example.com/banner.jpg')
        ->assertSee('Post do meu perfil');
});

test('authenticated user can see another user profile without edit action', function () {
    $viewer = User::factory()->create();
    $profileOwner = User::factory()->create([
        'name' => 'Ana Souza',
        'username' => 'ana.souza',
    ]);

    $post = Post::factory()->for($profileOwner)->create([
        'content' => 'Conteudo da Ana',
    ]);
    $post->feeling()->create([
        'name' => 'Animada',
        'emoji' => '😄',
    ]);

    $this->actingAs($viewer)
        ->get(route('profile.show', $profileOwner->username))
        ->assertOk()
        ->assertSee('Ana Souza')
        ->assertSee('@ana.souza')
        ->assertDontSee('Editar')
        ->assertSee('Conteudo da Ana');
});

test('guests are redirected when trying to open user profile page', function () {
    $profileOwner = User::factory()->create();

    $this->get(route('profile.show', $profileOwner->username))
        ->assertRedirect(route('login'));
});
