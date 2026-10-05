<x-portal-layout>
    <x-slot:title>Bukti Pendaftaran: {{ $pendaftaran->kode_pendaftaran }} — SIGEBAT</x-slot:title>
    <x-slot:description>Tanda bukti resmi pendaftaran kegiatan cagar budaya di Desa Wisata Kampung Gedung Batin, Way Kanan.</x-slot:description>

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 12mm 10mm 12mm;
            }
            html, body {
                background: #ffffff !important;
                color: #0f172a !important;
                font-size: 10pt !important;
            }
            .print\:hidden {
                display: none !important;
            }
            #printable-ticket {
                border: 1.5px solid #0f172a !important;
                border-radius: 10px !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                background: #ffffff !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .print-header {
                background: #ffffff !important;
                color: #0f172a !important;
                border-bottom: 2px solid #0f172a !important;
                padding: 12px 16px !important;
            }
            .print-body {
                padding: 14px 16px !important;
            }
            .print-footer {
                padding: 12px 16px !important;
                border-top: 1.5px solid #0f172a !important;
                background: #ffffff !important;
            }
        }
    </style>

    <div 
        class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-10 space-y-6"
        x-data="{ copied: false }"
    >
        <!-- Navigasi & Tombol Aksi Layar (Sembunyi saat cetak) -->
        <div class="flex flex-wrap items-center justify-between gap-3 print:hidden">
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
                <a href="/" class="hover:text-slate-900 no-underline text-slate-500">Beranda</a>
                <span>/</span>
                <a href="{{ route('kalender.index') }}" class="hover:text-slate-900 no-underline text-slate-500">Kalender</a>
                <span>/</span>
                <a href="{{ route('event.show', $pendaftaran->event->slug) }}" class="hover:text-slate-900 no-underline text-slate-500 truncate max-w-[160px]">{{ $pendaftaran->event->judul }}</a>
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
                    class="px-3 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs rounded-xl flex items-center gap-1.5 transition-all shadow-xs cursor-pointer"
                    title="Salin Tautan Bukti Pendaftaran"
                >
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span>
                </button>

                <!-- Tombol Kirim ke WhatsApp -->
                @php
                    $pesanWa = urlencode("Halo, ini tanda bukti resmi pendaftaran event saya: *{$pendaftaran->event->judul}* dengan Kode: *{$pendaftaran->kode_pendaftaran}*. Tautan tiket: " . route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran));
                @endphp
                <a
                    href="https://api.whatsapp.com/send?text={{ $pesanWa }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs rounded-xl no-underline flex items-center gap-1.5 transition-all shadow-xs"
                    title="Kirim ke WhatsApp"
                >
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                    </svg>
                    <span>Kirim WA</span>
                </a>

                <!-- Tombol Cetak PDF -->
                <button
                    type="button"
                    onclick="window.print()"
                    class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl flex items-center gap-2 transition-all shadow-xs cursor-pointer"
                >
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak PDF</span>
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-start gap-2.5 print:hidden">
                <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">&check;</span>
                <div>
                    <div class="font-bold text-emerald-950">Pendaftaran Berhasil Dicatat!</div>
                    <div class="text-xs text-emerald-800 mt-0.5 leading-relaxed">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- DOKUMEN TANDA BUKTI RESMI (Simple, Elegan, 1 Lembar A4) -->
        <div id="printable-ticket" class="bg-white rounded-2xl border border-slate-300 shadow-md overflow-hidden">
            <!-- 1. Kop Resmi Dokumen -->
            <div class="print-header bg-slate-900 text-white p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800">
                <div class="flex items-center gap-3.5">
                    <img 
                        src="{{ asset('images/logo.png') }}" 
                        alt="Logo SIGEBAT" 
                        class="w-12 h-12 object-contain rounded-xl bg-white p-1 border border-slate-200 shadow-2xs flex-shrink-0"
                    >
                    <div>
                        <div class="text-[11px] font-bold tracking-wider uppercase text-emerald-400 print:text-slate-600">
                            Pemerintah Kampung Gedung Batin &bull; Way Kanan
                        </div>
                        <h1 class="text-base sm:text-lg font-black tracking-tight text-white print:text-slate-950 mt-0.5 leading-snug">
                            Tanda Bukti Pendaftaran Event Budaya
                        </h1>
                        <div class="text-[11px] text-slate-300 print:text-slate-500">
                            Sistem Informasi Manajemen Desa Wisata (SIGEBAT)
                        </div>
                    </div>
                </div>

                <!-- Box Kode Pendaftaran & Status -->
                <div class="text-left sm:text-right bg-white/10 print:bg-slate-50 p-2.5 sm:p-2 rounded-xl border border-white/10 print:border-slate-300 flex-shrink-0">
                    <div class="text-[10px] text-slate-300 print:text-slate-500 font-bold uppercase tracking-wider">KODE REGISTRASI</div>
                    <div class="text-lg sm:text-xl font-mono font-black text-amber-300 print:text-slate-950 tracking-wider">
                        {{ $pendaftaran->kode_pendaftaran }}
                    </div>
                    <div class="mt-0.5">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pendaftaran->status_badge_class }}">
                            {{ $pendaftaran->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. Isi Rincian: 2 Kolom Bersih & Rapi -->
            <div class="print-body p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-200">
                <!-- Kolom Kiri: Informasi Acara -->
                <div class="space-y-3.5 text-xs">
                    <div class="font-bold text-slate-900 uppercase tracking-wider text-[11px] border-b border-slate-200 pb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Informasi Kegiatan Budaya</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Nama Acara</span>
                        <div class="font-bold text-slate-900 text-sm mt-0.5 leading-snug">{{ $pendaftaran->event->judul }}</div>
                        <div class="text-[11px] text-emerald-700 font-medium mt-0.5">Kategori: {{ $pendaftaran->event->kategori?->nama ?? 'Acara Adat' }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Hari &amp; Tanggal</span>
                        <div class="font-semibold text-slate-800 mt-0.5">
                            {{ $pendaftaran->event->tanggal_mulai ? $pendaftaran->event->tanggal_mulai->translatedFormat('l, d F Y') : '-' }}
                            @if($pendaftaran->event->is_multi_hari && $pendaftaran->event->tanggal_selesai)
                                &ndash; {{ $pendaftaran->event->tanggal_selesai->translatedFormat('d F Y') }}
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Waktu Pelaksanaan</span>
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
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Tempat / Lokasi</span>
                        <div class="font-semibold text-slate-800 mt-0.5">{{ $pendaftaran->event->lokasi }}</div>
                        <div class="text-[11px] text-slate-500">Kampung Gedung Batin, Way Kanan</div>
                    </div>
                </div>

                <!-- Kolom Kanan: Data Peserta Terdaftar -->
                <div class="pt-4 sm:pt-0 sm:pl-6 space-y-3.5 text-xs">
                    <div class="font-bold text-slate-900 uppercase tracking-wider text-[11px] border-b border-slate-200 pb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Identitas Peserta Terdaftar</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Nama Pemesan</span>
                        <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $pendaftaran->nama_lengkap }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Asal / Instansi</span>
                            <div class="font-semibold text-slate-800 mt-0.5 truncate">{{ $pendaftaran->asal_instansi }}</div>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Jumlah Peserta</span>
                            <div class="font-bold text-emerald-800 mt-0.5 text-sm">{{ $pendaftaran->jumlah_peserta }} Orang</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">No. WhatsApp</span>
                            <div class="font-semibold text-slate-800 mt-0.5">{{ $pendaftaran->nomor_telepon }}</div>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Email</span>
                            <div class="font-semibold text-slate-800 mt-0.5 truncate">{{ $pendaftaran->email }}</div>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Waktu Registrasi</span>
                        <div class="text-[11px] text-slate-600 mt-0.5">
                            {{ $pendaftaran->created_at ? $pendaftaran->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                        </div>
                    </div>

                    @if($pendaftaran->catatan)
                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200 text-[11px]">
                            <span class="text-slate-500 font-semibold">Catatan:</span>
                            <span class="text-slate-700 italic">"{{ $pendaftaran->catatan }}"</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 3. Petunjuk Singkat Kehadiran -->
            <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 text-[11px] text-slate-600 space-y-1 print:bg-white print:border-t">
                <div class="font-bold text-slate-800 uppercase text-[10px] tracking-wider">Petunjuk Kehadiran:</div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-slate-600">
                    <div>&bull; Simpan lembar ini (cetak atau tangkapan layar di HP).</div>
                    <div>&bull; Tunjukkan Kode Registrasi pada meja presensi di lokasi.</div>
                    <div>&bull; Hadir 15 menit sebelum acara dan berpakaian sopan.</div>
                </div>
            </div>

            <!-- 4. Area Validasi & Paraf Presensi Pengelola (Sangat Rapi di Cetak) -->
            <div class="print-footer px-5 py-4 border-t border-slate-200 bg-white grid grid-cols-2 gap-4 text-xs items-end">
                <div class="space-y-1">
                    <div class="text-[10px] text-slate-400 font-mono">
                        VERIFIKASI SISTEM INFORMASI MANAJEMEN:
                    </div>
                    <div class="text-[11px] font-mono font-semibold text-slate-700">
                        REF: {{ $pendaftaran->kode_pendaftaran }} &bull; ID: #{{ $pendaftaran->id }}
                    </div>
                    <div class="text-[10px] text-slate-500 italic">
                        Desa Wisata Cagar Budaya Kampung Gedung Batin
                    </div>
                </div>

                <div class="text-right space-y-1">
                    <div class="text-[10px] text-slate-500">
                        Kampung Gedung Batin, {{ now('Asia/Jakarta')->translatedFormat('d F Y') }}
                    </div>
                    <div class="text-[10px] text-slate-600 font-medium">Petugas Presensi Acara:</div>
                    <div class="h-10"></div>
                    <div class="text-slate-800 font-bold uppercase text-[11px] underline underline-offset-2">
                        ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Bawah (Sembunyi saat cetak) -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 print:hidden">
            <a
                href="{{ route('kalender.index') }}"
                class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs no-underline transition-all shadow-xs flex items-center gap-1.5"
            >
                <span>&larr;</span>
                <span>Kembali ke Kalender Event</span>
            </a>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('event.show', $pendaftaran->event->slug) }}"
                    class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs no-underline transition-all shadow-xs"
                >
                    Lihat Acara
                </a>
                <button
                    type="button"
                    onclick="window.print()"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition-all shadow-sm flex items-center gap-2 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak PDF</span>
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
                // Ignore storage error
            }
        });
    </script>
</x-portal-layout>
