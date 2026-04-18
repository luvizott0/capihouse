<?php

use App\Models\User;
use Livewire\Component;

new class extends Component {
    public int $size = 10;

    public string $textSize = 'sm';

    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
    }
};
?>

<div>
    <div class="flex items-center justify-center w-{{ $size }} h-{{ $size }} text-{{ $textSize }} font-bold text-white bg-primary">
        {{ $user->initials() }}
    </div>
</div>
