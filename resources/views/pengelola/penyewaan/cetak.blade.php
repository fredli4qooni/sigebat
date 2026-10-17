<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Kas Penyewaan Fasilitas — SIGEBAT</title>
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

    <!-- Bilah Kontrol Atas (Hanya layar browser) -->
    <div class="max-w-4xl mx-auto px-4 mb-6 no-print">
        <div class="bg-slate-900 text-white p-4 rounded-2xl shadow-lg flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 object-contain bg-white rounded-lg p-0.5">
                <div>
                    <div class="font-bold text-sm">Laporan Rekapitulasi Kas Sewa Fasilitas Desa</div>
                    <div class="text-xs text-slate-300">Format A4 Resmi untuk pertanggungjawaban BUMDes &amp; sidang skripsi</div>
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
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-sm border border-slate-200 print:border-0 print:shadow-none print:p-0 print:max-w-none space-y-6">
        <!-- 1. Kop Surat Resmi -->
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
                    Sistem Informasi Manajemen Desa Wisata (SIGEBAT) &bull; Unit Usaha Penyewaan Sarana &amp; Homestay
                </div>
                <div class="text-xs text-slate-500 italic mt-0.5">
                    Alamat: Kompleks Rumah Adat Panggung Kampung Gedung Batin, Way Kanan, Lampung 34764
                </div>
            </div>
        </div>

        <!-- 2. Judul Laporan & Metadata -->
        <div class="text-center space-y-1">
            <h2 class="text-base sm:text-lg font-black uppercase text-slate-900 tracking-tight underline">
                Laporan Rekapitulasi Pendapatan Kas Sewa Fasilitas Desa
            </h2>
            <div class="text-xs text-slate-600">
                @if($fasilitasTerpilih)
                    Fasilitas Khusus: <strong>{{ $fasilitasTerpilih->nama }}</strong> &bull;
                @endif
                Tanggal Terbit: <strong>{{ now()->translatedFormat('d F Y') }}</strong>
            </div>
        </div>

        <!-- 3. Ringkasan Eksekutif Kas -->
        <div class="grid grid-cols-3 gap-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs">
            <div>
                <span class="block text-slate-500 uppercase font-bold text-[10px]">Total Kas Lunas</span>
                <span class="text-base sm:text-lg font-black text-emerald-700">Rp {{ number_format($totalKas, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="block text-slate-500 uppercase font-bold text-[10px]">Total Hari Tersewa</span>
                <span class="text-base sm:text-lg font-bold text-slate-900">{{ $totalDurasiHari }} Hari</span>
            </div>
            <div>
                <span class="block text-slate-500 uppercase font-bold text-[10px]">Jumlah Reservasi</span>
                <span class="text-base sm:text-lg font-bold text-slate-900">{{ $penyewaanList->count() }} Transaksi</span>
            </div>
        </div>

        <!-- 4. Tabel Rincian Transaksi -->
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-100 border-b border-slate-300 text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-2.5 w-8 text-center">No</th>
                        <th class="p-2.5">Kode &amp; Tanggal</th>
                        <th class="p-2.5">Penyewa</th>
                        <th class="p-2.5">Fasilitas</th>
                        <th class="p-2.5 text-center">Durasi</th>
                        <th class="p-2.5 text-center">Status</th>
                        <th class="p-2.5 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($penyewaanList as $i => $row)
                        <tr>
                            <td class="p-2.5 text-center font-bold text-slate-400">{{ $i + 1 }}</td>
                            <td class="p-2.5">
                                <div class="font-mono font-bold">{{ $row->kode_sewa }}</div>
                                <div class="text-[10px] text-slate-500">{{ $row->created_at->translatedFormat('d/m/Y') }}</div>
                            </td>
                            <td class="p-2.5">
                                <div class="font-bold text-slate-900">{{ $row->nama_penyewa }}</div>
                                <div class="text-[10px] text-slate-500">{{ $row->nomor_telepon }}</div>
                            </td>
                            <td class="p-2.5">
                                <div class="font-medium">{{ $row->fasilitas?->nama }}</div>
                                <div class="text-[10px] text-slate-500">
                                    {{ $row->tanggal_mulai->translatedFormat('d M') }} - {{ $row->tanggal_selesai->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td class="p-2.5 text-center font-bold">{{ $row->durasi_hari }} Hari</td>
                            <td class="p-2.5 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $row->status_pembayaran === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $row->status_pembayaran }}
                                </span>
                            </td>
                            <td class="p-2.5 text-right font-bold whitespace-nowrap">
                                {{ $row->total_tagihan_format }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400">Tidak ada catatan transaksi penyewaan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-slate-900">
                    <tr>
                        <td colspan="6" class="p-2.5 text-right uppercase tracking-wider text-[11px]">Total Kas Masuk Terverifikasi (Lunas):</td>
                        <td class="p-2.5 text-right text-sm font-black text-emerald-800 whitespace-nowrap">
                            Rp {{ number_format($totalKas, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- 5. Tanda Tangan & Pengesahan Dokumen -->
        <div class="pt-8 flex items-start justify-between text-xs text-slate-700">
            <div class="space-y-1">
                <div>Dicetak secara otomatis melalui SIM SIGEBAT.</div>
                <div class="text-[10px] text-slate-500">Waktu Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
            </div>

            <div class="text-center w-52 space-y-16">
                <div>
                    <div>Kampung Gedung Batin, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="font-bold text-slate-900 mt-1">Pengelola / Bendahara BUMDes</div>
                </div>
                <div class="border-b border-slate-900 mx-6"></div>
                <div class="font-bold text-slate-900 -mt-14">
                    {{ auth()->user()->name ?? 'Pengelola Unit Wisata' }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
