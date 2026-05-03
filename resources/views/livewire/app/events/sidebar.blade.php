<aside class="border rounded-sm border-border bg-white max-h-[calc(100vh-210px)] overflow-y-auto sticky top-[180px]">
    <div class="flex items-center justify-between px-4 py-3 border-b border-primary-100">
        <h2 class="text-sm font-bold uppercase text-primary-800">{{ __('Próximos Eventos') }}</h2>
        <a wire:navigate href="{{ route('events') }}" class="text-xs font-bold text-primary-700 hover:underline">{{ __('Ver todos') }}</a>
    </div>

    <div class="p-3 space-y-2">
        @forelse ($events as $event)
            <a href="#" class="flex items-center gap-3 px-2 py-2 rounded-sm bg-primary-50 hover:bg-primary-100">
                <div class="flex-shrink-0 w-10 h-10 overflow-hidden rounded-xs bg-primary-200">
                    @if($event->media)
                        <img src="{{ $event->getImage() }}" alt="{{ $event->name }}" class="object-cover w-full h-full" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-primary-100 text-primary-400">
                            <x-icons.outline.calendar class="w-5 h-5" />
                        </div>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold truncate text-primary-800" title="{{ $event->name }}">
                        {{ $event->name }}
                    </p>
                    <div class="flex items-center gap-1 text-[10px] text-subtitle font-medium">
                        <x-icons.outline.calendar class="w-3 h-3" />
                        <span>{{ $event->date->translatedFormat('d M, H:i') }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center py-8 text-center px-4">
                <div class="p-3 bg-primary-50 rounded-full mb-2">
                    <x-icons.outline.x-circle class="w-6 h-6 text-primary-300" />
                </div>
                <p class="text-xs font-medium text-primary-500 italic">{{ __('Nenhum evento próximo.') }}</p>
            </div>
        @endforelse
    </div>
</aside>
