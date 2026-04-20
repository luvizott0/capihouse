@props([
    'show' => false,
    'title' => null,
    'openEvent' => 'open-modal',
    'closeEvent' => 'close-modal',
    'maxWidth' => 'sm',
    'zIndex' => 50,
    '$actionLabel' => 'Salvar',
    'action' => '',
])

@php
    $maxWidthClass = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth] ?? 'max-w-sm';
@endphp

<div
    x-data="{ show: @js($show) }"
    x-show="show"
    x-on:keydown.escape.window="$dispatch('{{ $closeEvent }}');show = false"
    x-on:{{ $closeEvent }}.window="show = false"
    x-on:{{ $openEvent }}.window="show = true"
    class="fixed inset-0 overflow-hidden"
    style="z-index: {{ $zIndex }}"
    x-cloak
>
    <div
        x-show="show"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        @click="$dispatch('{{ $closeEvent }}');show = false"
    ></div>

    <div
        x-show="show"
        x-transition:enter="transition-all ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition-all ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click.self="$dispatch('{{ $closeEvent }}');$dispatch('cancel'); show = false"
    >
        <div class="bg-white border-border rounded-sm {{ $maxWidthClass }} w-full relative shadow-xl" @click.stop>
            <div class="bg-primary px-4 py-2 flex justify-between items-center">
                <span class="text-white camelcase font-bold">» {{ $title }}</span>
                <button class="text-white cursor-pointer" @click="show = false">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                {{ $slot }}
            </div>
            <div class="w-full flex px-4 py-2 place-content-end">
                <button
                    type="button"
                    class="btn-primary"
                    @if($action) wire:click="{{ $action }}" @endif
                >
                    [ {{ $actionLabel }} ]
                </button>
            </div>
        </div>
    </div>
</div>
