<div>
    <x-modal
        open-event="event::create"
        close-event="event::close-modal"
        title="Criar Evento"
        max-width="lg"
    >
        <form wire:submit="create" class="space-y-6">
            <x-forms.input wire:model="name" label="Nome do evento" placeholder="Ex: Aniversário da Rogéria" />

            <x-forms.input wire:model="description" label="Descrição" placeholder="Conte mais sobre o evento..." />

            <x-forms.input wire:model="date" type="datetime-local" label="Data e Hora" />

            <x-forms.select wire:model.live="selectedUser">
                <option value="0">{{ __('Selecione os convidados') }}</option>
                @foreach($this->users() as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }}
                    </option>
                @endforeach
            </x-forms.select>

            <div class="flex flex-wrap gap-2">
                @foreach($guests as $guestId => $guest)
                    <div class="flex bg-primary px-2 rounded-xs py-1 text-white gap-2 items-start">
                        <span class="text-sm">{{ data_get($guest, 'name') }}</span>
                        <x-icons.outline.x-circle wire:click="removeGuest({{ $guestId }})" class="w-5 cursor-pointer" />
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-zinc-900 hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-500 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors"
                >
                    Criar Evento
                </button>
            </div>
        </form>
    </x-modal>
</div>
