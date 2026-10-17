<x-portal-layout>
    <x-slot:title>Tanda Bukti Reservasi Sewa {{ $penyewaan->kode_sewa }} — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Voucher resmi penyewaan fasilitas desa wisata Kampung Gedung Batin.</x-slot:description>

    <div class="py-8 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Action Bar (Hidden on Print) -->
            <div class="flex items-center justify-between gap-3 flex-wrap print:hidden">
                <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-emerald-700 no-underline">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('fasilitas.index') }}" class="hover:text-emerald-700 no-underline">Fasilitas</a>
                    <span>/</span>
                    <span class="text-slate-800 font-semibold">Bukti Reservasi</span>
                </nav>

                <div class="flex items-center gap-2">
                    @if(! $penyewaan->is_lunas)
                        <a
                            href="{{ route('sewa.bayar', $penyewaan->kode_sewa) }}"
                            class="h-9 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl transition-all inline-flex items-center gap-1.5 no-underline shadow-2xs"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>{{ $penyewaan->bukti_pembayaran ? 'Ganti Bukti Transfer' : 'Bayar Sekarang' }}</span>
                        </a>
                    @endif

                    <button
                        type="button"
                        onclick="window.print()"
                        class="h-9 px-3.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-all inline-flex items-center gap-1.5 cursor-pointer shadow-2xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Cetak / Unduh PDF</span>
                    </button>
                </div>
            </div>

            <!-- Pesan Notifikasi Sukses / Info -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs rounded-2xl flex items-center gap-2 print:hidden font-medium">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Lembar Voucher Resmi A4 -->
            <div class="bg-white border border-slate-300 rounded-3xl p-6 sm:p-10 shadow-sm print:border-none print:shadow-none print:p-0 space-y-8">
                <!-- Kop Resmi Surat / Voucher -->
                <div class="flex items-start justify-between border-b-2 border-slate-900 pb-6 gap-4">
                    <div class="space-y-1">
                        <div class="text-[11px] font-black tracking-widest text-slate-500 uppercase">
                            Badan Usaha Milik Desa (BUMDes) &amp; Pokdarwis
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tight">
                            Desa Wisata Kampung Gedung Batin
                        </h1>
                        <p class="text-xs text-slate-500 max-w-md">
                            Kec. Blambangan Umpu, Kab. Way Kanan, Lampung &bull; Layanan Terpadu Wisata &amp; Kebudayaan
                        </p>
                    </div>

                    <!-- Stempel Status Resmi -->
                    <div class="text-right flex flex-col items-end">
                        <div class="text-[11px] text-slate-400 font-mono">VOUCHER SEWA RESMI</div>
                        <div class="mt-1 px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider border {{ $penyewaan->status_pembayaran_badge_class }}">
                            {{ $penyewaan->status_pembayaran_label }}
                        </div>
                    </div>
                </div>

                <!-- Nomor Kode & Tanggal Cetak -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase font-bold">Kode Reservasi</span>
                        <span class="font-mono font-bold text-slate-900 text-sm">{{ $penyewaan->kode_sewa }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase font-bold">Status Operasional</span>
                        <span class="font-bold text-slate-800">{{ $penyewaan->status_sewa_label }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase font-bold">Tanggal Reservasi</span>
                        <span class="font-medium text-slate-700">{{ $penyewaan->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase font-bold">Metode Bayar</span>
                        <span class="font-medium text-slate-700">{{ $penyewaan->metode_pembayaran ? ucwords(str_replace('_', ' ', $penyewaan->metode_pembayaran)) : '-' }}</span>
                    </div>
                </div>

                <!-- Rincian Identitas Penyewa & Fasilitas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div class="space-y-3 p-4 border border-slate-200 rounded-2xl">
                        <div class="font-bold text-slate-900 uppercase tracking-wider text-[11px] border-b border-slate-100 pb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Data Penyewa / Pemesan</span>
                        </div>
                        <div class="space-y-1.5 text-slate-700">
                            <div><span class="text-slate-400">Nama:</span> <strong class="text-slate-900">{{ $penyewaan->nama_penyewa }}</strong></div>
                            <div><span class="text-slate-400">Email:</span> {{ $penyewaan->email }}</div>
                            <div><span class="text-slate-400">Telepon:</span> {{ $penyewaan->nomor_telepon }}</div>
                            @if($penyewaan->asal_instansi)
                                <div><span class="text-slate-400">Instansi / Kota:</span> {{ $penyewaan->asal_instansi }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3 p-4 border border-slate-200 rounded-2xl">
                        <div class="font-bold text-slate-900 uppercase tracking-wider text-[11px] border-b border-slate-100 pb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Fasilitas yang Dipesan</span>
                        </div>
                        <div class="space-y-1.5 text-slate-700">
                            <div><span class="text-slate-400">Nama Sarana:</span> <strong class="text-slate-900">{{ $penyewaan->fasilitas?->nama }}</strong></div>
                            <div><span class="text-slate-400">Jenis:</span> {{ $penyewaan->fasilitas?->jenis?->nama }}</div>
                            <div><span class="text-slate-400">Lokasi:</span> {{ $penyewaan->fasilitas?->keterangan_lokasi }}</div>
                            <div><span class="text-slate-400">Jumlah Tamu:</span> {{ $penyewaan->jumlah_tamu }} Orang</div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Perhitungan Tagihan Sewa Harian -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="p-3.5">Deskripsi Item Sewa</th>
                                <th class="p-3.5 text-center">Periode Sewa</th>
                                <th class="p-3.5 text-center">Durasi</th>
                                <th class="p-3.5 text-right">Tarif / Hari</th>
                                <th class="p-3.5 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            <tr>
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-900">{{ $penyewaan->fasilitas?->nama }}</div>
                                    @if($penyewaan->fasilitas?->fasilitas_include)
                                        <div class="text-[11px] text-slate-500 mt-0.5">Termasuk: {{ $penyewaan->fasilitas?->fasilitas_include }}</div>
                                    @endif
                                </td>
                                <td class="p-3.5 text-center whitespace-nowrap">
                                    {{ $penyewaan->tanggal_mulai->translatedFormat('d M Y') }}
                                    <br>s/d<br>
                                    {{ $penyewaan->tanggal_selesai->translatedFormat('d M Y') }}
                                </td>
                                <td class="p-3.5 text-center font-bold">
                                    {{ $penyewaan->durasi_hari }} Hari
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    {{ $penyewaan->harga_per_hari_format }}
                                </td>
                                <td class="p-3.5 text-right font-bold text-slate-900 whitespace-nowrap">
                                    {{ $penyewaan->total_tagihan_format }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-slate-50/80 border-t border-slate-200 font-bold text-slate-900">
                            <tr>
                                <td colspan="4" class="p-3.5 text-right uppercase tracking-wider text-xs">Total Pembayaran</td>
                                <td class="p-3.5 text-right text-sm sm:text-base font-black text-emerald-700 whitespace-nowrap">
                                    {{ $penyewaan->total_tagihan_format }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Keterangan Verifikasi & Petunjuk Check-in -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-600 space-y-2">
                    <div class="font-bold text-slate-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Petunjuk Check-in &amp; Tata Tertib Fasilitas:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 pl-1 leading-relaxed text-slate-600">
                        <li>Tunjukkan voucher ini (digital maupun cetak) kepada pengelola saat tiba di lokasi Kampung Gedung Batin.</li>
                        <li>Jam check-in umum dimulai pukul 14.00 WIB dan check-out maksimal pukul 12.00 WIB pada hari kepulangan.</li>
                        <li>Wisatawan diharapkan menjaga kebersihan dan menghormati norma adat budaya Kampung Gedung Batin.</li>
                        @if($penyewaan->catatan_verifikasi)
                            <li class="font-semibold text-slate-800">Catatan Pengelola: {{ $penyewaan->catatan_verifikasi }}</li>
                        @endif
                    </ul>
                </div>

                <!-- Tanda Tangan & Pengesahan BUMDes -->
                <div class="pt-6 border-t border-slate-200 flex items-end justify-between text-xs text-slate-500">
                    <div class="space-y-1">
                        <div>Dokumen ini diterbitkan sah secara elektronik oleh Sistem Informasi SIGEBAT.</div>
                        <div class="font-mono text-[10px] text-slate-400">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
                    </div>
                    <div class="text-center w-48 space-y-12">
                        <div>Pengelola Desa Wisata</div>
                        <div class="border-t border-slate-300 pt-1 font-bold text-slate-800">
                            {{ $penyewaan->verifier?->name ?? 'BUMDes Gedung Batin' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-portal-layout>
