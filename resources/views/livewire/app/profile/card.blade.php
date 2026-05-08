@php
    $bannerStyle = filled($user->banner_url)
        ? "background-image: url('".e($user->banner_url)."'); background-size: cover; background-position: center;"
        : 'background: linear-gradient(90deg, #d6bda2 0%, #e8d3bc 100%);';
@endphp

<div class="overflow-hidden border-2 rounded-sm border-border bg-white">
    <div class="relative w-full aspect-[3/1] border-b border-primary" style="{{ $bannerStyle }}">
        @if ($isOwner)
            <button
                type="button"
                class="absolute top-2 right-2 inline-flex items-center gap-1 px-2 py-1 text-xs bg-white/90 border border-border text-primary-800 hover:bg-white"
                x-on:click="$dispatch('profile::open-banner-modal')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931ZM19.5 7.125 16.875 4.5" />
                </svg>
                {{ __('Editar banner') }}
            </button>
        @endif
    </div>

    <div class="px-4 pb-4">
        <div class="flex flex-wrap items-end justify-between gap-4 -mt-14">
            <div class="flex items-center gap-4">
                <div class="relative">
                    @if (filled($user->avatar_url))
                        <img
                            src="{{ e($user->avatar_url) }}"
                            alt="{{ $user->name }}"
                            class="w-24 h-24 object-cover border-4 shadow-sm border-white"
                        >
                    @else
                        <div class="flex items-center justify-center w-24 h-24 text-2xl font-bold border-4 shadow-sm bg-primary text-white border-white">
                            {{ $user->initials() }}
                        </div>
                    @endif

                    @if ($isOwner)
                        <button
                            type="button"
                            class="absolute -right-2 bottom-2 inline-flex items-center justify-center w-7 h-7 bg-white border border-border text-primary-800 hover:bg-primary-100"
                            title="{{ __('Editar foto de perfil') }}"
                            x-on:click="$dispatch('profile::open-avatar-modal')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931ZM19.5 7.125 16.875 4.5" />
                            </svg>
                        </button>
                    @endif
                </div>

                <div class="pt-14">
                    <h1 class="text-lg md:text-2xl font-bold font-mono text-primary-800">{{ $user->name }}</h1>
                    <p class="text-sm md:text-md font-mono text-primary">{{ '@' . $user->username }}</p>
                    <p class="flex items-center gap-1 text-sm text-subtitle">
                        <span class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                        {{ $isOnline ? __('Online agora') : __('Offline') }}
                    </p>
                </div>
            </div>

            @if ($isOwner)
                <div class="flex items-center gap-2">
                    <x-icons.outline.cogs class="w-5 cursor-pointer" x-on:click="$dispatch('profile::open-settings-modal')" />
                </div>
            @endif
        </div>
    </div>

    @if ($isOwner)
        <x-modal
            open-event="profile::open-banner-modal"
            close-event="profile::close-banner-modal"
            title="Editar banner"
            max-width="lg"
        >
            <livewire:app.profile.image-upload-cropper :user="$user" type="banner" :key="'banner-'.$user->id" />
        </x-modal>

        <x-modal
            open-event="profile::open-avatar-modal"
            close-event="profile::close-avatar-modal"
            title="Editar foto de perfil"
            max-width="lg"
        >
            <livewire:app.profile.image-upload-cropper :user="$user" type="avatar" :key="'avatar-'.$user->id" />
        </x-modal>

        <x-modal
            open-event="profile::open-settings-modal"
            close-event="profile::close-settings-modal"
            title="Configuracoes da conta"
            max-width="md"
            action="saveSettings"
            action-label="Salvar"
        >
            <div class="flex flex-col gap-4">
                <x-forms.input
                    wire:model="settingsName"
                    label="{{ __('Nome') }}"
                    placeholder="{{ __('Seu nome') }}"
                    required
                />
                <x-forms.input
                    wire:model="settingsUsername"
                    label="{{ __('Nome de usuario') }}"
                    placeholder="{{ __('seu.usuario') }}"
                    required
                />

                <div class="border-t border-border pt-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-3">{{ __('Alterar senha') }}</p>
                    <div class="flex flex-col gap-4">
                        <x-forms.input
                            wire:model="settingsCurrentPassword"
                            type="password"
                            label="{{ __('Senha atual') }}"
                            placeholder="{{ __('••••••••') }}"
                        />
                        <x-forms.input
                            wire:model="settingsPassword"
                            type="password"
                            label="{{ __('Nova senha') }}"
                            placeholder="{{ __('••••••••') }}"
                        />
                        <x-forms.input
                            wire:model="settingsPasswordConfirmation"
                            type="password"
                            label="{{ __('Confirmar nova senha') }}"
                            placeholder="{{ __('••••••••') }}"
                        />
                    </div>
                </div>
            </div>
        </x-modal>
    @endif
</div>
