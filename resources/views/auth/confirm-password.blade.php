<x-guest-layout>
    <x-slot:title>Konfirmasi Kata Sandi</x-slot:title>

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Konfirmasi Keamanan</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Ini adalah area sistem yang dilindungi. Masukkan kata sandi Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata sandi" />
            <x-text-input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                placeholder="Masukkan kata sandi Anda"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                Konfirmasi Akses
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
