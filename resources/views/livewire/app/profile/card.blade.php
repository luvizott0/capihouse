<div class="overflow-hidden border-2 rounded-sm border-border bg-white">
    <div class="h-28 border-b border-primary"
         @if (filled($user->banner_url))
             style="background-image: url('{{ e($user->banner_url) }}'); background-size: cover; background-position: center;"
         @else
             style="background: linear-gradient(90deg, #d6bda2 0%, #e8d3bc 100%);"
         @endif
    ></div>

    <div class="px-4 pb-4">
        <div class="flex flex-wrap items-end justify-between gap-4 -mt-14">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-24 h-24 text-2xl font-bold border-4 shadow-sm bg-primary text-white border-white">
                    {{ $user->initials() }}
                </div>

                <div class="pt-14">
                    <h1 class="text-lg md:text-2xl font-bold font-mono text-primary-800">{{ $user->name }}</h1>
                    <p class="text-sm md:text-md font-mono text-primary">{{ '@' . $user->username }}</p>
                    <p class="flex items-center gap-1 text-sm text-subtitle">
                        <span class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                        {{ $isOnline ? __('Online agora') : __('Offline') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
