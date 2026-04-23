@props([
    'user' => auth()->user(),
    'size' => 10,
    'showName' => true,
    'textSize' => 'sm',
])

<div class="flex items-center gap-2 mb-3">
    <div class="flex items-center justify-center p-4 w-{{ $size }} h-{{ $size }} text-{{ $textSize }} font-bold text-white bg-primary">
        {{ $user->initials() }}
    </div>
    <div>
        @if($showName)
            <p class="text-sm font-mono font-bold text-primary-800">{{ $user->name }}</p>
            <p class="text-xs font-mono text-subtitle">{{ '@' . $user->username }}</p>
        @endif
    </div>
</div>
