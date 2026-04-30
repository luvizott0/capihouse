<?php

namespace App\Livewire\App\Events;

use App\Enums\MediaType;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $description = '';

    public string $date = '';

    public ?int $selectedUser = null;

    public array $guests = [];

    /** @var array<int, TemporaryUploadedFile> */
    public array $photo = [];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date' => ['required', 'date', 'after:now'],
            'guests' => ['nullable', 'array'],
            'photo.*' => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:20480'], // 20MB max
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'description.required' => 'A descrição é obrigatória.',
            'date.required' => 'A data é obrigatória.',
            'photo.*.mimes' => 'O arquivo deve ser uma imagem (png, jpg, jpeg, gif).',
            'photo.*.max' => 'O arquivo não pode ser maior que 20MB.',
            'date.after' => 'A data deve ser uma data futura.',
        ];

    }

    public function create(): void
    {

        $this->validate();

        DB::transaction(function () {
            /** @var Event $event */
            $event = auth()->user()->events()->create([
                'name' => $this->name,
                'description' => $this->description,
                'date' => $this->date,
            ]);

            foreach ($this->photo as $file) {
                $path = $file->store('events/media', 'public');
                $event->media()->create([
                    'path' => $path,
                    'type' => MediaType::IMAGE,
                    'collection_name' => 'events',
                ]);
            }

            $event->guests()->attach(array_column($this->guests, 'id'));

        });

        $this->reset(['name', 'description', 'date', 'guests', 'photo']);
        $this->dispatch('events::reload');
        $this->dispatch('event::close-modal');
    }

    public function updatedSelectedUser(?int $value): void
    {
        $this->resetValidation(['guests']);

        if (empty($value)) {
            return;
        }

        $user = User::find($value);

        if (isset($this->guests[$value])) {
            $this->addError('guests', 'User is already added as a guest.');
            $this->reset('selectedUser');

            return;
        }

        if ($user) {
            $this->addNewGuest($user);
        }

        $this->reset('selectedUser');
    }

    public function addNewGuest(User $user): void
    {
        $this->guests[$user->id] = $this->buildGuestArray($user);
    }

    public function buildGuestArray(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }

    public function removeGuest(int $guestId): void
    {
        unset($this->guests[$guestId]);
    }

    #[On('event::create')]
    public function resetForm(): void
    {
        $this->reset(['name', 'description', 'date', 'guests', 'photo']);
    }

    #[Computed]
    public function users(): Collection
    {
        return User::query()
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.app.events.create');
    }
}
