<x-guest-layout>
    <x-slot:title>Masuk ke Sistem</x-slot:title>

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold font-papan text-aspal">Masuk ke Sistem</h1>
        <p class="text-sm text-abu mt-1">Khusus Pengelola Wisata dan Administrator</p>
    </div>

    @if (session('status'))
        <div class="mb-5 p-3.5 rounded-kontrol bg-biru text-putih text-sm font-medium flex items-start gap-2.5">
            <svg class="w-5 h-5 flex-shrink-0 fill-current mt-0.5" viewBox="0 0 256 256">
                <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm16-40a8,8,0,0,1-8,8,16,16,0,0,1-16-16V120a8,8,0,0,1,0-16,16,16,0,0,1,16,16v48A8,8,0,0,1,144,176ZM112,84a12,12,0,1,1,12,12A12,12,0,0,1,112,84Z"/>
            </svg>
            <div class="flex-1 leading-snug">{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="nama@sigebat.desa.id"
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" value="Kata sandi" class="!mb-0" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-abu underline hover:text-aspal">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <x-text-input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                placeholder="Masukkan kata sandi"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Ingat Sesi -->
        <div class="pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="w-4 h-4 rounded-tag border-2 border-abu text-aspal focus:ring-0 focus:border-aspal" 
                    name="remember"
                >
                <span class="text-sm text-aspal">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Tombol Masuk -->
        <div class="pt-2">
            <x-primary-button class="w-full">
                Masuk
            </x-primary-button>
        </div>

        <div class="pt-4 border-t-2 border-beton text-center">
            <p class="text-sm text-abu">
                Ingin mendaftar sebagai pengelola?
                <a href="{{ route('register') }}" class="text-aspal font-bold underline hover:text-cokelat ml-1">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
