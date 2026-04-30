<div class="overflow-hidden border-2 rounded-xs border-border bg-white">
    <div class="flex font-mono items-center justify-between px-4 py-2 text-sm font-bold tracking-wider bg-primary text-white border-b border-border uppercase">
        <span>» {{ $event->name }}</span>
    </div>

    <div>
        <div class="px-4 py-2 gap-4 flex">
            <div>
                @if($event->media)
                    <img src="{{ $event->getImage() }}" alt=""
                         class="object-cover aspect-square max-w-[75px] md:max-w-[100px]" loading="lazy">
                @else
                    <div class="-w-[75px] h-[75px] md:w-[100px] md:h-[100px] bg-primary-200"></div>
                @endif
            </div>
            <div class="w-full flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-4 border-b mb-2 border-border">
                        <div class="flex-1 min-w-0">
                            <x-user-info :user="$event->owner" />
                        </div>
                        <div class="flex flex-col text-left gap-0.5">
                            <span class="text-sm text-primary-800">{{ __('Data') }}: {{ $event->date->format('d/m/Y') }}</span>
                            <span class="text-sm text-primary-800">{{ __('Hora') }}: {{ $event->date->format('H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-sm">{{ __('Convidados:') }}</span>
                        @foreach($this->guests() as $guest)
                            <div class="flex items-center justify-center p-3 w-4 h-4 text-[10px] font-bold text-white bg-primary">
                                {{ $guest->initials() }}
                            </div>
                        @endforeach
                    </div>

                    <button class="btn-primary">{{ __('Detalhes »') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
