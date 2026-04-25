<?php

namespace App\Livewire\App\Profile;

use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profile')]
#[Layout('components.layouts.auth')]
class Index extends Component
{
    public User $profileUser;

    public bool $isOwner = false;

    public function mount(?User $user = null): void
    {
        $this->profileUser = $user ?? auth()->user();
        $this->isOwner = auth()->id() === $this->profileUser->id;
    }

    public function render(): View
    {
        $posts = Post::query()
            ->with('user', 'likes', 'comments', 'hashtags')
            ->where('user_id', $this->profileUser->id)
            ->latest()
            ->paginate(10);

        return view('livewire.app.profile.index', [
            'profileUser' => $this->profileUser,
            'isOwner' => $this->isOwner,
            'posts' => $posts,
        ]);
    }
}
