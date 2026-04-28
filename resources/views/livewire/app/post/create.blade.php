<div>
    <x-modal
        open-event="post::create"
        close-event="post::close-modal"
        title="Criar Post"
        max-width="lg"
    >
        <div>
            <x-user-info/>

            <x-forms.comment-field
                wire:model="content"
                placeholder="O que você está pensando?"
                max="2000"
            />

            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <label class="cursor-pointer flex items-center gap-2 px-3 py-1 text-xs font-medium border border-border bg-primary-100 hover:bg-primary-200 text-primary-600 transition-colors">
                        <input type="file" class="hidden" wire:model="mediaFiles" multiple accept="image/*,video/*">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <span>[ Fotos / Vídeos ]</span>
                    </label>

                    <div wire:loading wire:target="mediaFiles">
                        <span class="text-[10px] animate-pulse uppercase">Enviando...</span>
                    </div>
                </div>

                @error('mediaFiles.*')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror

                @if ($mediaFiles)
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        @foreach ($mediaFiles as $index => $file)
                            <div class="relative aspect-square border border-border bg-primary-100 group">
                                @if (str_starts_with($file->getMimeType(), 'image'))
                                    <img src="{{ $file->temporaryUrl() }}" class="object-cover w-full h-full">
                                @else
                                    <div class="flex flex-col items-center justify-center w-full h-full text-primary-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                        </svg>
                                        <span class="text-[10px] uppercase mt-1 text-center">Vídeo</span>
                                    </div>
                                @endif

                                <button
                                    type="button"
                                    wire:click="removeMedia({{ $index }})"
                                    class="absolute -top-1 -right-1 bg-red-600 text-white w-5 h-5 flex items-center justify-center text-xs rounded-none border border-black hover:bg-red-700 transition-colors"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <x-forms.input
                    wire:model="hashtag"
                    label="Adicionar Hashtag"
                    placeholder="Digite aqui e pressione enter..."
                    @keydown.enter="$wire.addHashtag()"
                />

                @if (count($hashtags) > 0)
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($hashtags as $index => $tag)
                            <div
                                class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-white rounded-full bg-primary-600">
                                <span>#{{ $tag }}</span>
                                <button
                                    type="button"
                                    wire:click="removeHashtag({{ $index }})"
                                    class="ml-1 hover:opacity-75"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex justify-between items-center gap-2 mt-6">
                <div class="flex gap-2  items-center">
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
                    wire:click="createPost"
                >
                    [ Publicar ]
                </button>
            </div>
        </div>
    </x-modal>
</div>
