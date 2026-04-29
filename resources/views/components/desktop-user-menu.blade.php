<div x-data="{ open: false }" class="relative w-full">
    <button @click="open = !open" class="flex items-center w-full px-2 py-2 text-sm font-medium rounded-md text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition-colors duration-200">
        <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-700 dark:text-zinc-300 font-bold uppercase mr-3">
            {{ auth()->user()->initials() }}
        </div>
        <span class="flex-1 text-left truncate">{{ auth()->user()->name }}</span>
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" class="absolute bottom-full left-0 mb-2 w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md shadow-lg py-1 z-50" x-cloak>
        <div class="px-4 py-2 border-b border-zinc-100 dark:border-zinc-700">
            <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email }}</p>
        </div>
        <a href="{{ route('profile.edit') }}" wire:navigate class="block px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">{{ __('Settings') }}</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</div>
