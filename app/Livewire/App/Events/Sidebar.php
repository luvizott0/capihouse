<?php

namespace App\Livewire\App\Events;

use App\Models\Event;
use Illuminate\View\View;
use Livewire\Component;

class Sidebar extends Component
{
    public function render(): View
    {
        $events = Event::query()
            ->with(['owner', 'media'])
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();

        return view('livewire.app.events.sidebar', [
            'events' => $events,
        ]);
    }
}
