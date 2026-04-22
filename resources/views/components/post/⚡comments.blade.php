<?php

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
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
};
?>

<div>
    <div class="mt-4">
        @foreach($comments as $comment)
            <div class="flex items-start gap-1 mb-2">
                <x-user-info size="6" text-size="xs" :show-name="false" :user="$comment->user"/>
                <div class="flex flex-col bg-[#EAE7E1] w-full p-1 px-3 rounded-sm border border-[#D5C5B9]">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-primary-800">{{ $comment->user->name }}</span>

                        @if(auth()->id() === $comment->user_id)
                            <div class="text-end">
                                @if($editingCommentId === $comment->id)
                                    <button wire:click="saveEditing" class="cursor-pointer">
                                        <p class="text-xs text-primary-800 font-bold underline">
                                            Salvar
                                        </p>
                                    </button>
                                    <button wire:click="cancelEditing" class="cursor-pointer">
                                        <p class="text-xs text-primary-800 font-bold underline">
                                            Cancelar
                                        </p>
                                    </button>
                                @else
                                    <button wire:click="startEditing({{ $comment->id }})" class="cursor-pointer">
                                        <p class="text-xs text-primary-800 font-bold underline">
                                            Editar
                                        </p>
                                    </button>
                                    <button wire:click="deleteComment({{ $comment->id }})" class="cursor-pointer">
                                        <p class="text-xs text-primary-800 font-bold underline">
                                            Deletar
                                        </p>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($editingCommentId === $comment->id)
                        <textarea
                            wire:model.live="editingComment"
                            rows="1"
                            class="text-sm mt-1 px-2 py-1 border border-[#D5C5B9] rounded-sm"
                        ></textarea>
                        @error('editingComment')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    @else
                        <span class="text-sm break-all">{{ $comment->content }}</span>
                    @endif
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
