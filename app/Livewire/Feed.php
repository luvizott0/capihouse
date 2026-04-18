<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Feed')]
#[Layout('components.layouts.feed')]
class Feed extends Component
{
    public function render(): View
    {
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        return view('livewire.feed', [
            'posts' => $posts,
        ]);
    }
}
