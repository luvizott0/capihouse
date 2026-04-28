<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="border-2 border-border bg-white">
        <div class="flex font-mono items-center px-4 py-2 text-sm font-bold bg-primary text-white border-b border-border uppercase">
            » {{ __('Sobre mim') }}
        </div>

        <div class="px-4 py-2 flex flex-col gap-2">
            <div class="flex">
                <span class="text-sm font-normal text-primary-800">"{{ $user->bio ?? __('Diga algo sobre você...') }}"</span>
            </div>

            <div class="flex gap-2 items-start">
                <x-icons.outline.cake class="w-4 text-primary-800" />
                <span class="text-sm text-primary-800">{{ $user->birth?->format('d/m/Y') }}</span>
            </div>

            <div class="flex"></div>
        </div>
    </div>

    <div class="border-2 border-border bg-white">
        <div class="flex font-mono items-center px-4 py-2 text-sm font-bold bg-primary text-white border-b border-border uppercase">
            » {{ __('Meus interesses') }}
        </div>

        <div class="px-4 py-2">
            teste
        </div>
    </div>

    <div class="border-2 border-border bg-white">
        <div class="flex font-mono items-center px-4 py-2 text-sm font-bold bg-primary text-white border-b border-border uppercase">
            » {{ __('Status') }}
        </div>

        <div class="px-4 py-2">
            <span class="italic">"{{ __('Apenas mais uma capivara perdida no mundo...') }}"</span>
        </div>
    </div>
</div>
