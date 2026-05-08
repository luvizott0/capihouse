<?php

namespace App\Livewire\App\Feed\Post;

use App\Enums\MediaType;
use App\Models\Hashtag;
use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public ?string $content = null;

    public ?string $hashtag = null;

    public ?string $feeling = null;

    public ?string $emoji = null;

    /** @var array<int, TemporaryUploadedFile> */
    public array $mediaFiles = [];

    /** @var array<int, string> */
    public array $hashtags = [];

    public function rules(): array
    {
        return [
            'content' => ['nullable', 'string', 'max:2000'],
            'hashtag' => ['nullable', 'string', 'max:25'],
            'feeling' => ['required', 'string', 'max:50'],
            'mediaFiles.*' => ['nullable', 'file', 'mimes:png,jpg,jpeg,gif,mp4,mov,ogg', 'max:20480'], // 20MB max
        ];
    }

    public function messages(): array
    {
        return [
            'content.max' => 'O conteúdo do post deve conter no máximo 2000 caracteres.',
            'hashtag.max' => 'A hashtag deve conter no máximo 25 caracteres.',
            'feeling.required' => 'O campo de sentimento é obrigatório.',
            'mediaFiles.*.mimes' => 'O arquivo deve ser uma imagem (png, jpg, jpeg, gif) ou um vídeo (mp4, mov, ogg).',
            'mediaFiles.*.max' => 'O arquivo não pode ser maior que 20MB.',
        ];
    }

    #[Computed]
    public function hasContent(): bool
    {
        return $this->content !== null || count($this->mediaFiles) > 0;
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

    public function removeMedia(int $index): void
    {
        unset($this->mediaFiles[$index]);
        $this->mediaFiles = array_values($this->mediaFiles);
    }

    public function createPost(): void
    {
        if (! $this->hasContent()) {
            return;
        }

        $this->validate();

        /** @var Post $post */
        $post = auth()->user()->posts()->create([
            'content' => $this->content,
        ]);

        foreach ($this->mediaFiles as $file) {
            $path = $file->store('posts/media', 'public');
            $post->media()->create([
                'path' => $path,
                'type' => str_starts_with($file->getMimeType(), 'video') ? MediaType::VIDEO : MediaType::IMAGE,
                'collection_name' => 'posts',
            ]);
        }

        $post->feeling()->create([
            'name' => $this->feeling,
            'emoji' => $this->emoji ?? '🙂',
        ]);

        foreach ($this->hashtags as $hashtagName) {
            $hashtag = Hashtag::firstOrCreate(['name' => $hashtagName]);
            $post->hashtags()->attach($hashtag);
        }

        $this->reset('content', 'hashtags', 'hashtag', 'emoji', 'mediaFiles');
        $this->dispatch('posts::reload');
        $this->dispatch('post::close-modal');
    }

    #[On('post::create')]
    public function resetForm(): void
    {
        $this->reset('content', 'hashtags', 'hashtag', 'emoji', 'feeling', 'mediaFiles');
    }

    public function render(): View
    {
        return view('livewire.app.feed.post.create');
    }
}
