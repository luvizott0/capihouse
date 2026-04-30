@props([
    'media' => [],
    'multiple' => false,
    'accept' => 'image/*,video/*',
    'label' => '[ Fotos / Vídeos ]',
    'removeAction' => 'removeMedia',
    'labelAlt' => null,
])

@php($name = $attributes->wire('model')->value())

<div>
    @if($labelAlt)
        <p class="text-sm my-1">{{ $labelAlt }}</p>
    @else

    @endif
    <div class="flex items-center gap-2">
        <label class="cursor-pointer w-full place-content-center flex items-center gap-2 px-3 py-2 text-xs font-medium border border-border bg-primary-100 hover:bg-primary-200 text-subtitle transition-colors">
            <input type="file" class="hidden" {{ $attributes }} @if($multiple) multiple @endif accept="{{ $accept }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <span>{{ $label }}</span>
        </label>

        <div wire:loading wire:target="{{ $name }}">
            <span class="text-[10px] animate-pulse uppercase">Enviando...</span>
        </div>
    </div>

    @error($name . '.*')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror

    @if (!empty($media))
        <div class="grid grid-cols-4 gap-2 mt-2">
            @foreach ($media as $index => $file)
                <div class="relative aspect-square border border-border bg-primary-100 group">
                    @if (method_exists($file, 'getMimeType') && str_starts_with($file->getMimeType(), 'image'))
                        <img src="{{ $file->temporaryUrl() }}" class="object-cover w-full h-full">
                    @elseif(method_exists($file, 'getMimeType') && str_starts_with($file->getMimeType(), 'video'))
                        <div class="flex flex-col items-center justify-center w-full h-full text-primary-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <span class="text-[10px] uppercase mt-1 text-center">Vídeo</span>
                        </div>
                    @else
                         <div class="flex flex-col items-center justify-center w-full h-full text-primary-600">
                             <span class="text-[10px] uppercase mt-1 text-center">Arquivo</span>
                         </div>
                    @endif

                    <button
                        type="button"
                        wire:click="{{ $removeAction }}({{ $index }})"
                        class="absolute -top-1 -right-1 bg-red-600 text-white w-5 h-5 flex items-center justify-center text-xs rounded-none border border-black hover:bg-red-700 transition-colors"
                    >
                        ×
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
