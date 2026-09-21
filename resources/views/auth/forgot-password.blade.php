<x-guest-layout>
    <x-slot:title>Lupa Kata Sandi</x-slot:title>

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Lupa Kata Sandi</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Masukkan alamat email yang terdaftar, kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                placeholder="nama@email.com"
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                Kirim Tautan Reset Kata Sandi
            </x-primary-button>
        </div>

        <div class="pt-5 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-emerald-600 hover:text-emerald-700 no-underline">
                &larr; Kembali ke halaman masuk
            </a>
        </div>
    </form>
</x-guest-layout>
