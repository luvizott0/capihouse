<?php

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Post;
use App\Models\User;

new class extends Component {
    public Post $post;

    public User $user;

    public array $media = [];

    public bool $hasMedia;

    public bool $isCarousel;

    public bool $isLiked;

    public bool $commentsOpen = false;

    public int $commentsCount = 0;

    public function mount(): void
    {
        $this->loadPostInformation();
        $this->verifyIfPostIsLiked();
        $this->commentsCount = $this->post->comments()->count();
    }

    #[On('post-comments-updated')]
    public function refreshCommentsCount(int $postId): void
    {
        if ($postId !== $this->post->id) {
            return;
        }

        $this->commentsCount = $this->post->comments()->count();
    }

    public function loadPostInformation(): void
    {
        $this->user = $this->post->user;
        $this->media = $this->post->media ?? [];
        $this->hasMedia = count($this->media) > 0;
        $this->isCarousel = count($this->media) > 1;
    }

    public function VerifyIfPostIsLiked(): void
    {
        $user = auth()->user();
        $this->isLiked = $this->post
            ->likes()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function getTime(): string
    {
        return $this->post->created_at->diffForHumans(short: true);
    }

    public function likePost(): void
    {
        if ($this->isLiked) {
            $this->post->likes()
                ->where('user_id', auth()->user()->id)
                ->delete();

            $this->isLiked = false;

            return;
        }

        $this->post->likes()->create([
            'user_id' => auth()->user()->id,
        ]);

        $this->isLiked = true;
    }
};
?>

<div class="overflow-hidden border-2 rounded-xs border-border bg-white">
    {{-- Bulletin Header --}}
    <div
        class="flex items-center justify-between px-4 py-2 text-sm font-bold tracking-wider text-white uppercase bg-primary">
        <span>Postado por {{ $user->name }}</span>
        <span class="text-primary-200">{{ $this->getTime() }}</span>
    </div>

    <div class="p-4">
        {{-- User Info --}}
        <x-user-info :user="$user" />
        {{-- Body Text --}}
        @if ($post->content)
            <p class="mb-2 text-sm text-primary-800 leading-relaxed">{{ $post->content }}</p>
        @endif

        @if ($post->hashtags->count() > 0)
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach ($post->hashtags as $hashtag)
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-white  bg-primary-600">
                        #{{ $hashtag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Media --}}
        <div class="border-t border-primary-600">
            @if ($hasMedia)
                @if ($isCarousel)
                    {{-- Image Carousel with Alpine.js --}}
                    <div x-data="{ current: 0, total: {{ count($media) }} }" class="relative mb-3">
                        <div class="overflow-hidden rounded-lg">
                            @foreach ($media as $index => $item)
                                <div x-show="current === {{ $index }}"
                                     x-transition:enter="transition ease-out duration-300" class="w-full">
                                    @if ($item['type'] === 'video')
                                        <video src="{{ $item['url'] }}" controls
                                               class="object-cover w-full rounded-lg aspect-square"></video>
                                    @else
                                        <img src="{{ $item['url'] }}" alt=""
                                             class="object-cover w-full rounded-lg aspect-square" loading="lazy">
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Navigation Arrows --}}
                        <button x-show="current > 0" @click="current--"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 rounded-full flex items-center justify-center text-primary-800 shadow">
                            ‹
                        </button>
                        <button x-show="current < total - 1" @click="current++"
                                class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 rounded-full flex items-center justify-center text-primary-800 shadow">
                            ›
                        </button>

                        {{-- Dots Indicator --}}
                        <div class="flex justify-center gap-1 mt-2">
                            <template x-for="i in total" :key="i">
                                <button @click="current = i - 1"
                                        :class="current === i - 1 ? 'bg-primary' : 'bg-primary-200'"
                                        class="w-2 h-2 rounded-full transition"></button>
                            </template>
                        </div>
                    </div>
                @else
                    {{-- Single Media --}}
                    <div class="mb-3">
                        @if ($media[0]['type'] === 'video')
                            <video src="{{ $media[0]['url'] }}" controls
                                   class="object-cover w-full rounded-lg aspect-square"></video>
                        @else
                            <img src="{{ $media[0]['url'] }}" alt=""
                                 class="object-cover w-full rounded-lg aspect-square" loading="lazy">
                        @endif
                    </div>
                @endif
            @endif
        </div>

        {{-- Actions: Like, Comment, Share --}}
        <div class="flex items-center gap-6 pt-5">
            <button wire:click="likePost"
                    class="flex items-center cursor-pointer gap-1 text-sm text-primary-600 hover:text-red-500 transition">
                @if($isLiked)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="w-5 text-red-600">
                        <path
                            d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-5 text-subtitle">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                @endif


                <span>{{ $post->getLikesCount() }}</span>
            </button>
            <button wire:click="$toggle('commentsOpen')"
                    class="flex items-center gap-1 text-sm text-subtitle hover:text-primary-800 cursor-pointer transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>{{ $commentsCount }}</span>
            </button>
            <button class="flex items-center gap-1 text-sm text-subtitle hover:text-primary-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0-12.814a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0 12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                </svg>
            </button>
        </div>

        <div>
            @if($commentsOpen)
                <livewire:post.comments :post="$post"/>
            @endif
        </div>
    </div>
</div>
