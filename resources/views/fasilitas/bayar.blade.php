<x-portal-layout>
    <x-slot:title>Pembayaran Sewa {{ $penyewaan->kode_sewa }} — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Instruksi pembayaran dan unggah bukti transfer sewa fasilitas terintegrasi rekening kas BUMDes Kampung Gedung Batin.</x-slot:description>

    <div class="py-8 md:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Header Ringkas -->
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-emerald-700 no-underline">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('fasilitas.index') }}" class="hover:text-emerald-700 no-underline">Fasilitas</a>
                    <span>/</span>
                    <span class="text-slate-800 font-semibold">Pembayaran {{ $penyewaan->kode_sewa }}</span>
                </nav>

                <a
                    href="{{ route('sewa.bukti', $penyewaan->kode_sewa) }}"
                    class="h-9 px-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl transition-all inline-flex items-center gap-1.5 no-underline shadow-2xs"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>Lihat Lembar Bukti</span>
                </a>
            </div>

            <!-- Banner Tagihan -->
            <div class="bg-slate-900 border border-slate-800 text-white rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-400/30">
                                {{ $penyewaan->status_pembayaran_label }}
                            </span>
                            <span class="text-xs text-slate-400">Kode Sewa:</span>
                            <code class="px-2 py-0.5 rounded bg-slate-800 text-emerald-400 font-mono text-xs font-bold tracking-wider">
                                {{ $penyewaan->kode_sewa }}
                            </code>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                            Tagihan Sewa {{ $penyewaan->fasilitas?->nama }}
                        </h1>
                        <p class="text-slate-300 text-xs sm:text-sm">
                            Untuk: <strong>{{ $penyewaan->nama_penyewa }}</strong> ({{ $penyewaan->durasi_hari }} Hari &bull; {{ $penyewaan->tanggal_mulai->translatedFormat('d M Y') }} s/d {{ $penyewaan->tanggal_selesai->translatedFormat('d M Y') }})
                        </p>
                    </div>

                    <div class="text-left md:text-right md:border-l md:border-slate-800 md:pl-6 flex-shrink-0">
                        <div class="text-xs text-slate-400">Total Nominal Pembayaran</div>
                        <div class="text-3xl font-black text-emerald-400">
                            {{ $penyewaan->total_tagihan_format }}
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Harap transfer sesuai nominal tepat</div>
                    </div>
                </div>
            </div>

            @if(session('info'))
                <div class="p-4 bg-sky-50 border border-sky-200 text-sky-800 text-xs rounded-2xl flex items-center gap-2 font-medium">
                    <svg class="w-4 h-4 text-sky-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <!-- Metode Pembayaran Rekening Kas Desa & QRIS -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span>Pilihan Rekening Pembayaran Resmi BUMDes</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <!-- Rekening Bank Utama -->
                    <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-3" x-data="{ copied: false }">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rekening Utama</span>
                            <span class="text-xs font-extrabold text-sky-700 bg-sky-50 px-2 py-0.5 rounded border border-sky-200">{{ $setting->nama_bank_utama }}</span>
                        </div>
                        <div class="space-y-1">
                            <div class="text-xs text-slate-500">Nomor Rekening:</div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-base sm:text-lg font-mono font-bold text-slate-900 tracking-wide">{{ $setting->nomor_rekening_utama }}</span>
                                <button
                                    type="button"
                                    @click="navigator.clipboard.writeText('{{ $setting->nomor_rekening_utama_raw }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="h-8 px-2.5 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 rounded-lg text-xs font-semibold cursor-pointer transition-all inline-flex items-center gap-1 shadow-2xs"
                                >
                                    <span x-show="!copied">Salin</span>
                                    <span x-show="copied" class="text-emerald-600 font-bold">Tersalin!</span>
                                </button>
                            </div>
                            <div class="text-xs text-slate-600">a.n. <strong>{{ $setting->atas_nama_utama }}</strong></div>
                        </div>
                    </div>

                    <!-- Rekening Bank Sekunder (jika ada) -->
                    @if($setting->nama_bank_sekunder && $setting->nomor_rekening_sekunder)
                        <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-3" x-data="{ copied: false }">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rekening Alternatif</span>
                                <span class="text-xs font-extrabold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">{{ $setting->nama_bank_sekunder }}</span>
                            </div>
                            <div class="space-y-1">
                                <div class="text-xs text-slate-500">Nomor Rekening:</div>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-base sm:text-lg font-mono font-bold text-slate-900 tracking-wide">{{ $setting->nomor_rekening_sekunder }}</span>
                                    <button
                                        type="button"
                                        @click="navigator.clipboard.writeText('{{ $setting->nomor_rekening_sekunder_raw }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="h-8 px-2.5 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 rounded-lg text-xs font-semibold cursor-pointer transition-all inline-flex items-center gap-1 shadow-2xs"
                                    >
                                        <span x-show="!copied">Salin</span>
                                        <span x-show="copied" class="text-emerald-600 font-bold">Tersalin!</span>
                                    </button>
                                </div>
                                <div class="text-xs text-slate-600">a.n. <strong>{{ $setting->atas_nama_sekunder }}</strong></div>
                            </div>
                        </div>
                    @endif

                    <!-- QRIS Nasional -->
                    @if($setting->qris_url)
                        <div class="md:col-span-2 p-5 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col sm:flex-row items-center gap-6">
                            <div class="w-36 h-36 bg-white p-2 rounded-xl border border-slate-300 shadow-2xs flex-shrink-0 flex items-center justify-center">
                                <img src="{{ $setting->qris_url }}" alt="QRIS Desa" class="w-full h-full object-contain">
                            </div>
                            <div class="space-y-1.5 text-center sm:text-left">
                                <div class="inline-block px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                                    QRIS Bebas Biaya Admin
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">
                                    {{ $setting->nama_qris ?? 'BUMDes Gedung Batin' }}
                                </h3>
                                <p class="text-xs text-slate-500 max-w-md leading-relaxed">
                                    Dapat dipindai melalui seluruh aplikasi Mobile Banking (BCA, Mandiri, BRI, BNI, dll) dan dompet digital (GoPay, OVO, Dana, ShopeePay).
                                </p>
                                @if($setting->nmid_qris)
                                    <div class="text-[11px] text-slate-400 font-mono">NMID: {{ $setting->nmid_qris }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Formulir Upload Bukti Pembayaran -->
                <div class="pt-6 border-t border-slate-200">
                    <form
                        method="POST"
                        action="{{ route('sewa.proses-bayar', $penyewaan->kode_sewa) }}"
                        enctype="multipart/form-data"
                        class="space-y-5"
                        x-data="{
                            previewUrl: null,
                            handleFile(e) {
                                const file = e.target.files[0];
                                if (file) {
                                    this.previewUrl = URL.createObjectURL(file);
                                }
                            }
                        }"
                    >
                        @csrf

                        <h3 class="text-sm font-bold text-slate-900">
                            Konfirmasi &amp; Unggah Bukti Transfer
                        </h3>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Metode yang Digunakan <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="p-3.5 border border-slate-200 rounded-xl flex items-center gap-3 cursor-pointer hover:bg-slate-50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40 transition-all">
                                    <input type="radio" name="metode_pembayaran" value="transfer_bank" required checked class="text-emerald-600 focus:ring-emerald-500">
                                    <div class="text-xs font-semibold text-slate-800">Transfer Bank BUMDes</div>
                                </label>
                                <label class="p-3.5 border border-slate-200 rounded-xl flex items-center gap-3 cursor-pointer hover:bg-slate-50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40 transition-all">
                                    <input type="radio" name="metode_pembayaran" value="qris" required class="text-emerald-600 focus:ring-emerald-500">
                                    <div class="text-xs font-semibold text-slate-800">Pembayaran QRIS Desa</div>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('metode_pembayaran')" class="mt-1" />
                        </div>

                        <div class="space-y-2">
                            <label for="bukti_pembayaran" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Berkas Foto Bukti Transfer / Struk <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="file"
                                id="bukti_pembayaran"
                                name="bukti_pembayaran"
                                required
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                @change="handleFile"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer"
                            >
                            <p class="text-[11px] text-slate-500">Format gambar: JPG, PNG, atau WebP. Maksimal 3 MB.</p>
                            <x-input-error :messages="$errors->get('bukti_pembayaran')" class="mt-1" />

                            <template x-if="previewUrl">
                                <div class="mt-3 p-3 bg-slate-50 border border-slate-200 rounded-xl inline-block">
                                    <span class="block text-[11px] font-semibold text-slate-500 mb-1.5">Pratinjau Bukti:</span>
                                    <img :src="previewUrl" alt="Pratinjau Bukti" class="w-64 h-48 object-cover rounded-xl border border-slate-200">
                                </div>
                            </template>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button
                                type="submit"
                                class="h-11 px-7 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-md hover:shadow-emerald-600/30 cursor-pointer flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Kirim Bukti Pembayaran</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-portal-layout>
