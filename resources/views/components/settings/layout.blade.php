<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <nav class="space-y-1">
            <a href="{{ route('profile.edit') }}" wire:navigate class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile.edit') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' }}">
                {{ __('Profile') }}
            </a>
            <a href="{{ route('security.edit') }}" wire:navigate class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('security.edit') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' }}">
                {{ __('Security') }}
            </a>
            <a href="{{ route('appearance.edit') }}" wire:navigate class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('appearance.edit') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' }}">
                {{ __('Appearance') }}
            </a>
        </nav>
    </div>

    <hr class="w-full border-zinc-200 dark:border-zinc-800 md:hidden my-6" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $heading ?? '' }}</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $subheading ?? '' }}</p>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
