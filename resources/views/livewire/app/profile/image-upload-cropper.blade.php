@php
    $aspectRatio = $type === 'avatar' ? 1 : 3;
    $labelType = $type === 'avatar' ? __('foto de perfil') : __('banner');
    $currentUrl = $type === 'avatar' ? $user->avatar_url : $user->banner_url;
    $alpineComponent = 'imageCropper_' . $type;
@endphp

<div
    x-data="{{ $alpineComponent }}({{ $aspectRatio }})"
    x-on:profile::close-{{ $type }}-modal.window="reset()"
>
    {{-- Current image preview --}}
    @if ($currentUrl)
        <div class="mb-3">
            <p class="text-xs text-subtitle mb-1 uppercase tracking-wide">{{ __('Atual') }}</p>
            <img
                src="{{ $currentUrl }}"
                alt="{{ $labelType }}"
                class="border border-border object-cover {{ $type === 'avatar' ? 'w-24 h-24' : 'w-full aspect-3/1' }}"
            >
        </div>
    @endif

    {{-- Cropper area (shown after file selected) --}}
    <div x-show="imageSrc" x-cloak class="mb-3 border border-border overflow-hidden" style="max-height: 320px;">
        <img
            x-ref="cropperImage"
            x-bind:src="imageSrc"
            alt="{{ __('Imagem para recorte') }}"
            class="max-w-full block"
        >
    </div>

    @error('imageFile')
        <p class="text-xs text-red-600 mb-2">{{ $message }}</p>
    @enderror

    {{-- Dimension hint --}}
    <p class="text-[10px] text-subtitle uppercase tracking-wide mb-2">
        @if ($type === 'avatar')
            {{ __('Recomendado: 512 × 512 px') }}
        @else
            {{ __('Recomendado: 1200 × 400 px (proporção 3:1)') }}
        @endif
    </p>

    {{-- Actions --}}
    <div class="flex items-center gap-2">
        {{-- Styled file input --}}
        <label class="cursor-pointer flex items-center gap-2 px-3 py-2 text-xs font-medium border border-border bg-primary-100 hover:bg-primary-200 text-subtitle transition-colors">
            <input
                type="file"
                accept="image/*"
                class="hidden"
                x-ref="fileInput"
                x-on:change="onFileSelected($event)"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <span>{{ __('Selecionar imagem') }}</span>
        </label>

        <button
            type="button"
            class="btn-primary"
            x-show="imageSrc"
            x-cloak
            x-on:click="cropAndUpload()"
            x-bind:disabled="uploading"
        >
            <span x-show="!uploading">[ {{ __('Salvar') }} ]</span>
            <span x-show="uploading" x-cloak>{{ __('Enviando...') }}</span>
        </button>
    </div>
</div>

@script
<script>
    Alpine.data(@js($alpineComponent), (aspectRatio) => ({
        imageSrc: null,
        cropper: null,
        uploading: false,

        onFileSelected(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageSrc = e.target.result;
                this.$nextTick(() => this.initCropper());
            };
            reader.readAsDataURL(file);
        },

        initCropper() {
            if (this.cropper) {
                this.cropper.destroy();
            }
            this.cropper = new Cropper(this.$refs.cropperImage, {
                aspectRatio: aspectRatio,
                viewMode: 1,
                autoCropArea: 1,
                responsive: true,
                minContainerHeight: 200,
                maxContainerHeight: 300,
            });
        },

        cropAndUpload() {
            if (!this.cropper) return;

            this.uploading = true;

            this.cropper.getCroppedCanvas({
                maxWidth: aspectRatio === 1 ? 512 : 1200,
                maxHeight: aspectRatio === 1 ? 512 : 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                const file = new File([blob], 'image.jpg', { type: 'image/jpeg' });

                $wire.upload('imageFile', file,
                    () => {
                        $wire.save().then(() => {
                            this.uploading = false;
                        });
                    },
                    () => {
                        this.uploading = false;
                    }
                );
            }, 'image/jpeg', 0.88);
        },

        reset() {
            this.imageSrc = null;
            this.uploading = false;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },
    }));
</script>
@endscript
