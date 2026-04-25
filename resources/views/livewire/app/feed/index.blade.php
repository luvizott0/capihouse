<div class="flex flex-col gap-4 p-4">
    {{-- Posts --}}
    @forelse ($posts as $post)
        <livewire:post.card :post="$post" :key="$post->id" />
    @empty
        <div class="py-12 text-sm text-center text-primary-500">
            {{ __('Nenhum post ainda. Seja o primeiro a publicar!') }}
        </div>
    @endforelse

    {{-- Pagination --}}
    {{ $posts->links() }}


    <livewire:post.create />
</div>
