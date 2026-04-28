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
                max="150"
            />

            <div>
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
