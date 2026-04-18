<?php

use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public Post $post;

    public Collection $comments;

    public ?string $comment = null;

    public function mount(): void
    {
        $this->loadComments();
    }

    #[On('commentAdded')]
    public function loadComments(): void
    {
        $this->post->load('comments.user');

        $this->comments = $this->post->comments;
    }

    public function send(): void
    {
        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->comment,
        ]);

        $this->dispatch('commentAdded');

        $this->comment = null;
    }
};
?>

<div>
    <div class="mt-4">
        @foreach($comments as $comment)
            <div class="flex items-start gap-2 mb-2">
                <div class="flex items-center justify-center w-6 h-6 p-2 text-xs font-bold text-white bg-primary">
                    {{ $comment->user->initials() }}
                </div>
                <div class="flex flex-col bg-[#EAE7E1] w-full py-1 px-3 rounded-sm border border-[#D5C5B9]">
                    <span class="text-sm font-bold text-primary-800">{{ $comment->user->name }}</span>
                    <span class="text-sm">{{ $comment->content }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex items-center gap-2 mt-3">
        <input
            wire:model="comment"
            type="text"
            placeholder="{{ __('Deixe um comentário...') }}"
            class="w-full py-1 px-4 text-sm border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400"
        />

        <button wire:click="send" class="bg-primary px-4 py-0.5 rounded-sm cursor-pointer">
            <span class="text-sm text-white">Enviar</span>
        </button>
    </div>
</div>
