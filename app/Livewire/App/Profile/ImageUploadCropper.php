<?php

namespace App\Livewire\App\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ImageUploadCropper extends Component
{
    use WithFileUploads;

    /** @var 'avatar'|'banner' */
    public string $type;

    public User $user;

    /** @var TemporaryUploadedFile|null */
    public $imageFile = null;

    public function mount(User $user, string $type): void
    {
        $this->user = $user;
        $this->type = $type;
    }

    public function save(): void
    {
        if (! $this->canEdit()) {
            return;
        }

        $this->validate([
            'imageFile' => ['required', 'image', 'max:4096'],
        ]);

        $directory = $this->type === 'avatar' ? 'avatars' : 'banners';
        $path = $this->imageFile->store($directory, 'public');

        $column = $this->type === 'avatar' ? 'avatar_url' : 'banner_url';
        $oldUrl = $this->user->{$column};

        $this->user->forceFill([
            $column => Storage::url($path),
        ])->save();

        $this->deleteOldImage($oldUrl);

        $this->user->refresh();
        $this->imageFile = null;

        $this->dispatch("profile::close-{$this->type}-modal");
        $this->dispatch('profile::image-updated', type: $this->type);
    }

    /**
     * Delete the previous stored image if it lives in our own storage.
     */
    private function deleteOldImage(?string $url): void
    {
        if (! $url) {
            return;
        }

        $storagePublicPath = Storage::url('');

        if (str_starts_with($url, $storagePublicPath)) {
            $relativePath = ltrim(substr($url, strlen($storagePublicPath)), '/');
            Storage::disk('public')->delete($relativePath);
        }
    }

    private function canEdit(): bool
    {
        return auth()->id() === $this->user->id;
    }

    public function render(): View
    {
        return view('livewire.app.profile.image-upload-cropper');
    }
}
