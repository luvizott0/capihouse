<?php

namespace App\Livewire\App\Navigation;

use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Menu extends Component
{
    public bool $desktop = false;

    #[Computed]
    public function menuItems(): array
    {
        return [
            ['route' => 'feed', 'label' => __('Feed'), 'icon' => 'icons.outline.home'],
            ['route' => 'events', 'label' => __('Eventos'), 'icon' => 'icons.outline.calendar'],
            ['route' => 'catalog', 'label' => __('Acervo'), 'icon' => 'icons.outline.book'],
            ['route' => 'profile', 'label' => __('Perfil'), 'icon' => 'icons.outline.user'],
        ];
    }

    public function getRouteName(): string
    {
        return  request()->route()?->getName() ?? 'feed';
    }

    public function render(): View
    {
        return view('livewire.app.navigation.menu');
    }
}
