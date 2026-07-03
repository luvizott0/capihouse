@props([
    'target',
    'buttonLabel' => '🙂',
    'closeOnSelect' => true,
    'position' => 'left',
])

@php($pickerPositionClass = $position === 'right' ? 'right-0' : 'left-0')

<div
    x-data="emojiPicker({ target: @js($target), closeOnSelect: @js($closeOnSelect), initialLabel: @js($buttonLabel) })"
    @click.outside="close()"
    class="relative inline-block"
    data-emoji-picker="true"
    data-emoji-target="{{ $target }}"
>
    <button
        type="button"
        @click="toggle()"
        class="inline-flex items-center gap-1 px-1 cursor-pointer text-lg font-bold text-primary-700 border border-border bg-primary-100 hover:border-primary-400"
    >
        <span x-text="label">{{ $buttonLabel }}</span>
    </button>

    {{-- Desktop dropdown --}}
    <div
        x-show="open"
        x-cloak
        class="absolute {{ $pickerPositionClass }} z-50 mt-2 hidden sm:block"
        wire:ignore
    >
        <div class="p-1 bg-white border rounded-sm border-border shadow-lg">
            <div x-ref="picker"></div>
        </div>
    </div>

    {{-- Mobile bottom sheet --}}
    <div
        x-show="open"
        x-cloak
        class="sm:hidden"
        wire:ignore
    >
        {{-- Backdrop --}}
        <div
            class="fixed inset-0 bg-black/40 z-[90]"
            @click="close()"
        ></div>

        {{-- Sheet --}}
        <div class="fixed bottom-0 inset-x-0 z-[100] bg-white border-t border-border rounded-t-lg shadow-xl">
            <div class="flex items-center justify-between px-4 py-2 border-b border-border">
                <span class="text-sm font-semibold text-primary-800">{{ __('Selecionar emoji') }}</span>
                <button type="button" @click="close()" class="text-primary-600 hover:text-primary-900 cursor-pointer text-xl leading-none">&times;</button>
            </div>
            <div class="overflow-y-auto max-h-72">
                <div x-ref="pickerMobile"></div>
            </div>
        </div>
    </div>
</div>

