<div class="flex flex-col gap-4 p-4">
    {{-- Posts --}}
    @forelse ($posts as $post)
        <livewire:app.feed.post.card :post="$post" :key="$post->id" />
    @empty
        <x-ui.empty-results :message="__('Nenhum post encontrado. Seja o primeiro a publicar!')" />
    @endforelse

    {{-- Pagination --}}
    {{ $posts->links() }}


    <livewire:app.feed.post.create />
</div>
