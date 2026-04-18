<?php

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
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

        $this->reset('comment');
    }

    public function deleteComment(int $id): void
    {
        $comment = PostComment::find($id);

        $comment->delete();

        $this->dispatch('loadComments');
    }
};
?>

<div>
    <div class="mt-4">
        @foreach($comments as $comment)
            <div class="flex items-start gap-2 mb-2">
                <livewire:user-avatar size="6" text-size="xs"/>
                <div class="flex flex-col bg-[#EAE7E1] w-full py-1 px-3 rounded-sm border border-[#D5C5B9]">
                    <span class="text-sm font-bold text-primary-800">{{ $comment->user->name }}</span>
                    <span class="text-sm">{{ $comment->content }}</span>

                    <div class="text-end">
                        <button class="cursor-pointer">
                            <p class="text-sm text-primary-800 font-bold underline">
                                Editar
                            </p>
                        </button>
                        <button wire:click="deleteComment({{ $comment->id }})" class="cursor-pointer">
                            <p class="text-sm text-primary-800 font-bold underline">
                                Deletar
                            </p>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex items-start gap-2 mt-3">
        <div class="w-full">
            <x-forms.comment-field wire:model.live="comment" placeholder="{{ __('Deixe um comentário...') }}" max="500"/>
        </div>

        <button wire:click="send" class="bg-primary px-4 py-0.5 rounded-sm cursor-pointer">
            <span class="text-sm text-white">Enviar</span>
        </button>
    </div>
</div>
