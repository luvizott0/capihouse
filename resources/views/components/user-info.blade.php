@props([
    'user' => auth()->user(),
    'size' => 10,
    'showName' => true,
    'textSize' => 'sm',
    'linkToProfile' => true,
])

<div {{ $attributes->class([
    'flex items-center gap-2 mb-3',
    'hover:opacity-90 transition' => $linkToProfile,
]) }}>
    <div class="flex-shrink-0 w-{{ $size }} h-{{ $size }} overflow-hidden">
        @if (filled($user->avatar_url))
            <img
                src="{{ e($user->avatar_url) }}"
                alt="{{ $user->name }}"
                class="w-full h-full object-cover"
            />
        @else
            <div class="flex items-center justify-center w-full h-full text-{{ $textSize }} font-bold text-white bg-primary">
                {{ $user->initials() }}
            </div>
        @endif
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
