<?php

use App\Models\Post;
use App\Models\User;

test('guests are redirected to login from app pages', function (string $routeName) {
    $this->get(route($routeName))
        ->assertRedirect(route('login'));
})->with([
    'feed',
    'perfil',
    'eventos',
    'acervo',
]);

test('authenticated users can open app pages and see shared navigation shell', function (string $routeName, string $placeholderText) {
    $user = User::factory()->create();
    $otherUser = User::factory()->create(['name' => 'Outro Usuario']);

    Post::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertOk()
        ->assertSee('Feed')
        ->assertSee('Perfil')
        ->assertSee('Eventos')
        ->assertSee('Acervo')
        ->assertSee('Selecionar tipo de pesquisa')
        ->assertSee($placeholderText)
        ->assertSee('Usuarios do site')
        ->assertSee('Usuarios')
        ->assertSee($otherUser->name);
})->with([
    ['feed', 'Pesquisar posts...'],
    ['perfil', 'Pesquisar posts deste perfil...'],
    ['eventos', 'Pesquisar eventos...'],
    ['acervo', 'Pesquisar midias do acervo...'],
]);

test('perfil page lists authenticated user posts', function () {
    $user = User::factory()->create();

    Post::factory()->for($user)->create([
        'content' => 'Postagem do meu perfil',
    ]);

    $this->actingAs($user)
        ->get(route('perfil'))
        ->assertOk()
        ->assertSee('Postagem do meu perfil');
});
