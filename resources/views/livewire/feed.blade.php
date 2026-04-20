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

    {{-- FAB --}}
    <button
        wire:click="$dispatch('post::create')"
        class="fixed z-50 flex items-center cursor-pointer justify-center text-2xl text-white  rounded-full shadow-lg w-14 h-14
        bottom-24 right-4 bg-primary hover:bg-primary-500"
    >
        +
    </button>

    <livewire:post.create />
</div>
