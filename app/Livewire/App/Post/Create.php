<?php

namespace App\Livewire\App\Post;

use App\Models\hashtag;
use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    public ?string $content = null;

    public ?string $hashtag = null;

    public ?string $feeling = null;

    public ?string $emoji = null;

    /** @var array<int, string> */
    public array $hashtags = [];

    public function rules(): array
    {
        return [
            'content' => ['nullable', 'string', 'max:150'],
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
            $hashtag = hashtag::firstOrCreate(['name' => $hashtagName]);
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

    public function render(): View
    {
        return view('livewire.app.post.create');
    }
}
