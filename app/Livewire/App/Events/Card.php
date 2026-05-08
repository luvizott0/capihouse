<?php

namespace App\Livewire\App\Events;

use App\Models\Event;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Card extends Component
{
    public Event $event;

    #[Computed]
    public function guests()
    {
        return $this->event->guests()->take(3)->get();
    }

    public function render(): View
    {
        return view('livewire.app.events.card');
    }
}
