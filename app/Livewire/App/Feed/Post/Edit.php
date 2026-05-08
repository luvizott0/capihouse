<?php

namespace App\Livewire\App\Feed\Post;

use App\Enums\MediaType;
use App\Models\Hashtag;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public ?int $postId = null;

    public ?string $content = null;

    public ?string $hashtag = null;

    public ?string $feeling = null;

    public ?string $emoji = null;

    /** @var array<int, TemporaryUploadedFile> */
    public array $mediaFiles = [];

    /** @var array<int, string> */
    public array $hashtags = [];

    /** @var array<int, array{id:int,type:string,url:string,path:string}> */
    public array $existingMedia = [];

    /** @var array<int, int> */
    public array $removedMediaIds = [];

    protected function rules(): array
    {
        return [
            'content' => ['nullable', 'string', 'max:2000'],
            'hashtag' => ['nullable', 'string', 'max:25'],
            'feeling' => ['required', 'string', 'max:50'],
            'mediaFiles.*' => ['nullable', 'file', 'mimes:png,jpg,jpeg,gif,mp4,mov,ogg', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.max' => 'O conteudo do post deve conter no maximo 2000 caracteres.',
            'hashtag.max' => 'A hashtag deve conter no maximo 25 caracteres.',
            'feeling.required' => 'O campo de sentimento e obrigatorio.',
            'mediaFiles.*.mimes' => 'O arquivo deve ser uma imagem (png, jpg, jpeg, gif) ou um video (mp4, mov, ogg).',
            'mediaFiles.*.max' => 'O arquivo nao pode ser maior que 20MB.',
        ];
    }

    #[On('post::edit')]
    public function openEditor(int $postId): void
    {
        $post = Post::query()
            ->with(['hashtags', 'feeling', 'media'])
            ->where('id', $postId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $post) {
            $this->resetEditor();

            return;
        }

        $this->postId = $post->id;
        $this->content = $post->content;
        $this->feeling = $post->feeling?->name;
        $this->emoji = $post->feeling?->emoji;
        $this->hashtags = $post->hashtags->pluck('name')->values()->toArray();
        $this->hashtag = null;
        $this->removedMediaIds = [];
        $this->mediaFiles = [];
        $this->existingMedia = $post->media->map(function ($media) {
            return [
                'id' => $media->id,
                'type' => $media->type->value,
                'url' => Storage::url($media->path),
                'path' => $media->path,
            ];
        })->toArray();
    }

    #[Computed]
    public function hasContent(): bool
    {
        return filled($this->content)
            || count($this->existingMedia) > 0
            || count($this->mediaFiles) > 0;
    }

    public function addHashtag(): void
    {
        $hashtag = trim($this->hashtag ?? '');

        $this->validateOnly('hashtag');

        if ($hashtag === '') {
            return;
        }

        if (in_array($hashtag, $this->hashtags, true)) {
            $this->hashtag = null;

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

    public function removeExistingMedia(int $mediaId): void
    {
        if (! in_array($mediaId, $this->removedMediaIds, true)) {
            $this->removedMediaIds[] = $mediaId;
        }

        $this->existingMedia = array_values(array_filter(
            $this->existingMedia,
            fn (array $media): bool => $media['id'] !== $mediaId,
        ));
    }

    public function savePost(): void
    {
        if (! $this->hasContent()) {
            return;
        }

        $this->validate();

        if ($this->postId === null) {
            return;
        }

        /** @var Post|null $post */
        $post = Post::query()
            ->with(['media', 'hashtags', 'feeling'])
            ->where('id', $this->postId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $post) {
            $this->resetEditor();

            return;
        }

        DB::transaction(function () use ($post): void {
            $post->update([
                'content' => $this->content,
            ]);

            $post->feeling()->updateOrCreate(
                [],
                [
                    'name' => $this->feeling,
                    'emoji' => $this->emoji ?? '🙂',
                ],
            );

            $hashtagIds = [];

            foreach ($this->hashtags as $hashtagName) {
                $hashtag = Hashtag::firstOrCreate(['name' => $hashtagName]);
                $hashtagIds[] = $hashtag->id;
            }

            $post->hashtags()->sync($hashtagIds);

            $mediaToDelete = $post->media()->whereIn('id', $this->removedMediaIds)->get();
            foreach ($mediaToDelete as $media) {
                Storage::disk('public')->delete($media->path);
                $media->delete();
            }

            foreach ($this->mediaFiles as $file) {
                $path = $file->store('posts/media', 'public');
                $post->media()->create([
                    'path' => $path,
                    'type' => str_starts_with($file->getMimeType(), 'video') ? MediaType::VIDEO : MediaType::IMAGE,
                    'collection_name' => 'posts',
                ]);
            }
        });

        $this->dispatch('posts::reload');
        $this->dispatch('profile-posts::reload');
        $this->dispatch('post::close-edit-modal');
        $this->resetEditor();
    }

    public function resetEditor(): void
    {
        $this->reset([
            'postId',
            'content',
            'hashtag',
            'feeling',
            'emoji',
            'mediaFiles',
            'hashtags',
            'existingMedia',
            'removedMediaIds',
        ]);
    }

    #[On('post::close-edit-modal')]
    public function handleClose(): void
    {
        $this->resetEditor();
    }

    public function render(): View
    {
        return view('livewire.app.feed.post.edit');
    }
}
