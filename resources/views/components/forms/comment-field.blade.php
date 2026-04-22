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
    'counterPosition' => 'bottom',
])

@php($name ??= $attributes->wire('model')->value() ?? $attributes->get('name'))
@php($id ??= $name)
@php($counterPosition = in_array($counterPosition, ['bottom', 'side'], true) ? $counterPosition : 'bottom')

<div
    x-data="{
        textLength: 0,
        resizeObserver: null,
        visibilityObserver: null,
        resize() {
            const el = this.$refs.textarea;
            if (!el) return;

            el.style.height = '0px';
            const lineHeight = Number.parseFloat(window.getComputedStyle(el).lineHeight) || 20;
            const minHeight = lineHeight * Number(el.getAttribute('rows') || 1);
            el.style.height = `${Math.max(el.scrollHeight, minHeight)}px`;

            this.textLength = (el.value || '').length;
        },
        init() {
            this.$nextTick(() => {
                this.resize();

                const el = this.$refs.textarea;
                if (!el || !window.ResizeObserver) return;

                this.resizeObserver = new ResizeObserver(() => this.resize());
                this.resizeObserver.observe(el);

                const visibilityRoot = el.closest('[x-show]');
                if (visibilityRoot && window.MutationObserver) {
                    this.visibilityObserver = new MutationObserver(() => {
                        this.$nextTick(() => this.resize());
                    });
                    this.visibilityObserver.observe(visibilityRoot, {
                        attributes: true,
                        attributeFilter: ['style', 'class'],
                    });
                }
            });
        },
        destroy() {
            if (this.resizeObserver) {
                this.resizeObserver.disconnect();
                this.resizeObserver = null;
            }

            if (this.visibilityObserver) {
                this.visibilityObserver.disconnect();
                this.visibilityObserver = null;
            }
        }
    }"
    x-init="init()"
>
    @if($label)
        <p class="text-sm my-1">{{ $label }}</p>
    @endif

    @if($max && $counterPosition === 'side')
        <div class="flex items-center gap-1">
            <textarea
                x-ref="textarea"
                wire:ignore.self
                @input="resize()"
                @focus="$nextTick(() => resize())"
                @blur="$nextTick(() => resize())"
                @change="$nextTick(() => resize())"
                rows="1"
                {{
                    $attributes->merge([
                        'id' => $id,
                        'name' => $name,
                        'placeholder' => $placeholder,
                        'required' => $required,
                        'maxlength' => $max,
                    ])->class([
                        'w-full resize-none overflow-hidden py-1.5 px-2.5 text-sm border border-border bg-primary-100 focus:outline-none focus:border-primary-400',
                        'text-xs' => $xs,
                        'text-sm' => $sm,
                        'text-base' => $md,
                        'text-lg' => $lg,
                    ])
                }}
            ></textarea>

            <p class="shrink-0 pt-1 text-end text-xs font-light">
                <span x-text="textLength"></span>/{{ $max }}
            </p>
        </div>
    @else
        <textarea
            x-ref="textarea"
            wire:ignore.self
            @input="resize()"
            @focus="$nextTick(() => resize())"
            @blur="$nextTick(() => resize())"
            @change="$nextTick(() => resize())"
            rows="1"
            {{
                $attributes->merge([
                    'id' => $id,
                    'name' => $name,
                    'placeholder' => $placeholder,
                    'required' => $required,
                ])->class([
                    'w-full resize-none overflow-hidden py-1.5 px-2.5 text-sm border border-border bg-primary-100 focus:outline-none focus:border-primary-400',
                    'text-xs' => $xs,
                    'text-sm' => $sm,
                    'text-base' => $md,
                    'text-lg' => $lg,
                ])
            }}
        ></textarea>
    @endif

    <div class="flex w-full items-start">
        <div>
            @isset($errors)
                @error($name)
                @if($showErrors)
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @endif
                @enderror
            @endisset
        </div>

        @if($max && $counterPosition === 'bottom')
            <p class="ml-auto text-end text-xs font-light">
                <span x-text="textLength"></span>/{{ $max }}
            </p>
        @endif
    </div>
</div>
