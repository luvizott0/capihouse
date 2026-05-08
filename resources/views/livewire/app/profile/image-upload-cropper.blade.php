@php
    $aspectRatio = $type === 'avatar' ? 1 : 3;
    $labelType = $type === 'avatar' ? __('foto de perfil') : __('banner');
    $currentUrl = $type === 'avatar' ? $user->avatar_url : $user->banner_url;
@endphp

<div
    x-data="imageCropper({{ $aspectRatio }})"
    x-on:profile::close-{{ $type }}-modal.window="reset()"
>
    {{-- Hidden file input --}}
    <input
        type="file"
        accept="image/*"
        class="hidden"
        x-ref="fileInput"
        x-on:change="onFileSelected($event)"
    >

    {{-- Current image preview --}}
    @if ($currentUrl)
        <div class="mb-3">
            <p class="text-xs text-subtitle mb-1 uppercase tracking-wide">{{ __('Atual') }}</p>
            <img
                src="{{ $currentUrl }}"
                alt="{{ $labelType }}"
                class="border border-border object-cover {{ $type === 'avatar' ? 'w-24 h-24' : 'w-full h-20' }}"
            >
        </div>
    @endif

    {{-- Cropper area (shown after file selected) --}}
    <div x-show="imageSrc" x-cloak class="mb-3 border border-border overflow-hidden">
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

    {{-- Actions --}}
    <div class="flex items-center gap-2">
        <button
            type="button"
            class="btn-outline text-xs"
            x-on:click="$refs.fileInput.click()"
        >
            {{ __('Selecionar imagem') }}
        </button>

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
    Alpine.data('imageCropper', (aspectRatio) => ({
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
