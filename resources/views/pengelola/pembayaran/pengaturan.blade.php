<x-app-layout>
    <x-slot:title>Pengaturan Rekening &amp; QRIS — SIGEBAT</x-slot:title>
    <x-slot:header>Pengaturan Rekening Bank &amp; QRIS Desa Wisata</x-slot:header>

    <div
        class="max-w-4xl mx-auto space-y-6"
        x-data="{
            previewUrl: null,
            fileName: '',
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    if (file.type.startsWith('image/')) {
                        this.previewUrl = URL.createObjectURL(file);
                    }
                }
            }
        }"
    >
        <!-- Navigasi Pintas Kembali -->
        <div class="flex items-center justify-between">
            <a
                href="{{ route(auth()->user()->role === 'admin' ? 'admin.pembayaran.index' : 'pengelola.pembayaran.index') }}"
                class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors no-underline"
            >
                <span>&larr;</span>
                <span>Kembali ke Monitoring Kas &amp; Pembayaran</span>
            </a>
            <span class="text-xs text-slate-400 font-mono">
                SIM Konfigurasi Keuangan &bull; BUMDes Gedung Batin
            </span>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
                <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">&check;</span>
                <div>
                    <div class="font-bold text-emerald-950">Berhasil Disimpan</div>
                    <div class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- Banner Info Header -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-sm relative overflow-hidden">
            <div class="space-y-2 max-w-2xl relative z-10">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold">
                    Manajemen Rekening BUMDes &bull; SIM SIGEBAT
                </span>
                <h1 class="text-xl sm:text-3xl font-bold tracking-tight text-white">
                    Kelola Rekening Kas &amp; QRIS Pembayaran
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    Data yang diatur di halaman ini akan otomatis muncul pada halaman pembayaran wisatawan ketika mereka mendaftar kegiatan budaya berbayar.
                </p>
            </div>
        </div>

        <form
            action="{{ route(auth()->user()->role === 'admin' ? 'admin.pengaturan-pembayaran.update' : 'pengelola.pengaturan-pembayaran.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <!-- Seksi 1: Rekening Utama BUMDes -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            Rekening Bank Utama (BUMDes)
                        </h2>
                        <p class="text-xs text-slate-500">
                            Rekening prioritas yang ditampilkan pertama kali kepada calon peserta event.
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-bold text-[10px] uppercase border border-emerald-200">
                        Wajib Diisi
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Nama Bank <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_bank_utama"
                            value="{{ old('nama_bank_utama', $setting->nama_bank_utama) }}"
                            required
                            placeholder="Contoh: BANK LAMPUNG"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('nama_bank_utama')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Nomor Rekening <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nomor_rekening_utama"
                            value="{{ old('nomor_rekening_utama', $setting->nomor_rekening_utama) }}"
                            required
                            placeholder="Contoh: 381.03.01.09882.1"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('nomor_rekening_utama')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Atas Nama Rekening <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="atas_nama_utama"
                            value="{{ old('atas_nama_utama', $setting->atas_nama_utama) }}"
                            required
                            placeholder="Contoh: BUMDES GEDUNG BATIN"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('atas_nama_utama')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Seksi 2: Rekening Sekunder / Alternatif -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            Rekening Bank Alternatif (Opsional)
                        </h2>
                        <p class="text-xs text-slate-500">
                            Rekening cadangan/pilihan lain jika peserta menggunakan jaringan bank berbeda.
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold text-[10px] uppercase">
                        Opsional
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Nama Bank Alternatif
                        </label>
                        <input
                            type="text"
                            name="nama_bank_sekunder"
                            value="{{ old('nama_bank_sekunder', $setting->nama_bank_sekunder) }}"
                            placeholder="Contoh: BANK BRI"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('nama_bank_sekunder')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Nomor Rekening Alternatif
                        </label>
                        <input
                            type="text"
                            name="nomor_rekening_sekunder"
                            value="{{ old('nomor_rekening_sekunder', $setting->nomor_rekening_sekunder) }}"
                            placeholder="Contoh: 0182-01-002938-53-9"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('nomor_rekening_sekunder')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Atas Nama Rekening Alternatif
                        </label>
                        <input
                            type="text"
                            name="atas_nama_sekunder"
                            value="{{ old('atas_nama_sekunder', $setting->atas_nama_sekunder) }}"
                            placeholder="Contoh: UNIT PARIWISATA GEDUNG BATIN"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('atas_nama_sekunder')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Seksi 3: QRIS Standar Pembayaran Nasional -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900">
                        Kode QRIS Resmi Desa Wisata
                    </h2>
                    <p class="text-xs text-slate-500">
                        Unggah gambar barcode QRIS statis resmi BUMDes dari perbankan atau e-wallet nasional.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Nama Merchant / Instansi QRIS <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_qris"
                            value="{{ old('nama_qris', $setting->nama_qris) }}"
                            required
                            placeholder="Contoh: BUMDes Wisata Budaya Gedung Batin"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('nama_qris')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            NMID QRIS (Nomor Identitas Nasional)
                        </label>
                        <input
                            type="text"
                            name="nmid_qris"
                            value="{{ old('nmid_qris', $setting->nmid_qris) }}"
                            placeholder="Contoh: ID1020038847291"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 font-mono text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                        @error('nmid_qris')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Unggah Gambar Barcode QRIS -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start pt-2">
                    <!-- Kolom Preview Gambar -->
                    <div class="md:col-span-4 space-y-2 text-center">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">
                            Preview Gambar QRIS
                        </span>
                        
                        <div class="w-48 h-48 mx-auto bg-slate-50 rounded-2xl border-2 border-dashed border-slate-300 p-2 flex items-center justify-center overflow-hidden shadow-2xs">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Preview QRIS Baru" class="w-full h-full object-contain rounded-xl">
                            </template>

                            <template x-if="!previewUrl && '{{ $setting->qris_url }}'">
                                <img src="{{ $setting->qris_url }}" alt="QRIS Saat Ini" class="w-full h-full object-contain rounded-xl">
                            </template>

                            <template x-if="!previewUrl && !'{{ $setting->qris_url }}'">
                                <div class="space-y-1 text-slate-400">
                                    <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    <span class="text-[10px] block font-medium">QR Standar Sistem</span>
                                </div>
                            </template>
                        </div>

                        @if($setting->qris_image)
                            <label class="inline-flex items-center gap-2 text-xs text-rose-600 font-semibold cursor-pointer pt-1">
                                <input type="checkbox" name="hapus_qris" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                <span>Hapus Gambar QRIS Saat Ini</span>
                            </label>
                        @endif
                    </div>

                    <!-- Kolom Input File -->
                    <div class="md:col-span-8 space-y-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Pilih Berkas Gambar QRIS Baru (Opsional)
                        </label>
                        <div class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-6 text-center transition-all bg-slate-50/50">
                            <input
                                type="file"
                                id="qris_image"
                                name="qris_image"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                @change="handleFileSelect"
                                class="hidden"
                            >
                            <label for="qris_image" class="cursor-pointer block space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                </div>
                                <div class="text-xs font-bold text-slate-800">
                                    Klik di sini untuk memilih gambar QRIS
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    JPG, PNG, atau WebP (Maksimal 3 MB)
                                </div>
                                <div x-show="fileName" class="text-xs font-mono font-bold text-emerald-700 truncate px-2" x-text="fileName"></div>
                            </label>
                        </div>
                        @error('qris_image')
                            <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Seksi 4: Petunjuk Tambahan (Opsional) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-3">
                <div class="border-b border-slate-100 pb-2">
                    <h2 class="text-base font-bold text-slate-900">
                        Catatan &amp; Petunjuk Tambahan untuk Wisatawan
                    </h2>
                    <p class="text-xs text-slate-500">
                        Pesan khusus yang akan tampil pada kotak panduan pembayaran di sisi wisatawan.
                    </p>
                </div>
                <div>
                    <textarea
                        name="petunjuk_transfer"
                        rows="3"
                        placeholder="Contoh: Pembayaran dapat dilakukan 24 jam. Verifikasi kas dilakukan oleh pengelola maksimal 1x24 jam setelah bukti diunggah."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >{{ old('petunjuk_transfer', $setting->petunjuk_transfer) }}</textarea>
                    @error('petunjuk_transfer')
                        <div class="text-[11px] text-rose-600 font-semibold">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tombol Simpan Aksi -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a
                    href="{{ route(auth()->user()->role === 'admin' ? 'admin.pembayaran.index' : 'pengelola.pembayaran.index') }}"
                    class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm no-underline transition-all"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm transition-all shadow-md flex items-center gap-2 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Pengaturan Pembayaran</span>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
