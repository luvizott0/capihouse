<aside @class([
    'border rounded-sm border-border bg-white',
    'max-h-[calc(100vh-210px)] overflow-y-auto sticky top-[180px]' => !$mobile,
    'absolute right-0 top-0 h-full w-72 border-l rounded-none' => $mobile,
])>
    <div class="flex items-center justify-between px-4 py-3 border-b border-primary-100">
        <h2 class="text-sm font-bold uppercase text-primary-800">{{ __('Usuarios do site') }}</h2>
        @if ($mobile)
            <button type="button" @click="usersOpen = false" class="text-sm font-bold text-primary-700">{{ __('Fechar') }}</button>
        @endif
    </div>

    <div @class([
        'p-3 space-y-2',
        'overflow-y-auto h-[calc(100%-53px)]' => $mobile,
    ])>
        @forelse ($siteUsers as $siteUser)
            <a wire:navigate href="{{ route('profile.show', $siteUser->username) }}" class="flex items-center justify-between gap-3 px-2 py-2 rounded-sm bg-primary-50 hover:bg-primary-100">
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate text-primary-800">{{ $siteUser->name }}</p>
                    <p class="text-xs truncate text-subtitle">{{ '@' . $siteUser->username }}</p>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold uppercase rounded-sm {{ in_array($siteUser->id, $onlineUserIds) ? 'bg-emerald-100 text-emerald-700' : 'bg-zinc-200 text-zinc-600' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ in_array($siteUser->id, $onlineUserIds) ? 'bg-emerald-500' : 'bg-zinc-500' }}"></span>
                    {{ in_array($siteUser->id, $onlineUserIds) ? __('Online') : __('Offline') }}
                </span>
            </a>
        @empty
            <p class="px-2 py-6 text-sm text-center text-primary-500">{{ __('Nenhum usuario encontrado.') }}</p>
        @endforelse
    </div>
</aside>
