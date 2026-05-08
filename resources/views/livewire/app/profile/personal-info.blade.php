<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="border-2 border-border bg-white">
        <div class="flex font-mono items-center px-4 py-2 text-sm font-bold bg-primary text-white border-b border-border uppercase">
            » {{ __('Sobre mim') }}
        </div>

        <div class="px-4 py-2 flex flex-col gap-3">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs uppercase tracking-wide text-subtitle">{{ __('Bio') }}</span>

                    @if ($isOwner)
                        <button
                            type="button"
                            class="inline-flex items-center justify-center w-7 h-7 border border-border text-primary-800 hover:bg-primary-100"
                            wire:click="startEditingBio"
                            title="{{ __('Editar bio') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931ZM19.5 7.125 16.875 4.5" />
                            </svg>
                        </button>
                    @endif
                </div>

                @if ($isOwner && $isEditingBio)
                    <div class="mt-2 space-y-2">
                        <x-forms.comment-field
                            wire:model="bio"
                            placeholder="{{ __('Diga algo sobre voce...') }}"
                            max="255"
                        />

                        <div class="flex items-center gap-2">
                            <button type="button" class="btn-primary" wire:click="saveBio">[ Salvar ]</button>
                            <button type="button" class="btn-outline text-xs" wire:click="cancelEditingBio">{{ __('Cancelar') }}</button>
                        </div>
                    </div>
                @else
                    <div class="mt-1">
                        <span class="text-sm font-normal text-primary-800">"{{ $user->bio ?? __('Diga algo sobre voce...') }}"</span>
                    </div>
                @endif
            </div>

            <div>
                <div class="flex items-center justify-between gap-2">
                    <div class="flex gap-2 items-center">
                        <x-icons.outline.cake class="w-4 text-primary-800" />
                        <span class="text-xs uppercase tracking-wide text-subtitle">{{ __('Aniversario') }}</span>
                    </div>

                    @if ($isOwner)
                        <button
                            type="button"
                            class="inline-flex items-center justify-center w-7 h-7 border border-border text-primary-800 hover:bg-primary-100"
                            wire:click="startEditingBirth"
                            title="{{ __('Editar aniversario') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931ZM19.5 7.125 16.875 4.5" />
                            </svg>
                        </button>
                    @endif
                </div>

                @if ($isOwner && $isEditingBirth)
                    <div class="mt-2 space-y-2">
                        <input
                            wire:model="birth"
                            type="date"
                            class="w-full resize-none overflow-hidden py-1.5 text-subtitle px-2.5 text-sm border border-border bg-primary-100 focus:outline-none focus:border-primary-400"
                        >

                        @error('birth')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="flex items-center gap-2">
                            <button type="button" class="btn-primary" wire:click="saveBirth">[ Salvar ]</button>
                            <button type="button" class="btn-outline text-xs" wire:click="cancelEditingBirth">{{ __('Cancelar') }}</button>
                        </div>
                    </div>
                @else
                    <div class="mt-1">
                        <span class="text-sm text-primary-800">
                            {{ $user->birth?->format('d/m/Y') ?? __('Aniversario nao informado') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="border-2 border-border bg-white">
        <div class="flex font-mono items-center px-4 py-2 text-sm font-bold bg-primary text-white border-b border-border uppercase">
            » {{ __('Meus interesses') }}
        </div>

        <div class="px-4 py-2 space-y-2">
            @if ($isOwner)
                <div class="flex items-start gap-2">
                    <x-forms.input
                        wire:model="interest"
                        placeholder="interesse"
                        @keydown.enter="$wire.addInterest()"
                    />

                    <button
                        type="button"
                        class="btn-primary shrink-0"
                        wire:click="addInterest"
                        aria-label="{{ __('Adicionar interesse') }}"
                    >
                        +
                    </button>
                </div>
            @endif

            @if ($user->interests->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach ($user->interests as $userInterest)
                        <div class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-white bg-primary-600">
                            <span>{{ $userInterest->name }}</span>

                            @if ($isOwner)
                                <button
                                    type="button"
                                    wire:click="removeInterest({{ $userInterest->id }})"
                                    class="ml-1 hover:opacity-75"
                                >
                                    ×
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-subtitle">{{ __('Nenhum interesse cadastrado ainda.') }}</p>
            @endif
        </div>
    </div>
</div>
