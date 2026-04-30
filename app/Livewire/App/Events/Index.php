<?php

namespace App\Livewire\App\Events;

use App\Models\Event;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Events')]
#[Layout('components.layouts.auth')]
class Index extends Component
{
    #[On('events::reload')]
    public function renderEvents(): void
    {
        $this->render();
    }

    public function render(): View
    {
        $events = Event::where('user_id', auth()->id())
            ->orWhereHas('guests', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('owner', 'guests', 'media')
            ->latest()
            ->paginate(10);

        return view('livewire.app.events.index', [
            'events' => $events,
        ]);
    }
}
