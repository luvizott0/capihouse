<div class="px-4 pb-2">
    <div class="mt-4">
        @foreach($comments as $comment)
            <div class="flex items-start gap-1 mb-2">
                <x-user-info size="6" text-size="xs" :show-name="false" :user="$comment->user"/>
                <div class="flex flex-col bg-[#EAE7E1] w-full p-1 px-3 rounded-sm border border-[#D5C5B9]">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-primary-800">{{ $comment->user->name }}</span>

                        @if(auth()->id() === $comment->user_id)
                            <div class="text-end">
                                @if($editingCommentId === $comment->id)
                                    <button wire:click="saveEditing" class="cursor-pointer">
                                        <p class="text-xs text-primary-800 font-bold underline">
                                            Salvar
                                        </p>
                                    </button>
                                    <button wire:click="cancelEditing" class="cursor-pointer">
                                        <p class="text-xs text-primary-800 font-bold underline">
                                            Cancelar
                                        </p>
                                    </button>
                                @else
                                    <button wire:click="startEditing({{ $comment->id }})" class="cursor-pointer">
                                        <p class="text-xs text-primary-800 font-bold underline">
                                            Editar
                                        </p>
                                    </button>
                                    <button wire:click="deleteComment({{ $comment->id }})" class="cursor-pointer">
                                        <p class="text-xs text-primary-800 font-bold underline">
                                            Deletar
                                        </p>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($editingCommentId === $comment->id)
                        <textarea
                            wire:model.live="editingComment"
                            rows="1"
                            class="text-sm mt-1 px-2 py-1 border border-[#D5C5B9] rounded-sm"
                        ></textarea>
                        @error('editingComment')
                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    @else
                        <span class="text-sm break-all">{{ $comment->content }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex items-start gap-2 mt-3">
        <div class="w-full">
            <x-forms.comment-field wire:model="comment" placeholder="{{ __('Deixe um comentário...') }}" max="150"/>
        </div>

        <button wire:click="send" class="bg-primary px-4 pt-0.5 pb-1 cursor-pointer">
            <span class="text-sm text-white">Enviar</span>
        </button>
    </div>
</div>
