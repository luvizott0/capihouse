<div>
    <x-modal
        open-event="post::edit"
        close-event="post::close-edit-modal"
        title="Editar Post"
        max-width="lg"
    >
        <div>
            <x-user-info/>

            <x-forms.comment-field
                wire:model="content"
                placeholder="Atualize seu post"
                max="2000"
            />

            <p class="text-sm my-1">{{ __('Midias atuais') }}</p>
            @if (count($existingMedia) > 0)
                <div class="grid grid-cols-4 gap-2 mt-2">
                    @foreach ($existingMedia as $item)
                        <div class="relative aspect-square border border-border bg-primary-100 group">
                            @if ($item['type'] === 'video')
                                <div class="flex flex-col items-center justify-center w-full h-full text-primary-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                    <span class="text-[10px] uppercase mt-1 text-center">Video</span>
                                </div>
                            @else
                                <img src="{{ $item['url'] }}" alt="" class="object-cover w-full h-full" loading="lazy">
                            @endif

                            <button
                                type="button"
                                wire:click="removeExistingMedia({{ $item['id'] }})"
                                class="absolute -top-1 -right-1 bg-red-600 text-white w-5 h-5 flex items-center justify-center text-xs rounded-none border border-black hover:bg-red-700 transition-colors"
                            >
                                x
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-subtitle">{{ __('Nenhuma midia atual no post.') }}</p>
            @endif

            <p class="text-sm my-1 mt-3">{{ __('Adicionar novas fotos e videos') }}</p>
            <x-forms.file-upload
                wire:model="mediaFiles"
                :media="$mediaFiles"
                multiple
            />

            <div class="mt-4">
                <x-forms.input
                    wire:model="hashtag"
                    label="Hashtags"
                    placeholder="Digite aqui e pressione enter..."
                    @keydown.enter="$wire.addHashtag()"
                />

                @if (count($hashtags) > 0)
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($hashtags as $index => $tag)
                            <div class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-white bg-primary-600">
                                <span>#{{ $tag }}</span>
                                <button
                                    type="button"
                                    wire:click="removeHashtag({{ $index }})"
                                    class="ml-1 hover:opacity-75"
                                >
                                    x
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex justify-between items-center gap-2 mt-6">
                <div class="flex gap-2 items-center">
                    <div>
                        <x-forms.emoji-picker target="emoji"/>
                    </div>

                    <x-forms.comment-field
                        wire:model="feeling"
                        placeholder="Me sentindo..."
                        max="10"
                        counter-position="side"
                    />
                </div>

                <button
                    type="button"
                    class="btn-primary"
                    wire:click="savePost"
                >
                    [ Salvar ]
                </button>
            </div>
        </div>
    </x-modal>
</div>
