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
    @php
        $navigationItems = [
            ['route' => 'feed', 'label' => 'Feed'],
            ['route' => 'perfil', 'label' => 'Perfil'],
            ['route' => 'eventos', 'label' => 'Eventos'],
            ['route' => 'acervo', 'label' => 'Acervo'],
        ];

        $mobilePlaceholders = [
            'feed' => __('Pesquisar posts...'),
            'perfil' => __('Pesquisar posts deste perfil...'),
            'eventos' => __('Pesquisar eventos...'),
            'acervo' => __('Pesquisar midias do acervo...'),
        ];

        $currentRouteName = request()->route()?->getName() ?? 'feed';
        $currentSearchPlaceholder = $mobilePlaceholders[$currentRouteName] ?? __('Pesquisar...');
        $onlineUserIds = \Illuminate\Support\Facades\DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->pluck('user_id')
            ->all();

        $siteUsers = \App\Models\User::query()
            ->select(['id', 'name', 'username'])
            ->orderBy('name')
            ->get();
    @endphp

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

                {{-- Search Bar --}}
                <div class="flex gap-2 mt-4" data-test="global-search-bar">
                    <select
                        class="hidden md:block py-2 px-3 text-sm font-medium text-primary-700 border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400"
                        aria-label="{{ __('Selecionar tipo de pesquisa') }}"
                        data-test="desktop-search-scope"
                    >
                        @foreach ($navigationItems as $item)
                            <option value="{{ $item['route'] }}" @selected($currentRouteName === $item['route'])>
                                {{ __($item['label']) }}
                            </option>
                        @endforeach
                    </select>

                    <div class="relative w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute w-5 h-5 left-3 top-2.5 text-subtitle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input
                            type="text"
                            placeholder="{{ $currentSearchPlaceholder }}"
                            class="w-full py-2 pl-10 pr-4 text-sm font-medium text-subtitle border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400"
                            readonly
                        />
                    </div>

                    <livewire:auth.logout />
                </div>

                {{-- Desktop Navigation --}}
                <nav class="hidden md:flex gap-2 mt-4" data-test="desktop-top-navigation">
                    @foreach ($navigationItems as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            class="px-4 py-2 text-sm font-bold uppercase border rounded-sm transition {{ request()->routeIs($item['route']) ? 'text-white bg-primary border-primary' : 'text-primary-700 bg-primary-100 border-border hover:border-primary-300' }}"
                        >
                            {{ __($item['label']) }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </header>

        <div class="mx-auto flex w-full max-w-7xl gap-4 px-2 md:px-4">
            {{-- Main Content --}}
            <main class="flex-1 min-w-0 overflow-hidden pb-6">
                {{ $slot }}
            </main>

            {{-- Desktop Users Sidebar --}}
            <aside class="hidden md:flex md:w-72 md:shrink-0 md:flex-col md:pt-4" data-test="desktop-users-sidebar">
                <div class="sticky top-[180px] border rounded-sm border-border bg-white max-h-[calc(100vh-210px)] overflow-y-auto">
                    <div class="px-4 py-3 border-b border-primary-100">
                        <h2 class="text-sm font-bold uppercase text-primary-800">{{ __('Usuarios do site') }}</h2>
                    </div>

                    <div class="p-3 space-y-2">
                        @forelse ($siteUsers as $siteUser)
                            <div class="flex items-center justify-between gap-3 px-2 py-2 rounded-sm bg-primary-50">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold truncate text-primary-800">{{ $siteUser->name }}</p>
                                    <p class="text-xs truncate text-subtitle">{{ '@' . $siteUser->username }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold uppercase rounded-sm {{ in_array($siteUser->id, $onlineUserIds) ? 'bg-emerald-100 text-emerald-700' : 'bg-zinc-200 text-zinc-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ in_array($siteUser->id, $onlineUserIds) ? 'bg-emerald-500' : 'bg-zinc-500' }}"></span>
                                    {{ in_array($siteUser->id, $onlineUserIds) ? __('Online') : __('Offline') }}
                                </span>
                            </div>
                        @empty
                            <p class="px-2 py-6 text-sm text-center text-primary-500">{{ __('Nenhum usuario encontrado.') }}</p>
                        @endforelse
                    </div>
                </div>
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

            <aside class="absolute right-0 top-0 h-full w-72 border-l border-border bg-white">
                <div class="flex items-center justify-between px-4 py-3 border-b border-primary-100">
                    <h2 class="text-sm font-bold uppercase text-primary-800">{{ __('Usuarios do site') }}</h2>
                    <button type="button" @click="usersOpen = false" class="text-sm font-bold text-primary-700">{{ __('Fechar') }}</button>
                </div>

                <div class="p-3 space-y-2 overflow-y-auto h-[calc(100%-53px)]">
                    @forelse ($siteUsers as $siteUser)
                        <div class="flex items-center justify-between gap-3 px-2 py-2 rounded-sm bg-primary-50">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold truncate text-primary-800">{{ $siteUser->name }}</p>
                                <p class="text-xs truncate text-subtitle">{{ '@' . $siteUser->username }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold uppercase rounded-sm {{ in_array($siteUser->id, $onlineUserIds) ? 'bg-emerald-100 text-emerald-700' : 'bg-zinc-200 text-zinc-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ in_array($siteUser->id, $onlineUserIds) ? 'bg-emerald-500' : 'bg-zinc-500' }}"></span>
                                {{ in_array($siteUser->id, $onlineUserIds) ? __('Online') : __('Offline') }}
                            </span>
                        </div>
                    @empty
                        <p class="px-2 py-6 text-sm text-center text-primary-500">{{ __('Nenhum usuario encontrado.') }}</p>
                    @endforelse
                </div>
            </aside>
        </div>

        {{-- Mobile Bottom Navigation --}}
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-primary-200 md:hidden" data-test="mobile-bottom-navigation">
            <div class="grid grid-cols-4 py-2">
                @foreach ($navigationItems as $item)
                    <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-1 px-2 py-1 {{ request()->routeIs($item['route']) ? 'text-primary' : 'text-primary-400' }}">
                        <span class="text-xs font-bold uppercase">{{ __($item['label']) }}</span>
                    </a>
                @endforeach
            </div>
        </nav>
    </div>

    @livewireScripts
</body>
</html>
