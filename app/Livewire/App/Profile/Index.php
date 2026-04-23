<?php

namespace App\Livewire\App\Profile;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profile')]
#[Layout('components.layouts.auth')]
class Index extends Component
{
    public function render(): View
    {
        $posts = Post::query()
            ->with('user', 'likes', 'comments', 'hashtags')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('livewire.app.profile.index', [
            'posts' => $posts,
        ]);
    }
}
