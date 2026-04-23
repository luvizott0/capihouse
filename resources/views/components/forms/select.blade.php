@props([
    'name',
    'label' => null,
    'options' => [],
    'placeholder' => null,
    'required' => false,
    'xs' => false,
    'sm' => false,
    'md' => true,
    'lg' => false,
    'xl' => false,
    'clearable' => false,
    'showErrors' => false,
])

@php($name ??= $attributes->wire('model')->value() ?? $attributes->get('name'))
@php($id ??= $name)

<div x-data="{
    selected: @if($name) @entangle($name).live @else '' @endif,
    hasValue() {
        return this.selected && this.selected !== '';
    }
}">
    @if($label)
        <p class="text-sm my-1">{{ $label }}</p>
    @endif

    <div class="relative w-full">
        <select
            {{
             $attributes->merge([
                 'id' => $id,
                 'name' => $name,
                 'type' => 'text',
                 'placeholder' => $placeholder,
                 'required' => $required,
             ])->class([
                 'w-full cursor-pointer appearance-none resize-none overflow-hidden py-1.5 pl-2.5 pr-6 text-sm border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400',
                 'text-xs' => $xs,
                 'text-sm' => $sm,
                 'text-base' => $md,
                 'text-lg' => $lg,
             ])
         }}
        >
            {{ $slot }}
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-0 pl-4 flex items-center px-2">
            <div x-show="hasValue()" class="transition-opacity duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
        </div>
    </div>

        @error($name)
            @if($showErrors)
                <p class="text-xs text-red-600">{{ $message }}</p>
            @endif
        @enderror
</div>
