<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ ($title ?? 'Admin') . ' — CapiHouse Admin' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-primary-50 font-sans">
    <x-dev.bar />

    <div class="min-h-screen flex flex-col">
        {{-- Top Bar --}}
        <header class="border-b-2 border-primary bg-primary text-white">
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('capihouse-logo.png') }}" alt="CapiHouse" class="w-8 h-8">
                    <span class="font-bold tracking-widest uppercase text-sm">CapiHouse Admin</span>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <span class="opacity-75">{{ auth()->user()->name }}</span>
                    <a href="{{ route('feed') }}" class="underline hover:opacity-75">[ Ver site ]</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="underline hover:opacity-75">[ Sair ]</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="flex flex-1 max-w-7xl w-full mx-auto px-4 py-6 gap-6">
            {{-- Sidebar --}}
            <aside class="w-48 shrink-0">
                <nav class="border-2 border-primary overflow-hidden">
                    <div class="px-3 py-2 bg-primary text-white text-xs font-bold uppercase tracking-wider">
                        » Menu
                    </div>
                    <ul class="bg-white divide-y divide-primary-100">
                        <li>
                            <a
                                href="{{ route('admin.users') }}"
                                class="block px-3 py-2 text-sm text-primary-800 font-bold hover:bg-primary-50 {{ request()->routeIs('admin.users') ? 'bg-primary-100' : '' }}"
                            >
                                Usuários
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 min-w-0">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

