<?php

use App\Models\Post;
use App\Models\User;

test('guests are redirected to login from feed', function () {
    $this->get(route('feed'))
        ->assertRedirect(route('login'));
});

test('authenticated users can visit the feed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('feed'))
        ->assertOk();
});

test('feed displays posts', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create(['content' => 'Olha essa capivara!']);

    $this->actingAs($user)
        ->get(route('feed'))
        ->assertOk()
        ->assertSee('Olha essa capivara!')
        ->assertSee($user->name);
});

test('feed shows empty state when no posts exist', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('feed'))
        ->assertOk()
        ->assertSee('Nenhum post ainda');
});

test('successful login redirects to feed', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('feed'));
});
