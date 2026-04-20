<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?string $content = null;
};
?>

<div>
    <x-modal
        open-event="post::create"
        title="Criar Post"
        action-label="Publicar"
    >
        <div>
            <livewire:user-avatar />
            <textarea
                placeholder="O que você está pensando?"
                wire:model="content"
                class="w-full py-1 px-2 text-sm border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400"
            >
            </textarea>
        </div>
    </x-modal>
</div>
