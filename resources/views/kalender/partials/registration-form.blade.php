<!-- Panel Partisipasi & Formulir Pendaftaran Event Budaya -->
<div id="form-pendaftaran-event" class="bg-white rounded-2xl border-2 border-emerald-500/80 shadow-md p-6 sm:p-7 space-y-6">
    <!-- Header Formulir -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Partisipasi Resmi Wisatawan</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Pendaftaran Kehadiran Kegiatan
            </h3>
            <p class="text-xs sm:text-sm text-slate-600">
                Isi formulir di bawah untuk mendaftarkan kehadiran Anda atau rombongan pada kegiatan adat ini.
            </p>
        </div>

        <!-- Indikator Kuota / Status -->
        <div class="text-left sm:text-right flex-shrink-0">
            @if($event->is_pendaftaran_bisa_dilakukan)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                    Pendaftaran Dibuka
                </span>
                @if($event->kuota_peserta)
                    <div class="text-xs font-semibold text-slate-700 mt-1.5">
                        Tersedia <span class="text-emerald-700 font-bold">{{ $event->sisa_kuota }}</span> dari {{ $event->kuota_peserta }} Kuota
                    </div>
                @else
                    <div class="text-xs text-slate-500 mt-1">
                        Kapasitas Terbuka (Tanpa Batas Kuota)
                    </div>
                @endif
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300">
                    Pendaftaran Ditutup
                </span>
            @endif
        </div>
    </div>

    <!-- Progress Bar Kuota (Jika ada batasan kuota) -->
    @if($event->kuota_peserta && $event->is_pendaftaran_bisa_dilakukan)
        @php
            $persentase = min(100, round(($event->total_peserta_terdaftar / $event->kuota_peserta) * 100));
        @endphp
        <div class="space-y-1.5 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
            <div class="flex justify-between text-xs font-medium text-slate-600">
                <span>Keterisian Kuota Acara:</span>
                <span class="font-bold text-slate-900">{{ $event->total_peserta_terdaftar }} / {{ $event->kuota_peserta }} Peserta ({{ $persentase }}%)</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                <div 
                    class="bg-emerald-600 h-2.5 rounded-full transition-all duration-500" 
                    style="width: {{ $persentase }}%"
                ></div>
            </div>
        </div>
    @endif

    <!-- Alert Notifikasi Flash Error / Success -->
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-start gap-2.5">
            <span class="font-bold text-base">&times;</span>
            <span class="leading-relaxed">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm space-y-1">
            <div class="font-bold">Mohon periksa kembali isian formulir:</div>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tampilan Jika Pendaftaran Ditutup -->
    @if(! $event->is_pendaftaran_bisa_dilakukan)
        <div class="py-6 px-4 bg-slate-50 rounded-xl border border-slate-200 text-center space-y-2">
            <div class="w-10 h-10 mx-auto rounded-full bg-slate-200 text-slate-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="font-bold text-slate-800 text-sm">
                @if($event->status_turunan === 'Selesai')
                    Pendaftaran Telah Berakhir
                @elseif($event->is_kuota_penuh)
                    Batas Kuota Peserta Telah Terpenuhi
                @else
                    Pendaftaran Daring Sedang Ditutup
                @endif
            </div>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                @if($event->status_turunan === 'Selesai')
                    Kegiatan ini telah selesai dilaksanakan. Anda dapat melihat dokumentasi atau mengikuti agenda budaya mendatang lainnya.
                @elseif($event->is_kuota_penuh)
                    Seluruh kuota untuk kegiatan ini telah terisi penuh. Silakan hubungi pengelola kampung untuk informasi ketersediaan tempat langsung di lokasi.
                @else
                    Pengelola belum membuka akses registrasi online untuk kegiatan ini. Silakan pantau kembali secara berkala.
                @endif
            </p>
        </div>
    @else
        <!-- Formulir Input Pendaftaran Aktif -->
        <form 
            action="{{ route('event.daftar', $event->slug) }}" 
            method="POST" 
            class="space-y-4"
            x-data="{ submitting: false }"
            @submit="submitting = true"
        >
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nama Lengkap -->
                <div class="space-y-1">
                    <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_lengkap"
                        id="nama_lengkap"
                        required
                        value="{{ old('nama_lengkap') }}"
                        placeholder="Contoh: Budi Prasetyo"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    >
                </div>

                <!-- Nomor WhatsApp / HP -->
                <div class="space-y-1">
                    <label for="nomor_telepon" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        No. WhatsApp / HP <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="tel"
                        name="nomor_telepon"
                        id="nomor_telepon"
                        required
                        value="{{ old('nomor_telepon') }}"
                        placeholder="Contoh: 081234567890"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Alamat Email -->
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="budi@example.com"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    >
                </div>

                <!-- Asal Daerah / Instansi -->
                <div class="space-y-1">
                    <label for="asal_instansi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Asal Kota / Instansi <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="asal_instansi"
                        id="asal_instansi"
                        required
                        value="{{ old('asal_instansi') }}"
                        placeholder="Contoh: Bandar Lampung / Unila"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    >
                </div>
            </div>

            <!-- Jumlah Peserta / Rombongan -->
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <label for="jumlah_peserta" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Jumlah Peserta (Orang) <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-xs text-slate-500 font-normal">Perorangan atau rombongan (1 - {{ $event->sisa_kuota ? min(20, $event->sisa_kuota) : 20 }} orang)</span>
                </div>
                <input
                    type="number"
                    name="jumlah_peserta"
                    id="jumlah_peserta"
                    required
                    min="1"
                    max="{{ $event->sisa_kuota ? min(20, $event->sisa_kuota) : 20 }}"
                    value="{{ old('jumlah_peserta', 1) }}"
                    class="w-full sm:w-48 px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                >
            </div>

            <!-- Catatan / Kebutuhan Khusus -->
            <div class="space-y-1">
                <label for="catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Catatan Tambahan <span class="text-xs font-normal text-slate-400 capitalize">(Opsional)</span>
                </label>
                <textarea
                    name="catatan"
                    id="catatan"
                    rows="2"
                    placeholder="Contoh: Kebutuhan parkir bus, pendampingan lansia, atau riset akademis"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                >{{ old('catatan') }}</textarea>
            </div>

            <!-- Tombol Submit & Disclaimer -->
            <div class="pt-2 space-y-3">
                <button
                    type="submit"
                    :disabled="submitting"
                    class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 disabled:bg-emerald-400 text-white font-bold text-sm shadow-md hover:shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span x-show="!submitting">Daftar Partisipasi Sekarang &rarr;</span>
                    <span x-show="submitting" class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memproses Pendaftaran...</span>
                    </span>
                </button>

                <p class="text-xs text-slate-500 leading-relaxed">
                    Dengan mendaftar, data Anda dicatat secara resmi oleh pengelola Kampung Gedung Batin untuk pengaturan kapasitas dan persiapan acara. Anda akan menerima <strong>Kode Pendaftaran</strong> resmi setelah formulir dikirim.
                </p>
            </div>
        </form>
    @endif

    <!-- Bantuan Layanan Mandiri Peserta -->
    <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-slate-500">
        <span>Sudah pernah mendaftar untuk kegiatan ini sebelumnya?</span>
        <a href="{{ route('event.pendaftaran.cek') }}" class="font-bold text-emerald-700 hover:text-emerald-800 no-underline inline-flex items-center gap-1">
            <span>Cek / Lacak Bukti Pendaftaran</span>
            <span>&rarr;</span>
        </a>
    </div>
</div>
