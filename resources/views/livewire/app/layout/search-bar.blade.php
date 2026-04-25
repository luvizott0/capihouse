<div class="flex gap-2 mt-4" data-test="global-search-bar">
    <select
        wire:model.live="scope"
        class="w-28 md:w-auto py-2 px-3 text-sm font-medium text-primary-700 border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400"
        aria-label="{{ __('Selecionar tipo de pesquisa') }}"
        data-test="search-scope"
    >
        @foreach ($this->scopes as $route => $scopeConfig)
            <option value="{{ $route }}">{{ data_get($scopeConfig, 'label') }}</option>
        @endforeach
    </select>

    <div class="relative w-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute w-5 h-5 left-3 top-2.5 text-subtitle" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input
            type="text"
            placeholder="{{ $this->currentPlaceholder }}"
            class="w-full py-2 pl-10 pr-4 text-sm font-medium text-subtitle border rounded-sm border-border bg-primary-100 focus:outline-none focus:border-primary-400"
            readonly
        />
    </div>

    @if ($this->actionButton['type'] === 'logout')
        <livewire:auth.logout />
    @elseif (filled($this->actionButton['event'] ?? null))
        <button
            type="button"
            class="btn-primary whitespace-nowrap"
            x-data
            @click="$dispatch('{{ $this->actionButton['event'] }}')"
            data-test="search-action-button"
        >
            {{ $this->actionButton['label'] }}
        </button>
    @endif
</div>
