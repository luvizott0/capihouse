<x-layouts::app.sidebar :title="$title ?? null">
    <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
        {{ $slot }}
    </main>
</x-layouts::app.sidebar>
