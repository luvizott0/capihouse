@props([
    'target',
    'buttonLabel' => 'Emoji',
    'closeOnSelect' => true,
    'position' => 'left',
])

@php($pickerPositionClass = $position === 'right' ? 'right-0' : 'left-0')

<div
    x-data="emojiPicker({ target: @js($target), closeOnSelect: @js($closeOnSelect) })"
    @click.outside="close()"
    class="relative inline-block"
    data-emoji-picker="true"
    data-emoji-target="{{ $target }}"
>
    <button
        type="button"
        @click="toggle()"
        class="inline-flex items-center gap-1 px-2 py-1.5 cursor-pointer text-xs font-bold text-primary-700 border border-border bg-primary-100 hover:border-primary-400"
    >
        🙂
    </button>

    <div
        x-show="open"
        x-cloak
        class="absolute {{ $pickerPositionClass }} z-50 mt-2"
        wire:ignore
    >
        <div class="p-1 bg-white border rounded-sm border-border shadow-lg">
            <div x-ref="picker"></div>
        </div>
    </div>
</div>

