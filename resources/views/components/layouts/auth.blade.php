<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        @keyframes marquee {
            0% { transform: translateX(80%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee { animation: marquee 12s linear infinite; }
    </style>
</head>
<body class="font-sans bg-primary-50">
    <x-dev.bar />

    <div x-data="{ usersOpen: false }" class="min-h-screen pb-20 md:pb-0">
        {{-- Top Header --}}
        <header class="sticky top-0 z-50 px-4 py-3 bg-white border-b border-primary-200">
            <div class="mx-auto w-full max-w-7xl">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('capihouse-logo.png') }}" alt="CapiHouse" class="w-8 h-8">
                        <span class="text-lg font-bold font-mono text-primary-800">CapiHouse</span>
                    </div>
                    <div class="overflow-hidden max-w-[65%]">
                        <span class="inline-block text-sm font-bold whitespace-nowrap text-primary animate-marquee">
                            ★ {{ __('Ola, :name!', ['name' => auth()->user()->name]) }} ★ {{ __('Explore o CapiHouse') }} ★
                        </span>
                    </div>
                </div>

                <livewire:app.layout.search-bar />

                {{-- Desktop Navigation --}}
                <nav class="hidden md:flex gap-2 mt-4" data-test="desktop-top-navigation">
                    <livewire:app.navigation.menu desktop />
                </nav>
            </div>
        </header>

        <div class="mx-auto flex w-full max-w-7xl gap-4 px-2 md:px-4">
            <aside class="hidden md:flex md:w-72 md:shrink-0 md:flex-col md:pt-4">
                <livewire:app.profile.sidebar />
            </aside>

            <main class="flex-1 min-w-0 overflow-hidden pb-6">
                {{ $slot }}
            </main>

            <aside class="hidden md:flex md:w-72 md:shrink-0 md:flex-col md:pt-4" data-test="desktop-users-sidebar">
                <livewire:app.users.online-sidebar />
            </aside>
        </div>

        {{-- Mobile Users Button --}}
        <button
            @click="usersOpen = true"
            class="fixed z-40 flex items-center gap-2 px-4 py-2 text-xs font-bold text-white uppercase rounded-sm shadow-lg md:hidden right-4 bottom-24 bg-primary"
            type="button"
            data-test="mobile-users-toggle"
        >
            {{ __('Usuarios') }}
        </button>

        {{-- Mobile Users Drawer --}}
        <div x-show="usersOpen" x-cloak class="fixed inset-0 z-50 md:hidden" data-test="mobile-users-drawer">
            <div class="absolute inset-0 bg-black/40" @click="usersOpen = false"></div>
            <livewire:app.users.online-sidebar mobile />
        </div>

        {{-- Mobile Bottom Navigation --}}
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-primary-200 md:hidden" data-test="mobile-bottom-navigation">
            <livewire:app.navigation.menu />
        </nav>
    </div>

    @livewireScripts
</body>
</html>
