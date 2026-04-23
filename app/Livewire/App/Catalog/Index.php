<?php

namespace App\Livewire\App\Catalog;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Catalog')]
#[Layout('components.layouts.auth')]
class Index extends Component
{
    public function render(): View
    {
        return view('livewire.app.catalog.index');
    }
}
