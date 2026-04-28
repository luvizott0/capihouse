<?php

namespace App\Livewire\App\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Card extends Component
{
    public Post $post;

    public User $user;

    public array $media = [];

    public bool $hasMedia;

    public bool $isCarousel;

    public bool $isLiked;

    public bool $commentsOpen = false;

    public int $commentsCount = 0;

    public function mount(): void
    {
        $this->loadPostInformation();
        $this->verifyIfPostIsLiked();
        $this->commentsCount = $this->post->comments()->count();
    }

    #[On('post-comments-updated')]
    public function refreshCommentsCount(int $postId): void
    {
        if ($postId !== $this->post->id) {
            return;
        }

        $this->commentsCount = $this->post->comments()->count();
    }

    public function loadPostInformation(): void
    {
        $this->user = $this->post->user;
        $this->media = $this->post->media ?? [];
        $this->hasMedia = count($this->media) > 0;
        $this->isCarousel = count($this->media) > 1;
    }

    public function VerifyIfPostIsLiked(): void
    {
        $user = auth()->user();
        $this->isLiked = $this->post
            ->likes()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function getTime(): string
    {
        return $this->post->created_at->diffForHumans(short: true);
    }

    public function likePost(): void
    {
        if ($this->isLiked) {
            $this->post->likes()
                ->where('user_id', auth()->user()->id)
                ->delete();

            $this->isLiked = false;

            return;
        }

        $this->post->likes()->create([
            'user_id' => auth()->user()->id,
        ]);

        $this->isLiked = true;
    }

    public function render(): View
    {
        return view('livewire.app.post.card');
    }
}
