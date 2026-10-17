<x-portal-layout>
    <x-slot:title>Reservasi Sewa {{ $fasilitas->nama }} — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Formulir penyewaan fasilitas harian resmi di Desa Wisata Kampung Gedung Batin terintegrasi kas BUMDes.</x-slot:description>

    <div class="py-8 md:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Header Navigasi Breadcrumb -->
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-emerald-700 no-underline">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('fasilitas.index') }}" class="hover:text-emerald-700 no-underline">Direktori Fasilitas</a>
                    <span>/</span>
                    <span class="text-slate-800 font-semibold truncate max-w-xs">Sewa {{ $fasilitas->nama }}</span>
                </nav>

                <a
                    href="{{ route('fasilitas.index') }}"
                    class="h-9 px-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl transition-all inline-flex items-center gap-1.5 no-underline shadow-2xs"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>

            <!-- Banner Utama -->
            <div class="bg-slate-900 border border-slate-800 text-white rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-2xl">
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">
                            Pemesanan Sewa Harian
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                            Formulir Reservasi {{ $fasilitas->nama }}
                        </h1>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                            Nikmati fasilitas desa dengan pemesanan resmi. Tagihan dihitung berbasis harian dan diteruskan ke kas BUMDes.
                        </p>
                    </div>
                    <div class="text-right md:border-l md:border-slate-800 md:pl-6 flex-shrink-0">
                        <div class="text-xs text-slate-400">Tarif Sewa Harian</div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400">
                            {{ $fasilitas->harga_sewa_format }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesan Error / Validasi Tanggal Bentrok -->
            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl space-y-1 text-xs text-rose-700">
                    <div class="font-bold flex items-center gap-1.5 text-rose-800">
                        <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Mohon periksa kembali formulir Anda:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 pl-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div
                class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start"
                x-data="{
                    hargaPerHari: {{ (int) $fasilitas->harga_sewa_per_hari }},
                    tanggalMulai: '{{ old('tanggal_mulai', now()->toDateString()) }}',
                    tanggalSelesai: '{{ old('tanggal_selesai', now()->toDateString()) }}',
                    get durasiHari() {
                        if (!this.tanggalMulai || !this.tanggalSelesai) return 1;
                        const start = new Date(this.tanggalMulai);
                        const end = new Date(this.tanggalSelesai);
                        if (isNaN(start) || isNaN(end) || end < start) return 1;
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                        return diffDays > 0 ? diffDays : 1;
                    },
                    get totalTagihan() {
                        return this.durasiHari * this.hargaPerHari;
                    },
                    formatRupiah(num) {
                        return 'Rp ' + Number(num).toLocaleString('id-ID');
                    }
                }"
            >
                <!-- Formulir Input -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                    <form method="POST" action="{{ route('fasilitas.sewa.store', $fasilitas) }}" class="space-y-6">
                        @csrf

                        <div class="space-y-4">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">
                                Data Identitas Penyewa (Penanggung Jawab)
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label for="nama_penyewa" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Nama Lengkap <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="nama_penyewa"
                                        name="nama_penyewa"
                                        value="{{ old('nama_penyewa') }}"
                                        required
                                        placeholder="Nama pemesan sesuai KTP"
                                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                                    >
                                </div>

                                <div>
                                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Email Aktif <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        placeholder="alamat@email.com"
                                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                                    >
                                </div>

                                <div>
                                    <label for="nomor_telepon" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Nomor WhatsApp / HP <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        id="nomor_telepon"
                                        name="nomor_telepon"
                                        value="{{ old('nomor_telepon') }}"
                                        required
                                        placeholder="Contoh: 081234567890"
                                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                                    >
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="asal_instansi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Asal Instansi / Komunitas / Kota Asal (Opsional)
                                    </label>
                                    <input
                                        type="text"
                                        id="asal_instansi"
                                        name="asal_instansi"
                                        value="{{ old('asal_instansi') }}"
                                        placeholder="Contoh: Keluarga Bpk. Ahmad / Rombongan Wisata Jakarta"
                                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">
                                Rincian Tanggal & Durasi Sewa
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="tanggal_mulai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tanggal Mulai Sewa <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="tanggal_mulai"
                                        name="tanggal_mulai"
                                        x-model="tanggalMulai"
                                        min="{{ now()->toDateString() }}"
                                        required
                                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                                    >
                                </div>

                                <div>
                                    <label for="tanggal_selesai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tanggal Selesai Sewa <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="tanggal_selesai"
                                        name="tanggal_selesai"
                                        x-model="tanggalSelesai"
                                        :min="tanggalMulai"
                                        required
                                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                                    >
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="jumlah_tamu" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Jumlah Tamu / Pengguna <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        id="jumlah_tamu"
                                        name="jumlah_tamu"
                                        value="{{ old('jumlah_tamu', 1) }}"
                                        min="1"
                                        max="{{ $fasilitas->kapasitas ?: 100 }}"
                                        required
                                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                                    >
                                    @if($fasilitas->kapasitas)
                                        <p class="text-[11px] text-slate-500 mt-1">
                                            Kapasitas maksimal yang direkomendasikan adalah {{ $fasilitas->kapasitas }} orang.
                                        </p>
                                    @endif
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Catatan / Permintaan Khusus (Opsional)
                                    </label>
                                    <textarea
                                        id="catatan"
                                        name="catatan"
                                        rows="2"
                                        placeholder="Contoh: Perkiraan waktu check-in tiba sore pukul 15.00 WIB..."
                                        class="p-3 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                                    >{{ old('catatan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan Hitungan Realtime -->
                        <div class="p-4 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span>Perhitungan Durasi:</span>
                                <span class="font-bold text-slate-900" x-text="durasiHari + ' Hari'">1 Hari</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span>Tarif Harian:</span>
                                <span class="font-semibold text-slate-900">{{ $fasilitas->harga_sewa_format }}</span>
                            </div>
                            <div class="pt-2 border-t border-emerald-200 flex items-center justify-between text-sm sm:text-base font-extrabold text-emerald-900">
                                <span>Estimasi Total Biaya:</span>
                                <span class="text-lg sm:text-xl font-black text-emerald-700" x-text="formatRupiah(totalTagihan)">
                                    {{ $fasilitas->harga_sewa_format }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-emerald-600/30 cursor-pointer flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Ajukan Reservasi & Lanjut Pembayaran</span>
                            </button>
                            <p class="text-center text-[11px] text-slate-400 mt-2">
                                Kode sewa resmi akan diterbitkan setelah formulir diajukan.
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Kolom Ringkasan Fasilitas & Jadwal -->
                <div class="space-y-6">
                    <!-- Kartu Info Fasilitas -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        @if($fasilitas->foto)
                            <div class="h-44 bg-slate-100 overflow-hidden">
                                <img src="{{ $fasilitas->foto_url }}" alt="{{ $fasilitas->nama }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="p-5 space-y-3">
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-sky-50 text-sky-800 border border-sky-200">
                                {{ $fasilitas->jenis?->nama ?? 'Fasilitas' }}
                            </span>
                            <h3 class="text-lg font-bold text-slate-900 leading-tight">
                                {{ $fasilitas->nama }}
                            </h3>
                            <div class="text-xs text-slate-500 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $fasilitas->keterangan_lokasi }}</span>
                            </div>

                            @if($fasilitas->fasilitas_include)
                                <div class="pt-2 border-t border-slate-100">
                                    <div class="text-xs font-bold text-slate-800 mb-1">Fasilitas Termasuk:</div>
                                    <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        {{ $fasilitas->fasilitas_include }}
                                    </p>
                                </div>
                            @endif

                            @if($fasilitas->deskripsi)
                                <p class="text-xs text-slate-500 leading-relaxed pt-1">
                                    {{ $fasilitas->deskripsi }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Jadwal Terisi / Booking Aktif -->
                    @if($bookingAktif->count() > 0)
                        <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-4 sm:p-5 space-y-2.5">
                            <div class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Tanggal Terisi Saat Ini:</span>
                            </div>
                            <p class="text-[11px] text-amber-800">
                                Tanggal-tanggal berikut telah dipesan oleh tamu lain dan tidak dapat dipilih:
                            </p>
                            <ul class="text-xs space-y-1 text-amber-900 font-semibold list-disc list-inside">
                                @foreach($bookingAktif as $b)
                                    <li>
                                        {{ \Carbon\Carbon::parse($b->tanggal_mulai)->translatedFormat('d M Y') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse($b->tanggal_selesai)->translatedFormat('d M Y') }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-portal-layout>
