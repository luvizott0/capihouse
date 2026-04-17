@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h2 class="text-xl font-bold text-primary-800">{{ $title }}</h2>
    <p class="text-sm text-primary-600">{{ $description }}</p>
</div>
