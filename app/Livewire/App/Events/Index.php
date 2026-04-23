<?php

namespace App\Livewire\App\Events;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Events')]
#[Layout('components.layouts.auth')]
class Index extends Component
{
    public function render(): View
    {
        return view('livewire.app.events.index');
    }
}
