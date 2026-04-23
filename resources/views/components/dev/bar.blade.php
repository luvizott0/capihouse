@if(!app()->isProduction() && config('app.dev_bar'))
    <div class="bg-[#77c4ff] w-full flex justify-between py-1 px-6">
        <livewire:dev.env-bar />
        <livewire:dev.login />
    </div>
@endif
