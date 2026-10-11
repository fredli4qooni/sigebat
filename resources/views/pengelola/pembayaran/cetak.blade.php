<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Kas Event Budaya — SIGEBAT</title>
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
                font-size: 10pt;
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
                    <div class="font-bold text-sm">Laporan Rekapitulasi Kas Keuangan Event</div>
                    <div class="text-xs text-slate-300">Siap dicetak pada kertas A4 untuk pertanggungjawaban bendahara &amp; sidang</div>
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
                    Badan Usaha Milik Desa (BUMDes) Kampung Gedung Batin
                </h1>
                <div class="text-xs text-slate-600 mt-1">
                    Sistem Informasi Manajemen Desa Wisata (SIGEBAT) &bull; Unit Pengelolaan Keuangan &amp; Kas Event
                </div>
                <div class="text-xs text-slate-500 italic mt-0.5">
                    Alamat: Kompleks Rumah Adat Panggung Kampung Gedung Batin, Way Kanan, Lampung 34764
                </div>
            </div>
        </div>

        <!-- 2. Judul Dokumen -->
        <div class="text-center my-6 space-y-1">
            <h2 class="text-base sm:text-lg font-black uppercase text-slate-950 underline underline-offset-4 tracking-wide">
                Laporan Rekapitulasi Kas Penerimaan Event Budaya
            </h2>
            <div class="text-xs font-medium text-slate-600">
                @if($selectedEvent)
                    Kegiatan: <strong>{{ $selectedEvent->judul }}</strong> &bull; Jadwal: {{ $selectedEvent->tanggal_mulai ? $selectedEvent->tanggal_mulai->translatedFormat('d F Y') : '-' }}
                @else
                    Periode Laporan: <strong>Seluruh Kegiatan Budaya (Akumulatif)</strong>
                @endif
            </div>
            <div class="text-[11px] font-mono text-slate-500">
                Dicetak pada: {{ now('Asia/Jakarta')->translatedFormat('l, d F Y - H:i') }} WIB &bull; Dicetak oleh: {{ auth()->user()->name }}
            </div>
        </div>

        <!-- 3. Kotak Ringkasan Finansial -->
        <div class="grid grid-cols-3 gap-3 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-300 text-xs">
            <div>
                <span class="text-slate-500 block uppercase text-[10px] font-bold">Total Kas Diterima</span>
                <span class="text-base font-black text-emerald-800 font-mono mt-0.5 block">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </span>
            </div>
            <div>
                <span class="text-slate-500 block uppercase text-[10px] font-bold">Jumlah Transaksi Lunas</span>
                <span class="text-base font-bold text-slate-900 mt-0.5 block">
                    {{ $laporanList->count() }} Transaksi
                </span>
            </div>
            <div>
                <span class="text-slate-500 block uppercase text-[10px] font-bold">Total Tiket Terkonfirmasi</span>
                <span class="text-base font-bold text-slate-900 mt-0.5 block">
                    {{ $totalTiket }} Orang Peserta
                </span>
            </div>
        </div>

        <!-- 4. Tabel Rincian Kas Masuk -->
        <div class="overflow-hidden border border-slate-300 rounded-lg mb-8">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-800 uppercase text-[10px] font-bold border-b border-slate-300">
                        <th class="py-2.5 px-3 w-8 text-center border-r border-slate-300">No</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">No. Registrasi</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Waktu Bayar</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Nama Pendaftar</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Nama Acara</th>
                        <th class="py-2.5 px-3 text-center border-r border-slate-300">Tiket</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Metode</th>
                        <th class="py-2.5 px-3 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($laporanList as $i => $row)
                        <tr>
                            <td class="py-2 px-3 text-center border-r border-slate-200 text-slate-500 font-mono">
                                {{ $i + 1 }}
                            </td>
                            <td class="py-2 px-3 border-r border-slate-200 font-mono font-bold text-slate-900">
                                {{ $row->kode_pendaftaran }}
                            </td>
                            <td class="py-2 px-3 border-r border-slate-200 text-[11px] text-slate-600">
                                {{ $row->waktu_pembayaran ? $row->waktu_pembayaran->translatedFormat('d/m/Y H:i') : ($row->created_at ? $row->created_at->translatedFormat('d/m/Y') : '-') }}
                            </td>
                            <td class="py-2 px-3 border-r border-slate-200 font-medium text-slate-900">
                                {{ $row->nama_lengkap }}
                            </td>
                            <td class="py-2 px-3 border-r border-slate-200 text-slate-800">
                                {{ $row->event->judul }}
                            </td>
                            <td class="py-2 px-3 text-center border-r border-slate-200 font-bold text-slate-800">
                                {{ $row->jumlah_peserta }}
                            </td>
                            <td class="py-2 px-3 border-r border-slate-200 text-[11px] text-slate-600">
                                {{ $row->metode_pembayaran_label }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono font-bold text-slate-900">
                                {{ $row->total_tagihan_format }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400 italic">
                                Belum ada catatan penerimaan kas yang tercatat lunas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100 font-bold border-t-2 border-slate-400">
                        <td colspan="5" class="py-2.5 px-3 text-right uppercase text-[10px] tracking-wider border-r border-slate-300">
                            Total Kas Masuk Terverifikasi:
                        </td>
                        <td class="py-2.5 px-3 text-center border-r border-slate-300 font-mono">
                            {{ $totalTiket }}
                        </td>
                        <td class="py-2.5 px-3 border-r border-slate-300"></td>
                        <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800">
                            Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- 5. Kolom Validasi & Tanda Tangan Resmi -->
        <div class="grid grid-cols-2 gap-8 text-xs pt-4">
            <div class="text-left space-y-1">
                <div class="text-slate-500">Mengetahui,</div>
                <div class="font-bold text-slate-900">Kepala Kampung Gedung Batin / Pokdarwis</div>
                <div class="h-20"></div>
                <div class="font-bold text-slate-950 underline underline-offset-4 uppercase">
                    ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
                </div>
                <div class="text-[10px] text-slate-500">NIP / Jabatan Pengurus</div>
            </div>

            <div class="text-right space-y-1">
                <div class="text-slate-500">
                    Kampung Gedung Batin, {{ now('Asia/Jakarta')->translatedFormat('d F Y') }}
                </div>
                <div class="font-bold text-slate-900">Bendahara Kas / Petugas SIM:</div>
                <div class="h-20"></div>
                <div class="font-bold text-slate-950 underline underline-offset-4 uppercase">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-[10px] text-slate-500">Petugas Terotorisasi SIGEBAT</div>
            </div>
        </div>
    </div>

</body>
</html>
