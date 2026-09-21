<x-guest-layout>
    <x-slot:title>Pendaftaran Pengelola Wisata</x-slot:title>

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Daftar Pengelola</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Akun Anda akan diverifikasi oleh Admin sebelum dapat digunakan.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input 
                id="name" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name" 
                placeholder="Contoh: Ahmad Sanusi"
            />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Alamat Email -->
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autocomplete="username" 
                placeholder="nama@email.com"
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Nomor Telepon / WhatsApp -->
        <div>
            <x-input-label for="phone" value="Nomor HP / WhatsApp" />
            <x-text-input 
                id="phone" 
                type="text" 
                name="phone" 
                :value="old('phone')" 
                required 
                placeholder="0812xxxxxxxx"
            />
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <!-- Kata Sandi -->
        <div>
            <x-input-label for="password" value="Kata sandi" />
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

        <!-- Konfirmasi Kata Sandi -->
        <div>
            <x-input-label for="password_confirmation" value="Ulangi kata sandi" />
            <x-text-input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password" 
                placeholder="Ulangi kata sandi yang sama"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <!-- Tombol Kirim -->
        <div class="pt-2">
            <x-primary-button class="w-full">
                Kirim Pendaftaran
            </x-primary-button>
        </div>

        <div class="pt-5 border-t border-slate-100 text-center">
            <p class="text-xs sm:text-sm text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold no-underline ml-1">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
