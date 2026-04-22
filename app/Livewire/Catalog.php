<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Acervo')]
#[Layout('components.layouts.feed')]
class Catalog extends Component
{
    public function render(): View
    {
        return view('livewire.catalog');
    }
}
