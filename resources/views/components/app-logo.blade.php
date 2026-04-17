<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <img src="{{ asset('capihouse-logo.png') }}" alt="CapiHouse" class="w-8 h-8">
    <span class="font-bold text-primary-800">{{ config('app.name') }}</span>
</div>
