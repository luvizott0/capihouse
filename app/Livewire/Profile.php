<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Perfil')]
#[Layout('components.layouts.feed')]
class Profile extends Component
{
    public function render(): View
    {
        $posts = Post::query()
            ->with('user', 'likes', 'comments', 'hashtags')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('livewire.profile', [
            'posts' => $posts,
        ]);
    }
}
