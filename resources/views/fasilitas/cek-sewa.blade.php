<x-portal-layout>
    <x-slot:title>Pelacakan & Cek Bukti Sewa Fasilitas — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Cek dan temukan kembali kode sewa serta voucher tanda bukti reservasi fasilitas desa Kampung Gedung Batin.</x-slot:description>

    <div class="py-8 md:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Header Navigasi -->
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-emerald-700 no-underline">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('fasilitas.index') }}" class="hover:text-emerald-700 no-underline">Fasilitas</a>
                    <span>/</span>
                    <span class="text-slate-800 font-semibold">Pelacakan Sewa</span>
                </nav>

                <a
                    href="{{ route('fasilitas.index') }}"
                    class="h-9 px-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl transition-all inline-flex items-center gap-1.5 no-underline shadow-2xs"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Fasilitas</span>
                </a>
            </div>

            <!-- Banner Utama Pelacakan -->
            <div class="bg-slate-900 border border-slate-800 text-white rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="space-y-2 max-w-2xl">
                    <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">
                        Layanan Mandiri Wisatawan
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        Lacak Reservasi &amp; Bukti Sewa Fasilitas
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                        Masukkan kode reservasi sewa (contoh: <code class="text-emerald-300 font-mono">SGB-SWA-2610-ABCDE</code>), alamat email, atau nomor telepon pemesan untuk membuka kembali voucher tanda bukti Anda.
                    </p>
                </div>
            </div>

            <!-- Kotak Pencarian -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <form method="GET" action="{{ route('sewa.cek') }}" class="space-y-4">
                    <label for="q" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Kata Kunci Pencarian (Kode Sewa / Email / No. WhatsApp / Nama)
                    </label>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input
                                type="text"
                                id="q"
                                name="q"
                                value="{{ $q }}"
                                required
                                minlength="3"
                                placeholder="Contoh: SGB-SWA-2610-XXXXX atau 08123456789 atau email@anda.com"
                                class="h-12 pl-10 pr-4 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                            >
                            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <button
                            type="submit"
                            class="h-12 px-7 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-sm hover:shadow-emerald-600/30 cursor-pointer flex items-center justify-center gap-2 whitespace-nowrap"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Cari Reservasi</span>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('q')" class="mt-1" />
                </form>
            </div>

            <!-- Hasil Pencarian -->
            @if($isSearching)
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-900">
                            Hasil Pencarian untuk: <span class="text-emerald-700 font-mono">"{{ $q }}"</span>
                        </h2>
                        <span class="text-xs text-slate-500 font-semibold">{{ $hasil->count() }} Data Ditemukan</span>
                    </div>

                    @forelse($hasil as $item)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:border-emerald-300 transition-all space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                                <div>
                                    <span class="font-mono text-xs font-bold text-slate-500 uppercase">KODE SEWA:</span>
                                    <span class="font-mono text-sm font-extrabold text-slate-900 ml-1">{{ $item->kode_sewa }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $item->status_pembayaran_badge_class }}">
                                        {{ $item->status_pembayaran_label }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $item->status_sewa_badge_class }}">
                                        {{ $item->status_sewa_label }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600">
                                <div>
                                    <span class="block text-slate-400 text-[10px] uppercase font-bold">Fasilitas</span>
                                    <span class="font-bold text-slate-900 text-sm">{{ $item->fasilitas?->nama }}</span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 text-[10px] uppercase font-bold">Periode Sewa</span>
                                    <span class="font-semibold text-slate-800">
                                        {{ $item->tanggal_mulai->translatedFormat('d M Y') }} s/d {{ $item->tanggal_selesai->translatedFormat('d M Y') }} ({{ $item->durasi_hari }} Hari)
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 text-[10px] uppercase font-bold">Total Biaya</span>
                                    <span class="font-extrabold text-emerald-700 text-sm">{{ $item->total_tagihan_format }}</span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
                                <div class="text-xs text-slate-500">
                                    Pemesan: <strong class="text-slate-800">{{ $item->nama_penyewa }}</strong> ({{ $item->nomor_telepon }})
                                </div>

                                <div class="flex items-center gap-2">
                                    @if(! $item->is_lunas)
                                        <a
                                            href="{{ route('sewa.bayar', $item->kode_sewa) }}"
                                            class="h-9 px-3.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-xs font-semibold no-underline inline-flex items-center gap-1.5 transition-all"
                                        >
                                            <span>Unggah Bukti</span>
                                        </a>
                                    @endif

                                    <a
                                        href="{{ route('sewa.bukti', $item->kode_sewa) }}"
                                        class="h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold no-underline inline-flex items-center gap-1.5 transition-all shadow-xs"
                                    >
                                        <span>Buka Voucher</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center bg-white rounded-2xl border border-slate-200 text-slate-400 space-y-2">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-sm font-bold text-slate-800">Tidak ada riwayat sewa yang cocok</div>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                                Pastikan kode sewa yang Anda ketik sudah sesuai atau gunakan nomor telepon/email yang didaftarkan.
                            </p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-portal-layout>
