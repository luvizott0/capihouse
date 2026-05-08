<section>
    <div class="flex flex-col gap-6">
        <div class="overflow-hidden border-2 border-primary">
            <div class="px-4 py-2 text-sm font-bold tracking-wider text-center text-white uppercase bg-primary">
                &#9733; {{ __("Bem-vindo ao CapiHouse") }} &#9733;
            </div>
            <div class="flex flex-col items-center gap-2 px-6 py-8 bg-white">
                <img src="{{ asset("capihouse-logo.png") }}" alt="CapiHouse" class="w-28 h-28">
                <h1 class="text-2xl font-bold text-primary-800">{{ __("Criar conta") }}</h1>
                <p class="text-sm text-primary-600">{{ __("A rede social dos amigos da casa.") }}</p>
            </div>
        </div>
        @if ($registered)
            <div class="overflow-hidden border-2 border-green-500">
                <div class="px-4 py-2 text-sm font-bold tracking-wider text-white uppercase bg-green-500">
                    &#10003; {{ __("Pedido enviado!") }}
                </div>
                <div class="p-6 bg-white">
                    <p class="text-sm text-center text-green-700">
                        {{ __("Seu pedido foi enviado ao administrador. Em breve voce recebera uma resposta.") }}
                    </p>
                    <div class="mt-4 text-sm text-center">
                        <a href="{{ route("login") }}" wire:navigate class="font-bold underline text-primary-800 hover:text-primary-600">
                            {{ __("Voltar ao login") }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="overflow-hidden border-2 border-primary">
                <div class="px-4 py-2 text-sm font-bold tracking-wider text-white uppercase bg-primary">
                    &raquo; {{ __("Criar nova conta") }}
                </div>
                <div class="p-6 bg-white">
                    <form wire:submit="register" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label for="name" class="block mb-1 text-sm font-bold text-primary-800">{{ __("Nome completo") }}</label>
                            <input id="name" name="name" type="text" wire:model="name" required autofocus autocomplete="name" class="w-full px-3 py-2 text-sm border-2 border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400" />
                            @error("name") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="username" class="block mb-1 text-sm font-bold text-primary-800">{{ __("Nome de usuario") }}</label>
                            <input id="username" name="username" type="text" wire:model="username" required autocomplete="username" class="w-full px-3 py-2 text-sm border-2 border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400" />
                            @error("username") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block mb-1 text-sm font-bold text-primary-800">{{ __("Email") }}</label>
                            <input id="email" name="email" type="email" wire:model="email" required autocomplete="email" class="w-full px-3 py-2 text-sm border-2 border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400" />
                            @error("email") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password" class="block mb-1 text-sm font-bold text-primary-800">{{ __("Senha") }}</label>
                            <input id="password" name="password" type="password" wire:model="password" required autocomplete="new-password" class="w-full px-3 py-2 text-sm border-2 border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400" />
                            @error("password") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block mb-1 text-sm font-bold text-primary-800">{{ __("Confirmar senha") }}</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" wire:model="password_confirmation" required autocomplete="new-password" class="w-full px-3 py-2 text-sm border-2 border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400" />
                        </div>
                        <button type="submit" class="w-full py-3 text-sm font-bold text-white transition cursor-pointer bg-primary hover:bg-primary-500">
                            [ {{ __("Solicitar registro") }} ]
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-sm text-center text-primary-700">
                {{ __("Ja tem uma conta?") }}
                <a href="{{ route("login") }}" wire:navigate class="font-bold underline text-primary-800 hover:text-primary-600">{{ __("Entrar") }}</a>
            </div>
        @endif
    </div>
</section>