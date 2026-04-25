@props([
    'user' => auth()->user(),
    'size' => 10,
    'showName' => true,
    'textSize' => 'sm',
    'linkToProfile' => true,
])

<div @class([
    'flex items-center gap-2 mb-3',
    'hover:opacity-90 transition' => $linkToProfile,
])>
    <div class="flex items-center justify-center p-4 w-{{ $size }} h-{{ $size }} text-{{ $textSize }} font-bold text-white bg-primary">
        {{ $user->initials() }}
    </div>
    <div>
        @if($showName)
            @if ($linkToProfile)
                <a wire:navigate href="{{ route('profile.show', $user->username) }}" class="block">
                    <p class="text-sm font-mono font-bold text-primary-800">{{ $user->name }}</p>
                    <p class="text-xs font-mono text-subtitle">{{ '@' . $user->username }}</p>
                </a>
            @else
                <p class="text-sm font-mono font-bold text-primary-800">{{ $user->name }}</p>
                <p class="text-xs font-mono text-subtitle">{{ '@' . $user->username }}</p>
            @endif
        @endif
    </div>
</div>
