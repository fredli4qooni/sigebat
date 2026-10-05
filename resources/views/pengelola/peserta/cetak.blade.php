<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Peserta — {{ $event->judul }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 15mm 15mm 15mm;
            }
            body {
                background: white !important;
                color: black !important;
                font-size: 11pt;
            }
            .no-print {
                display: none !important;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 font-sans antialiased min-h-screen py-6 print:py-0 print:bg-white">

    <!-- Bilah Kontrol Atas (Hanya tampil di layar browser) -->
    <div class="max-w-4xl mx-auto px-4 mb-6 no-print">
        <div class="bg-slate-900 text-white p-4 rounded-2xl shadow-lg flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 object-contain bg-white rounded-lg p-0.5">
                <div>
                    <div class="font-bold text-sm">Format Cetak Lembar Presensi Resmi</div>
                    <div class="text-xs text-slate-300">Siap dicetak pada kertas A4 untuk keperluan registrasi di lokasi</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    onclick="window.print()"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-xs flex items-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak Sekarang</span>
                </button>
                <button
                    type="button"
                    onclick="window.close()"
                    class="px-3 py-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs rounded-xl cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Lembar Dokumen Fisik A4 -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-sm border border-slate-200 print:border-0 print:shadow-none print:p-0 print:max-w-none">
        <!-- 1. Kop Dokumen Resmi -->
        <div class="flex items-center gap-5 pb-4 border-b-2 border-slate-900">
            <img 
                src="{{ asset('images/logo.png') }}" 
                alt="Logo SIGEBAT" 
                class="w-16 h-16 object-contain flex-shrink-0"
            >
            <div class="text-center flex-1">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-700">
                    Pemerintah Kabupaten Way Kanan &bull; Kecamatan Umpu Semenguk
                </div>
                <h1 class="text-lg sm:text-xl font-black uppercase text-slate-950 tracking-tight mt-0.5">
                    Pengelola Desa Wisata Cagar Budaya Kampung Gedung Batin
                </h1>
                <div class="text-xs text-slate-600 mt-1">
                    Sistem Informasi Manajemen Desa Wisata (SIGEBAT) &bull; Modul Partisipasi & Agenda Budaya
                </div>
                <div class="text-xs text-slate-500 italic mt-0.5">
                    Alamat: Kompleks Rumah Adat Panggung Kampung Gedung Batin, Way Kanan, Lampung 34764
                </div>
            </div>
        </div>

        <!-- 2. Judul Lembar Presensi -->
        <div class="text-center py-5 space-y-1">
            <h2 class="text-base sm:text-lg font-black uppercase tracking-wide text-slate-950">
                Lembar Presensi &amp; Rekapitulasi Kehadiran Peserta Kegiatan
            </h2>
            <div class="text-xs font-mono text-slate-500">
                Nomor Agenda: SIGEBAT-EVT/{{ $event->id }}/{{ $event->tanggal_mulai ? $event->tanggal_mulai->format('Ym') : date('Ym') }}
            </div>
        </div>

        <!-- 3. Rincian Metadata Kegiatan -->
        <div class="bg-slate-50 rounded-xl border border-slate-300 p-4 mb-6 text-xs grid grid-cols-2 sm:grid-cols-4 gap-4 print:bg-slate-50">
            <div>
                <span class="text-slate-500 uppercase font-semibold block text-[10px]">Nama Kegiatan</span>
                <span class="font-bold text-slate-900 block mt-0.5">{{ $event->judul }}</span>
            </div>
            <div>
                <span class="text-slate-500 uppercase font-semibold block text-[10px]">Tanggal & Waktu</span>
                <span class="font-bold text-slate-900 block mt-0.5">
                    {{ $event->tanggal_mulai ? $event->tanggal_mulai->translatedFormat('d F Y') : '-' }} ({{ substr($event->jam_mulai, 0, 5) }} WIB)
                </span>
            </div>
            <div>
                <span class="text-slate-500 uppercase font-semibold block text-[10px]">Lokasi Acara</span>
                <span class="font-bold text-slate-900 block mt-0.5">{{ $event->lokasi }}</span>
            </div>
            <div>
                <span class="text-slate-500 uppercase font-semibold block text-[10px]">Total Terdaftar</span>
                <span class="font-bold text-emerald-800 block mt-0.5">
                    {{ $pesertaList->sum('jumlah_peserta') }} Orang ({{ $pesertaList->count() }} Pendaftaran)
                </span>
            </div>
        </div>

        <!-- 4. Tabel Presensi & Tanda Tangan Fisik -->
        <table class="w-full border-collapse border border-slate-400 text-xs mb-8">
            <thead>
                <tr class="bg-slate-100 text-slate-900 font-bold uppercase text-[10px] text-center border-b border-slate-400">
                    <th class="border border-slate-400 py-2.5 px-2 w-8">No</th>
                    <th class="border border-slate-400 py-2.5 px-3 w-32">Kode Registrasi</th>
                    <th class="border border-slate-400 py-2.5 px-3">Nama Lengkap Peserta</th>
                    <th class="border border-slate-400 py-2.5 px-3">Asal Daerah / Instansi</th>
                    <th class="border border-slate-400 py-2.5 px-2 w-14">Jumlah</th>
                    <th class="border border-slate-400 py-2.5 px-3 w-28">Status SIM</th>
                    <th class="border border-slate-400 py-2.5 px-3 w-32">Paraf Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesertaList as $idx => $item)
                    <tr class="border-b border-slate-300">
                        <td class="border border-slate-300 py-2 px-2 text-center font-medium">{{ $idx + 1 }}</td>
                        <td class="border border-slate-300 py-2 px-3 font-mono font-bold text-center text-[10px]">
                            {{ $item->kode_pendaftaran }}
                        </td>
                        <td class="border border-slate-300 py-2 px-3">
                            <span class="font-bold text-slate-900">{{ $item->nama_lengkap }}</span>
                            <span class="block text-[10px] text-slate-500">{{ $item->nomor_telepon }}</span>
                        </td>
                        <td class="border border-slate-300 py-2 px-3">{{ $item->asal_instansi }}</td>
                        <td class="border border-slate-300 py-2 px-2 text-center font-bold">{{ $item->jumlah_peserta }}</td>
                        <td class="border border-slate-300 py-2 px-3 text-center">
                            @if($item->status === 'hadir')
                                <span class="font-bold text-emerald-800">[ V ] Hadir</span>
                            @else
                                <span class="text-slate-600">Terdaftar</span>
                            @endif
                        </td>
                        <td class="border border-slate-300 py-2 px-3 text-center">
                            @if($item->status === 'hadir')
                                <span class="text-slate-400 font-mono text-[9px]">&bull; Terverifikasi SIM &bull;</span>
                            @else
                                <div class="h-6"></div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-slate-400 py-8 text-center text-slate-400 italic">
                            Belum ada wisatawan yang mendaftar pada kegiatan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- 5. Lembar Pengesahan Panitia / Pengelola -->
        <div class="grid grid-cols-2 gap-8 pt-4 text-xs text-center" style="page-break-inside: avoid;">
            <div>
                <div class="text-slate-600">Petugas Registrasi & Presensi:</div>
                <div class="h-20"></div>
                <div class="font-bold text-slate-900 uppercase underline decoration-1 underline-offset-4">
                    ( ...................................................... )
                </div>
                <div class="text-slate-500 text-[10px] mt-0.5">Pengelola Acara Budaya</div>
            </div>

            <div>
                <div class="text-slate-600">
                    Kampung Gedung Batin, {{ now('Asia/Jakarta')->translatedFormat('d F Y') }}<br>
                    Mengetahui,
                </div>
                <div class="h-16"></div>
                <div class="font-bold text-slate-900 uppercase underline decoration-1 underline-offset-4">
                    ( ...................................................... )
                </div>
                <div class="text-slate-500 text-[10px] mt-0.5">Ketua Pengelola / Penyimbang Adat</div>
            </div>
        </div>
    </div>

</body>
</html>
