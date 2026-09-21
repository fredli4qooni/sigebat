<x-guest-layout>
    <x-slot:title>Atur Ulang Kata Sandi</x-slot:title>

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Atur Ulang Kata Sandi</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Silakan masukkan kata sandi baru untuk akun Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email', $request->email)" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata sandi baru" />
            <x-text-input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password" 
                placeholder="Minimal 8 karakter"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi kata sandi" />
            <x-text-input 
                id="password_confirmation" 
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password" 
                placeholder="Ulangi kata sandi baru"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                Simpan Kata Sandi Baru
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
