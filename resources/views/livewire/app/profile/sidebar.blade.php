<aside class="sticky top-[180px] border rounded-sm border-border bg-white p-4" data-test="desktop-profile-sidebar">
    <x-user-info />

    <div class="pt-3 mt-3 border-t border-primary-100">
        <h2 class="text-xs font-bold tracking-wider uppercase text-primary-700">{{ __('Navegacao') }}</h2>
        <div class="flex flex-col gap-2 mt-3 text-sm font-semibold">
            <a wire:navigate href="{{ route('feed') }}" class="text-primary-700 hover:text-primary">{{ __('Feed') }}</a>
            <a wire:navigate href="{{ route('events') }}" class="text-primary-700 hover:text-primary">{{ __('Eventos') }}</a>
            <a wire:navigate href="{{ route('catalog') }}" class="text-primary-700 hover:text-primary">{{ __('Acervo') }}</a>
            <a wire:navigate href="{{ route('profile') }}" class="text-primary-700 hover:text-primary">{{ __('Perfil') }}</a>
        </div>
    </div>
</aside>
