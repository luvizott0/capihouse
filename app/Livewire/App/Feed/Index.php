<?php

namespace App\Livewire\App\Feed;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Feed')]
#[Layout('components.layouts.auth')]
class Index extends Component
{
    #[On('posts::reload')]
    public function reloadPosts(): void
    {
        $this->render();
    }

    public function render(): View
    {
        $posts = Post::with('user', 'likes', 'comments', 'hashtags', 'feeling')
            ->latest()
            ->paginate(10);

        return view('livewire.app.feed.index', [
            'posts' => $posts,
        ]);
    }
}
