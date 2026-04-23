<?php

use App\Models\Hashtag;
use App\Models\Post;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?string $content = null;

    public ?string $hashtag = null;

    public ?string $feeling = null;

    public ?string $emoji = null;

    /** @var array<int, string> */
    public array $hashtags = [];

    public function rules(): array
    {
        return [
            'content' => ['nullable', 'string', 'max:2000'],
            'hashtag' => ['nullable', 'string', 'max:20'],
            'feeling' => ['required', 'string', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.max' => 'O conteúdo do post deve conter no máximo 2000 caracteres.',
            'hashtag.max' => 'A hashtag deve conter no máximo 25 caracteres.',
            'feeling.required' => 'O campo de sentimento é obrigatório.',
        ];
    }

    #[Computed]
    public function hasContent(): bool
    {
        return $this->content !== null;
    }

    public function addHashtag(): void
    {
        $hashtag = trim($this->hashtag ?? '');

        $this->validateOnly('hashtag');

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

        $post->feeling()->create([
            'name' => $this->feeling,
            'emoji' => $this->emoji ?? '🙂',
        ]);

        foreach ($this->hashtags as $hashtagName) {
            $hashtag = Hashtag::firstOrCreate(['name' => $hashtagName]);
            $post->hashtags()->attach($hashtag);
        }

        $this->reset('content', 'hashtags', 'hashtag', 'emoji');
        $this->dispatch('posts::reload');
        $this->dispatch('post::close-modal');
    }

    #[On('post::create')]
    public function resetForm(): void
    {
        $this->reset('content', 'hashtags', 'hashtag', 'emoji', 'feeling');
    }
};
?>

<div>
    <x-modal
        open-event="post::create"
        close-event="post::close-modal"
        title="Criar Post"
        max-width="lg"
    >
        <div>
            <x-user-info/>
            <x-forms.comment-field
                wire:model="content"
                placeholder="O que você está pensando?"
                max="500"
            />

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

            <div class="flex justify-between items-center gap-2 mt-6">
                <div class="flex gap-2  items-center">
                    <div>
                        <x-forms.emoji-picker target="emoji"/>
                    </div>

                    <x-forms.comment-field
                        wire:model="feeling"
                        placeholder="Me sentindo..."
                        max="10"
                        counter-position="side"
                    />
                </div>

                <button
                    type="button"
                    class="btn-primary"
                    wire:click="createPost"
                >
                    [ Publicar ]
                </button>
            </div>
        </div>
    </x-modal>
</div>
