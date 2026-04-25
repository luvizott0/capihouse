<div>
    @if($desktop)
        @foreach ($this->menuItems() as $item)
            <a
                wire:navigate
                href="{{ route($item['route']) }}"
                class="px-4 py-2 text-sm font-bold uppercase border rounded-sm transition {{ (data_get($item, 'route') === 'profile' ? request()->routeIs('profile*') : request()->routeIs(data_get($item, 'route'))) ? 'text-white bg-primary border-primary' : 'text-primary-700 bg-primary-100 border-border hover:border-primary-300' }}"
            >
                {{ __($item['label']) }}
            </a>
        @endforeach
    @else
        <div class="grid grid-cols-4 py-2">
            @foreach ($this->menuItems() as $item)
                <a
                    href="{{ route(data_get($item, 'route')) }}"
                    wire:navigate
                    class="flex flex-col items-center gap-1 px-2 py-1 {{ (data_get($item, 'route') === 'profile' ? request()->routeIs('profile*') : request()->routeIs(data_get($item, 'route'))) ? 'text-primary' : 'text-primary-400' }}"
                >
                    <x-dynamic-component
                        :component="data_get($item, 'icon')"
                        @class([
                            'h-5 w-5 shrink-0',
                            'text-primary-800' => (data_get($item, 'route') === 'profile' ? request()->routeIs('profile*') : request()->routeIs(data_get($item, 'route'))),
                        ])
                    />
                    <span
                            @class([
                                'text-xs font-bold uppercase',
                                'text-primary-800' => (data_get($item, 'route') === 'profile' ? request()->routeIs('profile*') : request()->routeIs(data_get($item, 'route'))),
                            ])
                        >
                            {{ __(data_get($item, 'label')) }}
                        </span>
                </a>
            @endforeach
        </div>
    @endif
</div>
