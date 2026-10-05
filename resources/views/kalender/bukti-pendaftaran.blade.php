<x-portal-layout>
    <x-slot:title>Bukti Pendaftaran Partisipasi: {{ $pendaftaran->kode_pendaftaran }} — SIGEBAT</x-slot:title>
    <x-slot:description>Tanda bukti pendaftaran resmi kegiatan cagar budaya di Desa Wisata Kampung Gedung Batin, Way Kanan.</x-slot:description>

    <div 
        class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 space-y-6"
        x-data="{ copied: false }"
    >
        <!-- Breadcrumb & Tombol Navigasi (Sembunyi saat cetak) -->
        <div class="flex flex-wrap items-center justify-between gap-4 print:hidden">
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
                <a href="/" class="hover:text-slate-900 no-underline text-slate-500">Beranda</a>
                <span>/</span>
                <a href="{{ route('kalender.index') }}" class="hover:text-slate-900 no-underline text-slate-500">Kalender Event</a>
                <span>/</span>
                <a href="{{ route('event.show', $pendaftaran->event->slug) }}" class="hover:text-slate-900 no-underline text-slate-500 truncate max-w-[180px]">{{ $pendaftaran->event->judul }}</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Bukti Pendaftaran</span>
            </nav>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Tombol Salin Tautan -->
                <button
                    type="button"
                    @click="
                        navigator.clipboard.writeText(window.location.href);
                        copied = true;
                        setTimeout(() => copied = false, 2500);
                    "
                    class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs rounded-xl flex items-center gap-1.5 transition-all shadow-xs cursor-pointer"
                    title="Salin Tautan Bukti Pendaftaran"
                >
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span x-text="copied ? 'Tautan Tersalin!' : 'Salin Tautan'"></span>
                </button>

                <!-- Tombol Simpan ke WhatsApp -->
                @php
                    $pesanWa = urlencode("Halo, ini tanda bukti pendaftaran resmi saya untuk event *{$pendaftaran->event->judul}* dengan Kode: *{$pendaftaran->kode_pendaftaran}*. Tautan tiket: " . route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran));
                @endphp
                <a
                    href="https://api.whatsapp.com/send?text={{ $pesanWa }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs rounded-xl no-underline flex items-center gap-1.5 transition-all shadow-xs"
                    title="Simpan atau Kirim Tautan ke WhatsApp"
                >
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                    </svg>
                    <span>Kirim ke WA</span>
                </a>

                <!-- Cetak PDF -->
                <button
                    type="button"
                    onclick="window.print()"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl flex items-center gap-2 transition-all shadow-xs cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak PDF</span>
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-start gap-3 print:hidden">
                <div class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">
                    &check;
                </div>
                <div>
                    <div class="font-bold text-emerald-950">Pendaftaran Berhasil Dicatat!</div>
                    <div class="text-xs text-emerald-800 mt-0.5 leading-relaxed">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- Kartu Resmi E-Ticket / Bukti Partisipasi -->
        <div id="printable-ticket" class="bg-white rounded-3xl border-2 border-slate-300 shadow-lg overflow-hidden print:border-slate-800 print:shadow-none">
            <!-- Kop Tiket Resmi -->
            <div class="bg-slate-900 text-white p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-slate-800 print:bg-white print:text-slate-900 print:border-b-2 print:border-slate-800">
                <div class="flex items-center gap-4">
                    <img 
                        src="{{ asset('images/logo.png') }}" 
                        alt="Logo SIGEBAT" 
                        class="w-14 h-14 object-contain rounded-2xl bg-white p-1 border border-slate-200 shadow-xs flex-shrink-0"
                    >
                    <div>
                        <div class="text-xs font-bold tracking-wider uppercase text-emerald-400 print:text-slate-600">
                            Pemerintah Kampung Gedung Batin &bull; Way Kanan
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white print:text-slate-950 mt-0.5">
                            Tanda Bukti Pendaftaran Acara Budaya
                        </h1>
                        <div class="text-xs text-slate-300 print:text-slate-600 mt-1">
                            Sistem Informasi Manajemen Desa Wisata (SIGEBAT)
                        </div>
                    </div>
                </div>

                <!-- Nomor Registrasi Box -->
                <div class="text-left sm:text-right bg-white/10 sm:bg-transparent p-4 sm:p-0 rounded-2xl border sm:border-0 border-white/15">
                    <div class="text-xs text-slate-300 print:text-slate-500 font-medium">KODE PENDAFTARAN</div>
                    <div class="text-2xl sm:text-3xl font-mono font-black text-amber-300 print:text-slate-950 tracking-wider">
                        {{ $pendaftaran->kode_pendaftaran }}
                    </div>
                    <div class="mt-1.5 inline-block">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $pendaftaran->status_badge_class }}">
                            {{ $pendaftaran->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Isi Detail 2 Sisi -->
            <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-200">
                <!-- Sisi Kiri: Rincian Acara -->
                <div class="space-y-5">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h2 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Informasi Kegiatan</h2>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div>
                            <div class="text-xs text-slate-400 font-semibold uppercase">Nama Kegiatan</div>
                            <div class="font-bold text-slate-900 text-base mt-0.5 leading-snug">
                                {{ $pendaftaran->event->judul }}
                            </div>
                            <div class="text-xs text-emerald-700 font-semibold mt-1">
                                Kategori: {{ $pendaftaran->event->kategori?->nama ?? 'Acara Adat' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-slate-400 font-semibold uppercase">Tanggal Pelaksanaan</div>
                            <div class="font-semibold text-slate-800 mt-0.5">
                                {{ $pendaftaran->event->tanggal_mulai ? $pendaftaran->event->tanggal_mulai->translatedFormat('l, d F Y') : '-' }}
                                @if($pendaftaran->event->is_multi_hari && $pendaftaran->event->tanggal_selesai)
                                    <span class="text-slate-500 font-normal">s/d</span>
                                    {{ $pendaftaran->event->tanggal_selesai->translatedFormat('l, d F Y') }}
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-slate-400 font-semibold uppercase">Waktu / Jam</div>
                            <div class="font-semibold text-slate-800 mt-0.5">
                                {{ substr($pendaftaran->event->jam_mulai, 0, 5) }} WIB
                                @if($pendaftaran->event->jam_selesai)
                                    &ndash; {{ substr($pendaftaran->event->jam_selesai, 0, 5) }} WIB
                                @else
                                    &ndash; Selesai
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-slate-400 font-semibold uppercase">Lokasi Kegiatan</div>
                            <div class="font-semibold text-slate-800 mt-0.5">
                                {{ $pendaftaran->event->lokasi }}
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                Kampung Gedung Batin, Kec. Umpu Semenguk, Kab. Way Kanan
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Data Peserta Terdaftar -->
                <div class="pt-6 md:pt-0 md:pl-8 space-y-5">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <h2 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Identitas Peserta</h2>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div>
                            <div class="text-xs text-slate-400 font-semibold uppercase">Nama Lengkap</div>
                            <div class="font-bold text-slate-900 text-base mt-0.5">
                                {{ $pendaftaran->nama_lengkap }}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-slate-400 font-semibold uppercase">Asal / Instansi</div>
                                <div class="font-semibold text-slate-800 mt-0.5">
                                    {{ $pendaftaran->asal_instansi }}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400 font-semibold uppercase">Jumlah Peserta</div>
                                <div class="font-bold text-emerald-800 mt-0.5 text-base">
                                    {{ $pendaftaran->jumlah_peserta }} Orang
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-slate-400 font-semibold uppercase">Nomor WhatsApp</div>
                                <div class="font-semibold text-slate-800 mt-0.5">
                                    {{ $pendaftaran->nomor_telepon }}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400 font-semibold uppercase">Alamat Email</div>
                                <div class="font-semibold text-slate-800 mt-0.5 truncate">
                                    {{ $pendaftaran->email }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-slate-400 font-semibold uppercase">Waktu Pendaftaran</div>
                            <div class="text-xs text-slate-600 mt-0.5">
                                {{ $pendaftaran->created_at ? $pendaftaran->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                            </div>
                        </div>

                        @if($pendaftaran->catatan)
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                                <div class="text-xs text-slate-500 font-semibold">Catatan Tambahan:</div>
                                <div class="text-xs text-slate-700 italic mt-0.5">"{{ $pendaftaran->catatan }}"</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Petunjuk & Tata Tertib Peserta -->
            <div class="bg-slate-50 p-6 sm:p-8 border-t border-slate-200 space-y-3 print:bg-white print:border-t-2 print:border-slate-800">
                <div class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span>Petunjuk & Tata Tertib Kehadiran:</span>
                </div>
                <ul class="text-xs text-slate-600 space-y-1.5 list-disc pl-5 leading-relaxed">
                    <li>Simpan lembar bukti pendaftaran ini (baik dalam bentuk cetak fisik maupun tangkapan layar ponsel).</li>
                    <li>Tunjukkan <strong>Kode Pendaftaran</strong> kepada petugas/pengelola di lokasi acara untuk proses konfirmasi kehadiran (presensi).</li>
                    <li>Hadir minimal 15 menit sebelum acara dimulai dan mengenakan pakaian sopan yang menghormati tradisi adat Kampung Gedung Batin.</li>
                    <li>Jika berhalangan hadir, harap memberitahukan kepada pengelola melalui kontak resmi desa wisata.</li>
                </ul>
            </div>
        </div>

        <!-- Tombol Aksi Bawah (Sembunyi saat cetak) -->
        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 print:hidden">
            <a
                href="{{ route('kalender.index') }}"
                class="px-5 py-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs sm:text-sm no-underline transition-all shadow-xs flex items-center gap-2"
            >
                <span>&larr;</span>
                <span>Kembali ke Kalender Event</span>
            </a>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('event.show', $pendaftaran->event->slug) }}"
                    class="px-5 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm no-underline transition-all shadow-xs"
                >
                    Lihat Rincian Kegiatan
                </a>
                <button
                    type="button"
                    onclick="window.print()"
                    class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm transition-all shadow-md flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak / Simpan Tiket</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Simpan Otomatis Riwayat Pendaftaran ke Browser untuk Layanan Mandiri -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const key = 'sigebat_pendaftaran_history';
                let list = JSON.parse(localStorage.getItem(key) || '[]');
                const current = {
                    kode: @json($pendaftaran->kode_pendaftaran),
                    judul: @json($pendaftaran->event->judul),
                    tanggal: @json($pendaftaran->event->tanggal_mulai ? $pendaftaran->event->tanggal_mulai->translatedFormat('d M Y') : ''),
                    url: @json(route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran))
                };
                list = list.filter(item => item.kode !== current.kode);
                list.unshift(current);
                if (list.length > 5) list = list.slice(0, 5);
                localStorage.setItem(key, JSON.stringify(list));
            } catch (e) {
                // Ignore storage error if disabled
            }
        });
    </script>
</x-portal-layout>
