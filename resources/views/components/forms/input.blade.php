@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'max' => null,
    'required' => false,
    'xs' => false,
    'sm' => false,
    'md' => false,
    'lg' => false,
    'placeholder' => null,
    'showErrors' => true,
    'iconRight' => null,
    'iconAction' => null,
])

@php($name ??= $attributes->wire('model')->value() ?? $attributes->get('name'))
@php($id ??= $name)

<div>
    @if($label)
        <p class="text-sm my-1">{{ $label }}</p>
    @endif
    <input
        {{
            $attributes->merge([
                'id' => $id,
                'name' => $name,
                'type' => 'text',
                'placeholder' => $placeholder,
                'required' => $required,
            ])->class([
                'w-full resize-none overflow-hidden py-1.5 px-2.5 text-sm border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400',
                'text-xs' => $xs,
                'text-sm' => $sm,
                'text-base' => $md,
                'text-lg' => $lg,
            ])
        }}
    />

    <div class="flex w-full items-start">
        <div>
            @error($name)
            @if($showErrors)
                <p class="text-xs text-red-600">{{ $message }}</p>
            @endif
            @enderror
        </div>

        @if($max)
            <p class="ml-auto text-end text-xs font-light">
                <span x-text="textLength"></span>/{{ $max }}
            </p>
        @endif
    </div>
</div>
