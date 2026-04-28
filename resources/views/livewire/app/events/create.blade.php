<div>
    <x-modal
        open-event="event::create"
        close-event="event::close-modal"
        title="Criar Evento"
        max-width="lg"
    >
        <div>
            <x-forms.comment-field
                wire:model="content"
                placeholder="Nome do evento..."
                max="150"
            />

            <div class="flex place-content-end items-center gap-2 mt-6">
                <button
                    type="button"
                    class="btn-primary"
                    wire:click="createPost"
                >
                    [ Criar ]
                </button>
            </div>
        </div>
    </x-modal>
</div>
