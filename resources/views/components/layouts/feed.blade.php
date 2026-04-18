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
<body class="flex flex-col h-screen font-mono bg-primary-50">
    {{-- Top Header --}}
    <header class="sticky top-0 z-50 px-4 py-3 bg-white border-b border-primary-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ asset('capihouse-logo.png') }}" alt="CapiHouse" class="w-8 h-8">
                <span class="text-lg font-bold text-primary-800">CapiHouse</span>
            </div>
            <div class="overflow-hidden max-w-[60%]">
            <span class="inline-block text-sm font-bold whitespace-nowrap text-primary animate-marquee">
                ★ {{ __('Bem-vindo de volta, :name!', ['name' => Auth::user()->name]) }} ★
            </span>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="relative mt-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute w-5 h-5 left-3 top-2.5 text-subtitle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input
                type="text"
                placeholder="{{ __('Pesquisar posts...') }}"
                class="w-full py-2 pl-10 pr-4 text-sm font-medium text-subtitle border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400"
                readonly
            />
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 overflow-y-auto pb-20">
        {{ $slot }}
    </main>

    {{-- FAB --}}
    <a href="#" class="fixed z-50 flex items-center justify-center text-2xl text-white rounded-full shadow-lg w-14 h-14 bottom-24 right-4 bg-primary hover:bg-primary-500">+</a>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-primary-200">
        <div class="flex items-center justify-around py-2">
            <a href="{{ route('feed') }}" class="flex flex-col items-center gap-1 px-4 py-1 {{ request()->routeIs('feed') ? 'text-primary' : 'text-primary-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/></svg>
                <span class="text-xs font-bold uppercase">Feed</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 px-4 py-1 text-primary-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-xs font-bold uppercase">Perfil</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 px-4 py-1 text-primary-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-xs font-bold uppercase">Config</span>
            </a>
        </div>
    </nav>
    @livewireScripts
</body>
</html>
