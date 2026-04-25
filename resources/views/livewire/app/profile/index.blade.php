<div class="flex flex-col gap-4 p-4">
    <livewire:app.profile.card :user="$profileUser" :is-owner="$isOwner" :key="'profile-header-'.$profileUser->id" />

    <livewire:app.profile.personal-info />

    <div class="border-2 border-border bg-white">
        <div class="flex font-mono items-center px-4 py-2 text-sm font-bold bg-primary text-white border-b border-border uppercase">
            » {{ __('Todas as postagens') }}
        </div>
    </div>

    @forelse ($posts as $post)
        <livewire:post.card :post="$post" :key="$post->id" />
    @empty
        <div class="py-12 text-sm text-center text-primary-500">
            {{ __('Nenhum post encontrado neste perfil.') }}
        </div>
    @endforelse

    {{ $posts->links() }}
</div>
