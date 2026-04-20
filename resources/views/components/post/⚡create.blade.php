<?php

use App\Models\Hashtag;
use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?string $content = null;

    public ?string $hashtag = null;

    /** @var array<int, string> */
    public array $hashtags = [];

    public function rules(): array
    {
        return [
            'content' => ['nullable', 'string', 'max:2000'],
            'hashtag' => ['nullable', 'string', 'max:10'],
        ];
    }

    public function hasContent(): bool
    {
        return $this->content !== null;
    }

    public function addHashtag(): void
    {
        $hashtag = trim($this->hashtag ?? '');

        if ($hashtag === '') {
            return;
        }

        $this->hashtags[] = $hashtag;
        $this->hashtag = null;
    }

    public function removeHashtag(int $index): void
    {
        unset($this->hashtags[$index]);
        $this->hashtags = array_values($this->hashtags);
    }

    public function createPost(): void
    {
        if (!$this->hasContent()) {
            return;
        }

        $this->validate();

        /** @var Post $post */
        $post = auth()->user()->posts()->create([
            'content' => $this->content,
        ]);

        // Attach hashtags to the post
        foreach ($this->hashtags as $hashtagName) {
            $hashtag = Hashtag::firstOrCreate(['name' => $hashtagName]);
            $post->hashtags()->attach($hashtag);
        }

        $this->reset('content', 'hashtags', 'hashtag');
    }

    #[On('post::close-modal')]
    public function resetForm(): void
    {
        $this->reset('content', 'hashtags', 'hashtag');
    }
};
?>

<div>
    <x-modal
        open-event="post::create"
        close-event="post::close-modal"
        title="Criar Post"
        action-label="Publicar"
    >
        <div>
            <livewire:user-avatar/>
            <textarea
                placeholder="O que você está pensando?"
                wire:model="content"
                class="w-full py-1 px-2 text-sm border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400"
            >
            </textarea>

            <div>
                <x-forms.input
                    wire:model="hashtag"
                    label="Adicionar Hashtag"
                    placeholder="Digite aqui e pressione enter..."
                    @keydown.enter="$wire.addHashtag()"
                />

                @if (count($hashtags) > 0)
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($hashtags as $index => $tag)
                            <div
                                class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-white rounded-full bg-primary-600">
                                <span>#{{ $tag }}</span>
                                <button
                                    type="button"
                                    wire:click="removeHashtag({{ $index }})"
                                    class="ml-1 hover:opacity-75"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-modal>
</div>
