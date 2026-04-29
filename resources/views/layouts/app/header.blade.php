<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900" x-data="{ mobileMenuOpen: false }">
        <header class="sticky top-0 z-30 w-full bg-zinc-50 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800">
            <div class="container mx-auto px-4 h-16 flex items-center">
                <button @click="mobileMenuOpen = true" class="lg:hidden mr-4 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>

                <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

                <nav class="hidden lg:flex items-center ml-8 space-x-1">
                    <a href="{{ route('dashboard') }}" wire:navigate class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-zinc-200 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' }}">
                        {{ __('Dashboard') }}
                    </a>
                </nav>

                <div class="flex-1"></div>

                <div class="flex items-center space-x-4">
                    <button class="p-2 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200" title="{{ __('Search') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <a href="https://github.com/laravel/livewire-starter-kit" target="_blank" class="hidden lg:block p-2 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200" title="{{ __('Repository') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </a>
                    <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank" class="hidden lg:block p-2 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200" title="{{ __('Documentation') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </a>
                    <x-desktop-user-menu />
                </div>
            </div>
        </header>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 z-40 bg-zinc-900 bg-opacity-50 lg:hidden" x-cloak></div>

        <!-- Mobile Sidebar -->
        <aside x-show="mobileMenuOpen" class="fixed inset-y-0 left-0 z-50 w-64 bg-zinc-50 dark:bg-zinc-950 border-e border-zinc-200 dark:border-zinc-800 lg:hidden" x-cloak>
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between h-16 px-4 border-b border-zinc-200 dark:border-zinc-800">
                    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                    <button @click="mobileMenuOpen = false" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    <div>
                        <h3 class="px-2 mb-2 text-xs font-semibold tracking-wider uppercase text-zinc-500 dark:text-zinc-400">
                            {{ __('Platform') }}
                        </h3>
                        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-zinc-200 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' }}">
                            {{ __('Dashboard') }}
                        </a>
                    </div>
                    <div class="mt-8">
                        <h3 class="px-2 mb-2 text-xs font-semibold tracking-wider uppercase text-zinc-500 dark:text-zinc-400">
                            {{ __('Links') }}
                        </h3>
                        <a href="https://github.com/laravel/livewire-starter-kit" target="_blank" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white">
                            {{ __('Repository') }}
                        </a>
                        <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white">
                            {{ __('Documentation') }}
                        </a>
                    </div>
                </nav>
            </div>
        </aside>

        <main>
            {{ $slot }}
        </main>

        @persist('toast')
            <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>
        @endpersist
    </body>
</html>
