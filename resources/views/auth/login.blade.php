<x-guest-layout>
    <x-slot:title>Masuk ke Sistem</x-slot:title>

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Masuk ke Sistem</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Khusus Pengelola Wisata dan Administrator</p>
    </div>

    @if (session('status'))
        <div class="mb-5 p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs sm:text-sm font-medium flex items-start gap-2.5">
            <svg class="w-4 h-4 flex-shrink-0 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" value="Kata sandi" class="!mb-0" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-slate-500 hover:text-emerald-700 font-medium no-underline transition-colors">
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
                    class="w-4 h-4 rounded-md border-slate-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer" 
                    name="remember"
                >
                <span class="text-xs sm:text-sm text-slate-600">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Tombol Masuk -->
        <div class="pt-2">
            <x-primary-button class="w-full">
                Masuk ke Akun
            </x-primary-button>
        </div>

        <div class="pt-5 border-t border-slate-100 text-center">
            <p class="text-xs sm:text-sm text-slate-500">
                Ingin mendaftar sebagai pengelola?
                <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold no-underline ml-1">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
