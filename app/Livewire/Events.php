<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Eventos')]
#[Layout('components.layouts.feed')]
class Events extends Component
{
    public function render(): View
    {
        return view('livewire.events');
    }
}
