<div class="flex flex-col gap-4 p-4">
    @forelse ($posts as $post)
        <livewire:post.card :post="$post" :key="$post->id" />
    @empty
        <div class="py-12 text-sm text-center text-primary-500">
            {{ __('Nenhum post encontrado neste perfil.') }}
        </div>
    @endforelse

    {{ $posts->links() }}
</div>
