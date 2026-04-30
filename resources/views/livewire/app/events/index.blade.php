<section class="p-4">
    <div class="space-y-4">
        @forelse ($events as $event)
            <livewire:app.events.card :event="$event" :key="$event->id" />
        @empty
            <x-ui.empty-results :message="__('Nenhum evento encontrado. Seja o primeiro a criar um!')" />
        @endforelse
    </div>

    {{-- Pagination --}}
    {{ $events->links() }}

    <livewire:app.events.create />
</section>
