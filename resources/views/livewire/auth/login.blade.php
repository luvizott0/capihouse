<section>
    <div class="flex flex-col gap-6">
        {{-- Welcome Card --}}
        <div class="overflow-hidden border-2 rounded-lg border-primary">
            <div class="px-4 py-2 text-sm font-bold tracking-wider text-center text-white uppercase bg-primary">
                ★ {{ __('Bem-vindo ao CapiHouse') }} ★
            </div>
            <div class="flex flex-col items-center gap-2 px-6 py-8 bg-white">
                <img src="{{ asset('capihouse-logo.png') }}" alt="CapiHouse" class="w-28 h-28">
                <h1 class="text-2xl font-bold text-primary-800">{{ __('Entrar') }}</h1>
                <p class="text-sm text-primary-600">{{ __('A rede social dos amigos da casa.') }}</p>
            </div>
        </div>

        {{-- Login Form Card --}}
        <div class="overflow-hidden border-2 rounded-lg border-primary">
            <div class="px-4 py-2 text-sm font-bold tracking-wider text-white uppercase bg-primary">
                » {{ __('Acessar conta') }}
            </div>
            <div class="p-6 bg-white">
                {{-- Session Status --}}
                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-center text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form wire:submit="tryLogin" class="flex flex-col gap-4">
                    @csrf

                    {{-- Email or Username --}}
                    <div>
                        <label for="email" class="block mb-1 text-sm font-bold text-primary-800">
                            {{ __('Email ou usuario') }}
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="text"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full px-3 py-2 text-sm border-2 rounded border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400"
                        />
                        @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block mb-1 text-sm font-bold text-primary-800">
                            {{ __('Senha') }}
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-3 py-2 text-sm border-2 rounded border-primary-200 bg-primary-50 focus:outline-none focus:border-primary-400"
                        />
                        @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full py-3 text-sm font-bold text-white transition rounded cursor-pointer bg-primary hover:bg-primary-500"
                        data-test="login-button"
                    >
                        [ {{ __('Entrar') }} ]
                    </button>
                </form>
            </div>
        </div>

        {{-- Register Link --}}
        @if (Route::has('register'))
            <div class="text-sm text-center text-primary-700">
                {{ __('Ainda nao tem conta?') }}
                <a href="{{ route('register') }}" class="font-bold underline text-primary-800 hover:text-primary-600">
                    {{ __('Registrar') }}
                </a>
            </div>
        @endif
    </div>
</section>
