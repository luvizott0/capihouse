<?php

namespace App\Livewire\App\Post;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Comments extends Component
{
    public Post $post;

    public Collection $comments;

    public ?string $comment = null;

    public ?int $editingCommentId = null;

    public ?string $editingComment = null;

    public function mount(): void
    {
        $this->loadComments();
    }

    public function rules(): array
    {
        return [
            'comment' => ['nullable', 'string', 'max:100'],
        ];
    }

    #[On('loadComments')]
    public function loadComments(): void
    {
        $this->post->load('comments.user');

        $this->comments = $this->post->comments;
    }

    public function send(): void
    {
        if (empty($this->comment)) {
            return;
        }

        $this->validate();

        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->comment,
        ]);

        $this->dispatch('loadComments');
        $this->dispatch('post-comments-updated', postId: $this->post->id);

        $this->reset('comment');
    }

    public function startEditing(int $id): void
    {
        $comment = $this->post->comments()->findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $this->editingCommentId = $comment->id;
        $this->editingComment = $comment->content;
    }

    public function cancelEditing(): void
    {
        $this->reset(['editingCommentId', 'editingComment']);
    }

    public function saveEditing(): void
    {
        if ($this->editingCommentId === null) {
            return;
        }

        $this->validate([
            'editingComment' => ['required', 'string', 'max:100'],
        ]);

        $comment = $this->post->comments()->findOrFail($this->editingCommentId);

        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->update([
            'content' => $this->editingComment,
        ]);

        $this->cancelEditing();
        $this->dispatch('loadComments');
        $this->dispatch('post-comments-updated', postId: $this->post->id);
    }

    public function deleteComment(int $id): void
    {
        $comment = $this->post->comments()->findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        $this->dispatch('loadComments');
        $this->dispatch('post-comments-updated', postId: $this->post->id);
    }

    public function render(): View
    {
        return view('livewire.app.post.comments');
    }
}
