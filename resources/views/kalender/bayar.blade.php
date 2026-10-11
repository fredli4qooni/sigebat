<x-portal-layout>
    <x-slot:title>Pembayaran Pendaftaran: {{ $pendaftaran->kode_pendaftaran }} — SIGEBAT</x-slot:title>
    <x-slot:description>Instruksi dan konfirmasi pembayaran pendaftaran event budaya di Desa Wisata Kampung Gedung Batin.</x-slot:description>

    <div
        class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 space-y-8"
        x-data="{
            activeTab: 'bank',
            copiedBank: false,
            copiedAmount: false,
            fileName: '',
            previewUrl: null,
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    if (file.type.startsWith('image/')) {
                        this.previewUrl = URL.createObjectURL(file);
                    } else {
                        this.previewUrl = null;
                    }
                }
            }
        }"
    >
        <!-- Navigasi Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <a href="/" class="hover:text-slate-900 no-underline text-slate-500">Beranda</a>
            <span>/</span>
            <a href="{{ route('kalender.index') }}" class="hover:text-slate-900 no-underline text-slate-500">Kalender Event</a>
            <span>/</span>
            <a href="{{ route('event.show', $pendaftaran->event->slug) }}" class="hover:text-slate-900 no-underline text-slate-500 truncate max-w-[150px]">{{ $pendaftaran->event->judul }}</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Pembayaran</span>
        </nav>

        @if(session('info'))
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">&excl;</span>
                <div>
                    <div class="font-bold text-amber-950">Informasi Tagihan</div>
                    <div class="text-xs text-amber-800 mt-0.5 leading-relaxed">{{ session('info') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">&times;</span>
                <div>
                    <div class="font-bold text-rose-950">Terjadi Kesalahan</div>
                    <div class="text-xs text-rose-800 mt-0.5 leading-relaxed">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        <!-- Kartu Tagihan Utama -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl relative overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-3 py-1 rounded-full bg-amber-400/20 border border-amber-300/30 text-amber-300 text-xs font-bold uppercase tracking-wider">
                            Menunggu Pembayaran
                        </span>
                        <span class="font-mono text-xs text-slate-400">
                            Ref: {{ $pendaftaran->kode_pendaftaran }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-3xl font-black text-white tracking-tight">
                        {{ $pendaftaran->event->judul }}
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm">
                        Pemesan: <strong class="text-white">{{ $pendaftaran->nama_lengkap }}</strong> &bull; {{ $pendaftaran->jumlah_peserta }} Peserta ({{ $pendaftaran->nominal_per_tiket ? 'Rp ' . number_format($pendaftaran->nominal_per_tiket, 0, ',', '.') . ' / orang' : 'Berbayar' }})
                    </p>
                </div>

                <!-- Nominal Total Tagihan -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/10 text-left md:text-right flex-shrink-0">
                    <span class="text-[11px] font-bold text-slate-300 uppercase tracking-wider block">Total Tagihan</span>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-400 font-mono mt-1">
                        {{ $pendaftaran->total_tagihan_format }}
                    </div>
                    <button
                        type="button"
                        @click="
                            navigator.clipboard.writeText('{{ $pendaftaran->total_tagihan }}');
                            copiedAmount = true;
                            setTimeout(() => copiedAmount = false, 2500);
                        "
                        class="mt-2 text-[11px] text-slate-300 hover:text-white inline-flex items-center gap-1 font-semibold cursor-pointer underline underline-offset-2"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span x-text="copiedAmount ? 'Nominal Tersalin!' : 'Salin Nominal'"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Kiri: Pilihan Metode Pembayaran & Rekening (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Nav Tab Metode -->
                <div class="bg-slate-100 p-1.5 rounded-2xl flex items-center gap-1 border border-slate-200">
                    <button
                        type="button"
                        @click="activeTab = 'bank'"
                        :class="activeTab === 'bank' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs sm:text-sm text-center transition-all cursor-pointer flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Transfer Bank BUMDes</span>
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'qris'"
                        :class="activeTab === 'qris' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs sm:text-sm text-center transition-all cursor-pointer flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <span>QRIS Desa Wisata</span>
                    </button>
                </div>

                <!-- Tab Konten: Transfer Bank BUMDes -->
                <div x-show="activeTab === 'bank'" class="space-y-4">
                    <!-- Kartu Rekening 1: Bank Lampung -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-black text-xs border border-emerald-200">
                                    BANK LAMPUNG
                                </span>
                                <span class="text-xs text-slate-500 font-medium">BUMDes Gedung Batin Sejahtera</span>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-400">Akun Resmi</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold uppercase block">Nomor Rekening</span>
                                <div class="font-mono text-lg font-black text-slate-900 tracking-wider">
                                    381.03.01.09882.1
                                </div>
                                <span class="text-[11px] text-slate-600 block mt-0.5">a.n. BUMDES KAMPUNG GEDUNG BATIN</span>
                            </div>
                            <button
                                type="button"
                                @click="
                                    navigator.clipboard.writeText('3810301098821');
                                    copiedBank = true;
                                    setTimeout(() => copiedBank = false, 2500);
                                "
                                class="px-3.5 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 font-semibold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all shadow-2xs cursor-pointer flex-shrink-0"
                            >
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="copiedBank ? 'Tersalin!' : 'Salin Rekening'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Kartu Rekening 2: Bank BRI -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 font-black text-xs border border-blue-200">
                                    BANK BRI
                                </span>
                                <span class="text-xs text-slate-500 font-medium">BUMDes Unit Pariwisata</span>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-400">Alternatif</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold uppercase block">Nomor Rekening</span>
                                <div class="font-mono text-lg font-black text-slate-900 tracking-wider">
                                    0182-01-002938-53-9
                                </div>
                                <span class="text-[11px] text-slate-600 block mt-0.5">a.n. UNIT PARIWISATA GEDUNG BATIN</span>
                            </div>
                            <button
                                type="button"
                                @click="
                                    navigator.clipboard.writeText('018201002938539');
                                    alert('Nomor Rekening BRI disalin!');
                                "
                                class="px-3.5 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 font-semibold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-all shadow-2xs cursor-pointer flex-shrink-0"
                            >
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span>Salin Rekening</span>
                            </button>
                        </div>
                    </div>

                    <!-- Petunjuk Transfer -->
                    <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-4 text-xs text-amber-900 space-y-1.5">
                        <div class="font-bold flex items-center gap-1.5 text-amber-950">
                            <span>Petunjuk Pembayaran Transfer:</span>
                        </div>
                        <ol class="list-decimal pl-4 space-y-1 text-amber-800 leading-relaxed">
                            <li>Transfer tepat sejumlah <strong class="text-amber-950">{{ $pendaftaran->total_tagihan_format }}</strong>.</li>
                            <li>Sertakan berita transfer / keterangan: <code class="bg-amber-100 px-1 py-0.5 rounded font-mono">{{ $pendaftaran->kode_pendaftaran }}</code>.</li>
                            <li>Simpan struk / tangkapan layar bukti transfer dan unggah pada formulir di samping.</li>
                        </ol>
                    </div>
                </div>

                <!-- Tab Konten: QRIS Desa Wisata -->
                <div x-show="activeTab === 'qris'" x-cloak class="space-y-4">
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs text-center space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-xs font-bold">
                            <span>QRIS STANDAR PEMBAYARAN NASIONAL</span>
                        </div>

                        <!-- Tampilan QR Code Elegan -->
                        <div class="w-56 h-56 mx-auto bg-white p-3 rounded-2xl border-2 border-slate-800 shadow-md flex flex-col items-center justify-between">
                            <div class="text-[10px] font-black tracking-widest text-slate-800 uppercase">
                                QRIS &bull; DESA WISATA GEDUNG BATIN
                            </div>
                            <div class="w-40 h-40 bg-slate-50 border border-slate-300 rounded-xl p-2 flex items-center justify-center">
                                <img
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('event.pendaftaran.bayar', $pendaftaran->kode_pendaftaran)) }}"
                                    alt="QRIS Desa Wisata"
                                    class="w-full h-full object-contain"
                                >
                            </div>
                            <div class="text-[9px] font-mono text-slate-500">
                                NMID: ID1020038847291 &bull; A01
                            </div>
                        </div>

                        <div class="space-y-1">
                            <div class="text-sm font-bold text-slate-900">
                                BUMDes Wisata Budaya Gedung Batin
                            </div>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                                Buka aplikasi m-Banking (BCA, BRI, Mandiri, BNI, Bank Lampung) atau e-Wallet (Gopay, OVO, Dana, ShopeePay), lalu arahkan kamera ke kode QR di atas.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KOTAK SIMULASI CEPAT SIDANG SKRIPSI (Penting untuk Dosen Penguji / Demo) -->
                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-2xl p-5 border border-indigo-700/60 shadow-md space-y-3">
                    <div class="flex items-center gap-2 text-indigo-300 font-bold text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Mode Sidang Skripsi (Fitur Demo Cepat)</span>
                    </div>
                    <p class="text-xs text-indigo-200 leading-relaxed">
                        Disediakan khusus untuk kemudahan simulasi pengujian sidang. Mengubah status pembayaran menjadi <strong>LUNAS</strong> secara instan tanpa perlu mengunggah struk nyata.
                    </p>
                    <form action="{{ route('event.pendaftaran.simulasi-bayar', $pendaftaran->kode_pendaftaran) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            onclick="return confirm('Jalankan simulasi bayar instan untuk keperluan sidang skripsi?')"
                            class="w-full py-2.5 px-4 bg-gradient-to-r from-indigo-500 to-emerald-500 hover:from-indigo-400 hover:to-emerald-400 text-white font-bold text-xs rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <span>Simulasikan Pembayaran Berhasil (Bypass Sidang)</span>
                            <span>&rarr;</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Form Unggah Bukti Transfer (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
                    <div class="space-y-1 border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">
                            Konfirmasi Pembayaran
                        </h2>
                        <p class="text-xs text-slate-500">
                            Unggah bukti transfer untuk diverifikasi oleh pengelola kegiatan.
                        </p>
                    </div>

                    <form
                        action="{{ route('event.pendaftaran.proses-bayar', $pendaftaran->kode_pendaftaran) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        @csrf

                        <!-- Pilihan Metode Pembayaran yang Digunakan -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Metode Pembayaran <span class="text-rose-500">*</span>
                            </label>
                            <select
                                name="metode_pembayaran"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white"
                            >
                                <option value="transfer_bank" {{ old('metode_pembayaran', $pendaftaran->metode_pembayaran) === 'transfer_bank' ? 'selected' : '' }}>
                                    Transfer Bank (Bank Lampung / BRI)
                                </option>
                                <option value="qris" {{ old('metode_pembayaran', $pendaftaran->metode_pembayaran) === 'qris' ? 'selected' : '' }}>
                                    Scan QRIS Desa Wisata
                                </option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Unggah File Bukti Pembayaran -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Unggah Bukti Struk / Transfer <span class="text-rose-500">*</span>
                            </label>

                            <div class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-4 text-center transition-all bg-slate-50/50">
                                <input
                                    type="file"
                                    id="bukti_pembayaran"
                                    name="bukti_pembayaran"
                                    accept="image/jpeg,image/png,image/jpg,application/pdf"
                                    required
                                    @change="handleFileSelect"
                                    class="hidden"
                                >
                                <label for="bukti_pembayaran" class="cursor-pointer block space-y-2">
                                    <!-- Preview jika berupa gambar -->
                                    <template x-if="previewUrl">
                                        <div class="relative w-full h-44 rounded-xl overflow-hidden border border-slate-200 mx-auto">
                                            <img :src="previewUrl" alt="Preview Struk" class="w-full h-full object-cover">
                                        </div>
                                    </template>

                                    <!-- Placeholder jika belum pilih file -->
                                    <template x-if="!previewUrl">
                                        <div class="py-4 space-y-2">
                                            <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                            </div>
                                            <div class="text-xs font-bold text-slate-800">
                                                Klik untuk Pilih Berkas
                                            </div>
                                            <div class="text-[11px] text-slate-500">
                                                JPG, PNG, atau PDF (Maks. 2 MB)
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="fileName" class="text-xs font-mono font-bold text-emerald-700 truncate px-2" x-text="fileName"></div>
                                </label>
                            </div>

                            @error('bukti_pembayaran')
                                <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catatan Pembayar (Opsional) -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Catatan / Nama Pemilik Rekening (Opsional)
                            </label>
                            <input
                                type="text"
                                name="catatan_pembayar"
                                value="{{ old('catatan_pembayar') }}"
                                placeholder="Contoh: Transfer atas nama Budi Santoso"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            >
                        </div>

                        <!-- Tombol Kirim Bukti -->
                        <button
                            type="submit"
                            class="w-full py-3 px-5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer mt-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Kirim Bukti Pembayaran</span>
                        </button>
                    </form>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                        <a
                            href="{{ route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran) }}"
                            class="text-slate-500 hover:text-slate-900 no-underline font-semibold"
                        >
                            &larr; Lihat Bukti Saat Ini
                        </a>
                        <a
                            href="{{ route('kalender.index') }}"
                            class="text-slate-500 hover:text-slate-900 no-underline"
                        >
                            Kalender Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-portal-layout>
