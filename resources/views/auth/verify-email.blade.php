<x-guest-layout>
    <x-slot:title>Verifikasi Alamat Email</x-slot:title>

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verifikasi Email</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Terima kasih telah mendaftar! Sebelum memulai, silakan periksa kotak masuk email Anda dan klik tautan verifikasi yang baru saja kami kirimkan.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium">
            Tautan verifikasi baru telah berhasil dikirim ke alamat email yang Anda gunakan saat mendaftar.
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <x-primary-button class="w-full sm:w-auto">
                Kirim Ulang Email
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs sm:text-sm text-slate-500 hover:text-slate-800 underline font-medium cursor-pointer">
                Keluar (Logout)
            </button>
        </form>
    </div>
</x-guest-layout>
