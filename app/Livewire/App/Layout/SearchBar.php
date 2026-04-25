<?php

namespace App\Livewire\App\Layout;

use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SearchBar extends Component
{
    public string $scope = 'feed';

    public function mount(): void
    {
        $currentRouteName = request()->route()?->getName() ?? 'feed';

        if ($currentRouteName === 'profile.show') {
            $currentRouteName = 'profile';
        }

        $this->scope = array_key_exists($currentRouteName, $this->scopes)
            ? $currentRouteName
            : 'feed';
    }

    /**
     * @return array<string, array{label: string, placeholder: string}>
     */
    #[Computed]
    public function scopes(): array
    {
        return [
            'feed' => ['label' => __('Feed'), 'placeholder' => __('Pesquisar posts...')],
            'profile' => ['label' => __('Perfil'), 'placeholder' => __('Pesquisar posts deste perfil...')],
            'events' => ['label' => __('Eventos'), 'placeholder' => __('Pesquisar eventos...')],
            'catalog' => ['label' => __('Acervo'), 'placeholder' => __('Pesquisar midias do acervo...')],
        ];
    }

    #[Computed]
    public function currentPlaceholder(): string
    {
        return data_get($this->scopes, $this->scope.'.placeholder', __('Pesquisar...'));
    }

    /**
     * @return array{type: string, label: string, event?: string}
     */
    #[Computed]
    public function actionButton(): array
    {
        return match (request()->route()?->getName()) {
            'feed' => ['type' => 'event', 'label' => __('Criar post'), 'event' => 'post::create'],
            'events' => ['type' => 'event', 'label' => __('Adicionar evento'), 'event' => 'event::create'],
            'catalog' => ['type' => 'event', 'label' => __('Adicionar midia'), 'event' => 'catalog::create'],
            'profile', 'profile.show' => ['type' => 'logout', 'label' => __('Logout')],
            default => ['type' => 'event', 'label' => __('Acao')],
        };
    }

    public function render(): View
    {
        return view('livewire.app.layout.search-bar');
    }
}
