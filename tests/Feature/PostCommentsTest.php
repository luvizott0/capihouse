<?php

use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Livewire\Livewire;

test('comment owner can edit a comment', function () {
    $owner = User::factory()->create();
    $post = Post::query()->create([
        'user_id' => $owner->id,
        'content' => 'Post para testar comentario',
    ]);
    $comment = PostComment::query()->create([
        'post_id' => $post->id,
        'user_id' => $owner->id,
        'content' => 'Comentario original',
    ]);

    $this->actingAs($owner);

    Livewire::test('post.comments', ['post' => $post])
        ->call('startEditing', $comment->id)
        ->set('editingComment', 'Comentario editado')
        ->call('saveEditing')
        ->assertHasNoErrors();

    expect($comment->refresh()->content)->toBe('Comentario editado');
});

test('non owner cannot edit a comment', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::query()->create([
        'user_id' => $owner->id,
        'content' => 'Post para testar comentario',
    ]);
    $comment = PostComment::query()->create([
        'post_id' => $post->id,
        'user_id' => $owner->id,
        'content' => 'Comentario original',
    ]);

    $this->actingAs($otherUser);

    Livewire::test('post.comments', ['post' => $post])
        ->call('startEditing', $comment->id)
        ->assertSet('editingCommentId', null)
        ->assertSet('editingComment', null);

    expect($comment->refresh()->content)->toBe('Comentario original');
});

test('non owner cannot save forged comment edits', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::query()->create([
        'user_id' => $owner->id,
        'content' => 'Post para testar comentario',
    ]);
    $comment = PostComment::query()->create([
        'post_id' => $post->id,
        'user_id' => $owner->id,
        'content' => 'Comentario original',
    ]);

    $this->actingAs($otherUser);

    Livewire::test('post.comments', ['post' => $post])
        ->set('editingCommentId', $comment->id)
        ->set('editingComment', 'Tentativa indevida')
        ->call('saveEditing')
        ->assertSet('editingCommentId', null)
        ->assertSet('editingComment', null);

    expect($comment->refresh()->content)->toBe('Comentario original');
});

test('comment owner can delete a comment', function () {
    $owner = User::factory()->create();
    $post = Post::query()->create([
        'user_id' => $owner->id,
        'content' => 'Post para testar comentario',
    ]);
    $comment = PostComment::query()->create([
        'post_id' => $post->id,
        'user_id' => $owner->id,
        'content' => 'Comentario para deletar',
    ]);

    $this->actingAs($owner);

    Livewire::test('post.comments', ['post' => $post])
        ->call('deleteComment', $comment->id)
        ->assertHasNoErrors();

    $this->assertSoftDeleted('post_comments', ['id' => $comment->id]);
});

test('non owner cannot delete a comment', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::query()->create([
        'user_id' => $owner->id,
        'content' => 'Post para testar comentario',
    ]);
    $comment = PostComment::query()->create([
        'post_id' => $post->id,
        'user_id' => $owner->id,
        'content' => 'Comentario protegido',
    ]);

    $this->actingAs($otherUser);

    Livewire::test('post.comments', ['post' => $post])
        ->call('deleteComment', $comment->id)
        ->assertHasNoErrors();

    expect($comment->refresh()->deleted_at)->toBeNull();
});
