@props(['post'])

@php
    $user = $post->user;
    $media = $post->media ?? [];
    $hasMedia = count($media) > 0;
    $isCarousel = count($media) > 1;
    $bulletinTitle = strtoupper($user->name) . "'S BULLETIN";
    $timeAgo = $post->created_at->diffForHumans(short: true);
@endphp

<div class="overflow-hidden border-2 rounded-xs border-primary bg-white">
    {{-- Bulletin Header --}}
    <div class="flex items-center justify-between px-4 py-2 text-sm font-bold tracking-wider text-white uppercase bg-primary">
        <span>{{ $bulletinTitle }}</span>
        <span class="text-primary-200">{{ $timeAgo }}</span>
    </div>

    <div class="p-4">
        {{-- User Info --}}
        <div class="flex items-center gap-3 mb-3">
            <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-primary-300">
                {{ $user->initials() }}
            </div>
            <div>
                <p class="text-sm font-bold text-primary-800">{{ $user->name }}</p>
                <p class="text-xs text-primary-500">{{ '@' . $user->username }}</p>
            </div>
        </div>

        {{-- Body Text --}}
        @if ($post->body)
            <p class="mb-3 text-sm text-primary-800 leading-relaxed">{{ $post->body }}</p>
        @endif

        {{-- Media --}}
        @if ($hasMedia)
            @if ($isCarousel)
                {{-- Image Carousel with Alpine.js --}}
                <div x-data="{ current: 0, total: {{ count($media) }} }" class="relative mb-3">
                    <div class="overflow-hidden rounded-lg">
                        @foreach ($media as $index => $item)
                            <div x-show="current === {{ $index }}" x-transition:enter="transition ease-out duration-300" class="w-full">
                                @if ($item['type'] === 'video')
                                    <video src="{{ $item['url'] }}" controls class="object-cover w-full rounded-lg aspect-square"></video>
                                @else
                                    <img src="{{ $item['url'] }}" alt="" class="object-cover w-full rounded-lg aspect-square" loading="lazy">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Navigation Arrows --}}
                    <button x-show="current > 0" @click="current--" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 rounded-full flex items-center justify-center text-primary-800 shadow">
                        ‹
                    </button>
                    <button x-show="current < total - 1" @click="current++" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 rounded-full flex items-center justify-center text-primary-800 shadow">
                        ›
                    </button>

                    {{-- Dots Indicator --}}
                    <div class="flex justify-center gap-1 mt-2">
                        <template x-for="i in total" :key="i">
                            <button @click="current = i - 1" :class="current === i - 1 ? 'bg-primary' : 'bg-primary-200'" class="w-2 h-2 rounded-full transition"></button>
                        </template>
                    </div>
                </div>
            @else
                {{-- Single Media --}}
                <div class="mb-3">
                    @if ($media[0]['type'] === 'video')
                        <video src="{{ $media[0]['url'] }}" controls class="object-cover w-full rounded-lg aspect-square"></video>
                    @else
                        <img src="{{ $media[0]['url'] }}" alt="" class="object-cover w-full rounded-lg aspect-square" loading="lazy">
                    @endif
                </div>
            @endif
        @endif

        {{-- Actions: Like, Comment, Share --}}
        <div class="flex items-center gap-6 pt-3 border-t border-primary-100">
            <button class="flex items-center gap-1 text-sm text-primary-600 hover:text-red-500 transition">
                <span class="text-red-500">❤️</span>
                <span>{{ $post->likes_count }}</span>
            </button>
            <button class="flex items-center gap-1 text-sm text-primary-600 hover:text-primary-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>{{ $post->comments_count }}</span>
            </button>
            <button class="flex items-center gap-1 text-sm text-primary-600 hover:text-primary-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0-12.814a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0 12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
            </button>
        </div>
    </div>
</div>

