<?php

use App\Models\Post;
use App\Models\User;

test('guests are redirected to login from app pages', function (string $routeName) {
    $this->get(route($routeName))
        ->assertRedirect(route('login'));
})->with([
    'feed',
    'profile',
    'events',
    'catalog',
]);

test('authenticated users can open app pages and see shared navigation shell', function (string $routeName, string $placeholderText, string $actionLabel) {
    $user = User::factory()->create();
    $otherUser = User::factory()->create(['name' => 'Outro Usuario']);

    $post = Post::factory()->for($user)->create();
    $post->feeling()->create([
        'name' => 'Feliz',
        'emoji' => '🙂',
    ]);

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertOk()
        ->assertSee('Feed')
        ->assertSee('Perfil')
        ->assertSee('Eventos')
        ->assertSee('Acervo')
        ->assertSee('Selecionar tipo de pesquisa')
        ->assertSee($placeholderText)
        ->assertSee($actionLabel)
        ->assertSee('Usuarios do site')
        ->assertSee('Usuarios')
        ->assertSee($otherUser->name);
})->with([
    ['feed', 'Pesquisar posts...', 'Criar post'],
    ['profile', 'Pesquisar posts deste perfil...', 'Logout'],
    ['events', 'Pesquisar eventos...', 'Adicionar evento'],
    ['catalog', 'Pesquisar midias do acervo...', 'Adicionar midia'],
]);

test('perfil page lists authenticated user posts', function () {
    $user = User::factory()->create();

    $post = Post::factory()->for($user)->create([
        'content' => 'Postagem do meu perfil',
    ]);
    $post->feeling()->create([
        'name' => 'Animado',
        'emoji' => '😄',
    ]);

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertOk()
        ->assertSee('Postagem do meu perfil');
});
