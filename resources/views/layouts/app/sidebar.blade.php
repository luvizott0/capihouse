<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900" x-data="{ sidebarOpen: false }">
        <!-- Sidebar for Desktop -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 transition-transform transform bg-zinc-50 dark:bg-zinc-950 border-e border-zinc-200 dark:border-zinc-800 lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between h-16 px-4 border-b border-zinc-200 dark:border-zinc-800">
                    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                    <button @click="sidebarOpen = false" class="lg:hidden text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Sidebar Nav -->
                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    <div class="mb-4">
                        <h3 class="px-2 mb-2 text-xs font-semibold tracking-wider uppercase text-zinc-500 dark:text-zinc-400">
                            {{ __('Platform') }}
                        </h3>
                        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-zinc-200 dark:bg-zinc-800 text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                <div class="px-4 py-4 border-t border-zinc-200 dark:border-zinc-800">
                    <div class="space-y-1">
                        <a href="https://github.com/laravel/livewire-starter-kit" target="_blank" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                            {{ __('Repository') }}
                        </a>
                        <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            {{ __('Documentation') }}
                        </a>
                    </div>
                    <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        <x-desktop-user-menu class="w-full" :name="auth()->user()->name" />
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay for Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-zinc-900 bg-opacity-50 lg:hidden" x-cloak></div>

        <!-- Main Content Wrapper -->
        <div class="lg:pl-64 flex flex-col min-h-screen">
            <!-- Mobile Header -->
            <header class="sticky top-0 z-30 flex items-center justify-between h-16 px-4 bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 lg:hidden">
                <button @click="sidebarOpen = true" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
                <x-app-logo href="{{ route('dashboard') }}" wire:navigate />
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-zinc-500 rounded-full">
                        <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-700 dark:text-zinc-300 font-bold uppercase">
                            {{ auth()->user()->initials() }}
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md shadow-lg py-1 z-50" x-cloak>
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
            </header>

            {{ $slot }}
        </div>

        @persist('toast')
            <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2">
                <!-- Standard Toast Implementation would go here -->
            </div>
        @endpersist
    </body>
</html>
