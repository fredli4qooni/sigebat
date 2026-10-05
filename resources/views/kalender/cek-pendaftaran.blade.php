<x-portal-layout>
    <x-slot:title>Cek dan Lacak Bukti Pendaftaran Event Budaya — SIGEBAT</x-slot:title>
    <x-slot:description>Layanan mandiri peserta untuk mencari dan mengunduh kembali tanda bukti pendaftaran resmi event budaya di Desa Wisata Kampung Gedung Batin.</x-slot:description>

    <div
        class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 space-y-8"
        x-data="{
            localRegistrations: [],
            init() {
                try {
                    const raw = localStorage.getItem('sigebat_pendaftaran_history');
                    if (raw) {
                        this.localRegistrations = JSON.parse(raw);
                    }
                } catch (e) {
                    this.localRegistrations = [];
                }
            }
        }"
    >
        <!-- Breadcrumb Navigasi -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <a href="/" class="hover:text-slate-900 no-underline text-slate-500">Beranda</a>
            <span>/</span>
            <a href="{{ route('kalender.index') }}" class="hover:text-slate-900 no-underline text-slate-500">Kalender Event</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Cek Bukti Pendaftaran</span>
        </nav>

        <!-- Hero Card Pencarian -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-10 border border-slate-800 shadow-lg relative overflow-hidden">
            <div class="relative z-10 space-y-4 max-w-2xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold backdrop-blur-sm">
                    Layanan Mandiri Peserta
                </span>
                <h1 class="text-2xl sm:text-4xl font-bold tracking-tight text-white leading-tight">
                    Cek dan Lacak Bukti Pendaftaran
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    Kehilangan tautan atau tidak sengaja menutup halaman tanda bukti? Masukkan <strong>Kode Pendaftaran</strong>, <strong>Alamat Email</strong>, atau <strong>Nomor WhatsApp</strong> yang Anda gunakan saat mendaftar.
                </p>
            </div>

            <!-- Form Pencarian -->
            <form action="{{ route('event.pendaftaran.cek') }}" method="GET" class="mt-6 relative z-10">
                <div class="flex flex-col sm:flex-row items-stretch gap-2.5 bg-slate-800/90 p-2 rounded-2xl border border-slate-700 shadow-inner">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="q"
                            value="{{ $q }}"
                            placeholder="Contoh: SGB-EVT-..., email@anda.com, atau 081234567890"
                            required
                            minlength="3"
                            class="w-full pl-11 pr-4 py-3 bg-transparent text-white placeholder-slate-400 text-sm focus:outline-none"
                        >
                    </div>
                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer flex-shrink-0"
                    >
                        <span>Cari Bukti</span>
                        <span>&rarr;</span>
                    </button>
                </div>

                @error('q')
                    <div class="mt-2 text-xs text-rose-400 font-semibold">
                        {{ $message }}
                    </div>
                @enderror
            </form>
        </div>

        <!-- Riwayat Pendaftaran yang Tersimpan di Perangkat Ini (Local Storage) -->
        <div x-show="localRegistrations.length > 0" x-cloak class="bg-emerald-50/70 border border-emerald-200/90 rounded-2xl p-5 space-y-3">
            <div class="flex items-center justify-between">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Pendaftaran Terakhir di Perangkat Ini:</span>
                </div>
                <span class="text-[11px] text-emerald-700 font-medium">Tersimpan Otomatis</span>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <template x-for="(item, idx) in localRegistrations" :key="idx">
                    <a
                        :href="item.url"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white hover:bg-emerald-100 border border-emerald-300 text-slate-800 text-xs font-semibold no-underline transition-all shadow-2xs group"
                    >
                        <span class="font-mono text-emerald-700 group-hover:underline" x-text="item.kode"></span>
                        <span class="text-slate-400" x-show="item.tanggal">&bull;</span>
                        <span class="text-slate-600 text-[11px] truncate max-w-[140px]" x-text="item.judul"></span>
                        <span class="text-emerald-700 font-bold">&rarr;</span>
                    </a>
                </template>
            </div>
        </div>

        <!-- Hasil Pencarian -->
        @if($isSearching)
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <h2 class="text-lg font-bold text-slate-900">
                        Hasil Pencarian
                    </h2>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                        {{ $hasil->count() }} data ditemukan
                    </span>
                </div>

                @if($hasil->count() > 0)
                    <div class="space-y-4">
                        @foreach($hasil as $item)
                            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs hover:border-emerald-300 transition-all space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-mono text-xs font-black px-2.5 py-1 rounded-lg bg-slate-900 text-emerald-400">
                                                {{ $item->kode_pendaftaran }}
                                            </span>
                                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full
                                                {{ $item->status === 'terdaftar' ? 'bg-amber-50 text-amber-800 border border-amber-200' : ($item->status === 'dikonfirmasi' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($item->status === 'selesai' ? 'bg-slate-100 text-slate-700 border border-slate-200' : 'bg-rose-50 text-rose-800 border border-rose-200')) }}
                                            ">
                                                Status: {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                        <h3 class="text-base sm:text-lg font-bold text-slate-900 pt-1">
                                            <a href="{{ route('event.show', $item->event->slug) }}" class="text-slate-900 hover:text-emerald-700 no-underline transition-colors">
                                                {{ $item->event->judul }}
                                            </a>
                                        </h3>
                                    </div>

                                    <a
                                        href="{{ route('event.pendaftaran.bukti', $item->kode_pendaftaran) }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl no-underline transition-all shadow-xs flex-shrink-0"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>Buka Bukti Pendaftaran</span>
                                    </a>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-slate-100 text-xs text-slate-600">
                                    <div>
                                        <span class="block text-slate-400 font-semibold uppercase text-[10px]">Nama Pemesan</span>
                                        <span class="font-bold text-slate-900 mt-0.5 block">{{ $item->nama_lengkap }}</span>
                                        <span class="text-slate-500">({{ $item->jumlah_peserta }} Orang)</span>
                                    </div>
                                    <div>
                                        <span class="block text-slate-400 font-semibold uppercase text-[10px]">Jadwal Pelaksanaan</span>
                                        <span class="font-medium text-slate-800 mt-0.5 block">
                                            {{ $item->event->tanggal_mulai ? $item->event->tanggal_mulai->translatedFormat('d F Y') : '-' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="block text-slate-400 font-semibold uppercase text-[10px]">Kontak Pendaftar</span>
                                        <span class="font-medium text-slate-800 mt-0.5 block truncate">{{ $item->email }}</span>
                                        <span class="text-slate-500">{{ $item->nomor_telepon }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- State Tidak Ditemukan -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-12 text-center space-y-4 shadow-xs">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-slate-900">
                                Data Pendaftaran Tidak Ditemukan
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                                Tidak ada catatan pendaftaran yang sesuai dengan kata kunci "<strong>{{ $q }}</strong>".
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 max-w-lg mx-auto text-left text-xs text-slate-600 space-y-2 border border-slate-200">
                            <div class="font-bold text-slate-800">Tips Pencarian:</div>
                            <ul class="list-disc pl-4 space-y-1 text-slate-600">
                                <li>Pastikan penulisan alamat email atau nomor telepon sama persis seperti yang diisi saat mendaftar.</li>
                                <li>Jika Anda menggunakan kode pendaftaran, format resminya adalah <code>SGB-EVT-YYMM-XXXXX</code>.</li>
                                <li>Jika masih mengalami kendala, hubungi pengelola kegiatan melalui kontak desa wisata.</li>
                            </ul>
                        </div>
                        <div class="pt-2">
                            <a
                                href="{{ route('kalender.index') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs no-underline transition-all shadow-xs"
                            >
                                <span>Kembali ke Kalender Event</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Petunjuk Panduan Awal (Jika belum mencari) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-2 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                        1
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Gunakan Identitas Anda</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Cukup ketik alamat email atau nomor telepon yang Anda daftarkan di kolom pencarian di atas.
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-2 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                        2
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Temukan Data Tiket</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Sistem SIGEBAT akan menampilkan seluruh riwayat pendaftaran kegiatan budaya yang Anda miliki.
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-2 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                        3
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Buka & Cetak PDF</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Buka tanda bukti resmi dan simpan ke file PDF untuk ditunjukkan saat proses presensi di lokasi acara.
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-portal-layout>
