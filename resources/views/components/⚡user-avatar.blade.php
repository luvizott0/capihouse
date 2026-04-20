<?php

use App\Models\User;
use Livewire\Component;

new class extends Component {
    public int $size = 10;

    public bool $showName = true;

    public string $textSize = 'sm';

    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
    }
};
?>

<div class="flex items-center gap-3 mb-3">
    <div class="flex items-center justify-center w-{{ $size }} h-{{ $size }} text-{{ $textSize }} font-bold text-white bg-primary">
        {{ $user->initials() }}
    </div>
    <div>
        @if($showName)
            <p class="text-sm font-bold text-primary-800">{{ $user->name }}</p>
            <p class="text-xs text-subtitle">{{ '@' . $user->username }}</p>
        @endif
    </div>
</div>
