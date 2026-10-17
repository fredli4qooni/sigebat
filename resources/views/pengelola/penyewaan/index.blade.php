<x-app-layout>
    <x-slot:title>Penyewaan Fasilitas Desa — SIGEBAT</x-slot:title>
    <x-slot:header>Sistem Informasi Manajemen Sewa Fasilitas Desa</x-slot:header>

    <div
        class="space-y-6"
        x-data="{
            showModalBukti: false,
            buktiUrl: '',
            buktiTitle: '',
            showModalTolak: false,
            tolakUrl: '',
            tolakKode: '',
            catatanTolak: '',
            bukaBukti(url, title) {
                this.buktiUrl = url;
                this.buktiTitle = title;
                this.showModalBukti = true;
            },
            bukaModalTolak(url, kode) {
                this.tolakUrl = url;
                this.tolakKode = kode;
                this.catatanTolak = '';
                this.showModalTolak = true;
            }
        }"
    >
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Modul Penyewaan Sarana &amp; Homestay &bull; BUMDes SIGEBAT</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Monitoring &amp; Verifikasi Sewa Fasilitas
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Kelola reservasi harian homestay, gazebo, dan aula desa. Verifikasi bukti transfer wisatawan dan monitor penggunaan fasilitas desa secara transparan.
                </p>
            </div>

            <!-- Tombol Aksi Banner -->
            <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                <a
                    href="{{ route(auth()->user()->role === 'admin' ? 'admin.penyewaan.cetak-laporan' : 'pengelola.penyewaan.cetak-laporan', request()->query()) }}"
                    target="_blank"
                    class="h-11 px-5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs sm:text-sm shadow-xs transition-all inline-flex items-center gap-2 no-underline cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak Rekap Kas (PDF)</span>
                </a>

                <a
                    href="{{ route(auth()->user()->role === 'admin' ? 'admin.pengaturan-pembayaran.edit' : 'pengelola.pengaturan-pembayaran.edit') }}"
                    class="h-11 px-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl font-semibold text-xs sm:text-sm transition-all inline-flex items-center gap-2 no-underline"
                >
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Atur Rekening</span>
                </a>

                <a
                    href="{{ route('pengelola.fasilitas.index') }}"
                    class="h-11 px-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl font-semibold text-xs sm:text-sm transition-all inline-flex items-center gap-2 no-underline"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Kelola Fasilitas</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">&check;</span>
                <div class="font-bold text-emerald-950">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">&excl;</span>
                <div class="font-bold text-amber-950">{{ session('warning') }}</div>
            </div>
        @endif

        <!-- 2. Kartu Metrik SIM Sewa (5 Kolom) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-1">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kas Masuk Sewa</div>
                <div class="text-xl font-black text-emerald-600">Rp {{ number_format($metrics['total_kas_sewa'], 0, ',', '.') }}</div>
                <div class="text-[11px] text-slate-400">Total pendapatan lunas</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-1">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Menunggu Verifikasi</div>
                <div class="text-xl font-black text-blue-600">{{ $metrics['menunggu_verifikasi'] }}</div>
                <div class="text-[11px] text-slate-400">Bukti struk perlu dicek</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-1">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Menunggu Bayar</div>
                <div class="text-xl font-black text-amber-600">{{ $metrics['menunggu_pembayaran'] }}</div>
                <div class="text-[11px] text-slate-400">Belum unggah bukti</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-1">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Reservasi Lunas</div>
                <div class="text-xl font-black text-slate-900">{{ $metrics['total_booking_lunas'] }}</div>
                <div class="text-[11px] text-slate-400">Transaksi terkonfirmasi</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-1 col-span-2 sm:col-span-1">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sedang Digunakan</div>
                <div class="text-xl font-black text-indigo-600">{{ $metrics['sedang_digunakan'] }}</div>
                <div class="text-[11px] text-slate-400">Tamu sedang check-in</div>
            </div>
        </div>

        <!-- 3. Form Filter & Pencarian -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <div class="lg:col-span-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode sewa, nama penyewa, no HP..."
                        class="h-10 px-3.5 border border-slate-300 rounded-xl w-full text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600"
                    >
                </div>

                <div>
                    <select
                        name="fasilitas_id"
                        class="h-10 px-3 border border-slate-300 rounded-xl w-full text-xs sm:text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600"
                    >
                        <option value="">Semua Fasilitas</option>
                        @foreach($fasilitasList as $fas)
                            <option value="{{ $fas->id }}" {{ request('fasilitas_id') == $fas->id ? 'selected' : '' }}>
                                {{ $fas->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select
                        name="status_pembayaran"
                        class="h-10 px-3 border border-slate-300 rounded-xl w-full text-xs sm:text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600"
                    >
                        <option value="">Status Bayar: Semua</option>
                        <option value="menunggu_verifikasi" {{ request('status_pembayaran') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="menunggu_pembayaran" {{ request('status_pembayaran') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="lunas" {{ request('status_pembayaran') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="ditolak" {{ request('status_pembayaran') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="h-10 flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all cursor-pointer shadow-xs"
                    >
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'fasilitas_id', 'status_pembayaran', 'status_sewa']))
                        <a
                            href="{{ url()->current() }}"
                            class="h-10 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl flex items-center justify-center no-underline transition-all"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 4. Tabel Data Reservasi Sewa -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Daftar Transaksi Sewa Fasilitas</h3>
                    <p class="text-xs text-slate-500">Monitoring reservasi dan konfirmasi pembayaran kas masuk BUMDes</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $penyewaanList->total() }} Reservasi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Kode &amp; Waktu</th>
                            <th class="py-3.5 px-4">Penyewa</th>
                            <th class="py-3.5 px-4">Fasilitas &amp; Periode</th>
                            <th class="py-3.5 px-4">Tagihan</th>
                            <th class="py-3.5 px-4">Pembayaran</th>
                            <th class="py-3.5 px-4">Status Sewa</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($penyewaanList as $index => $sewa)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    {{ $penyewaanList->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-bold text-slate-900">{{ $sewa->kode_sewa }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $sewa->created_at->translatedFormat('d M Y, H:i') }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $sewa->nama_penyewa }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $sewa->nomor_telepon }} &bull; {{ $sewa->email }}</div>
                                    @if($sewa->asal_instansi)
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $sewa->asal_instansi }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $sewa->fasilitas?->nama }}</div>
                                    <div class="text-[11px] text-slate-600 font-medium">
                                        {{ $sewa->tanggal_mulai->translatedFormat('d M') }} - {{ $sewa->tanggal_selesai->translatedFormat('d M Y') }}
                                        <span class="font-bold text-emerald-700">({{ $sewa->durasi_hari }} Hari)</span>
                                    </div>
                                    <div class="text-[11px] text-slate-400">Tamu: {{ $sewa->jumlah_tamu }} Org</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $sewa->total_tagihan_format }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $sewa->harga_per_hari_format }}/hari</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $sewa->status_pembayaran_badge_class }}">
                                        {{ $sewa->status_pembayaran_label }}
                                    </span>
                                    @if($sewa->bukti_pembayaran)
                                        <div class="mt-1">
                                            <button
                                                type="button"
                                                @click="bukaBukti('{{ $sewa->bukti_pembayaran_url }}', 'Bukti Transfer {{ $sewa->kode_sewa }} - {{ $sewa->nama_penyewa }}')"
                                                class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 underline cursor-pointer"
                                            >
                                                Lihat Struk Transfer &rarr;
                                            </button>
                                        </div>
                                    @endif
                                    @if($sewa->catatan_verifikasi)
                                        <div class="text-[10px] text-slate-500 mt-0.5 max-w-xs truncate" title="{{ $sewa->catatan_verifikasi }}">
                                            Note: {{ $sewa->catatan_verifikasi }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <form
                                        method="POST"
                                        action="{{ route(auth()->user()->role === 'admin' ? 'admin.penyewaan.update-status' : 'pengelola.penyewaan.update-status', $sewa) }}"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <select
                                            name="status_sewa"
                                            onchange="this.form.submit()"
                                            class="h-8 px-2 border border-slate-300 rounded-lg text-xs font-semibold text-slate-800 bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500 cursor-pointer"
                                        >
                                            <option value="diajukan" {{ $sewa->status_sewa === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                            <option value="dikonfirmasi" {{ $sewa->status_sewa === 'dikonfirmasi' ? 'selected' : '' }}>Terkonfirmasi</option>
                                            <option value="digunakan" {{ $sewa->status_sewa === 'digunakan' ? 'selected' : '' }}>Sedang Digunakan</option>
                                            <option value="selesai" {{ $sewa->status_sewa === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="batal" {{ $sewa->status_sewa === 'batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($sewa->status_pembayaran === 'menunggu_verifikasi')
                                            <!-- Tombol Setujui Lunas -->
                                            <form
                                                method="POST"
                                                action="{{ route(auth()->user()->role === 'admin' ? 'admin.penyewaan.verifikasi' : 'pengelola.penyewaan.verifikasi', $sewa) }}"
                                                onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pembayaran sewa {{ $sewa->kode_sewa }} sebesar {{ $sewa->total_tagihan_format }}?')"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="aksi" value="setujui">
                                                <button
                                                    type="submit"
                                                    class="h-8 px-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition-all cursor-pointer shadow-2xs inline-flex items-center gap-1"
                                                    title="Setujui Pembayaran Sah"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>Setujui</span>
                                                </button>
                                            </form>

                                            <!-- Tombol Tolak -->
                                            <button
                                                type="button"
                                                @click="bukaModalTolak('{{ route(auth()->user()->role === 'admin' ? 'admin.penyewaan.verifikasi' : 'pengelola.penyewaan.verifikasi', $sewa) }}', '{{ $sewa->kode_sewa }}')"
                                                class="h-8 px-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs rounded-lg transition-all cursor-pointer inline-flex items-center gap-1"
                                                title="Tolak Pembayaran"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                <span>Tolak</span>
                                            </button>
                                        @endif

                                        <a
                                            href="{{ route('sewa.bukti', $sewa->kode_sewa) }}"
                                            target="_blank"
                                            class="h-8 px-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-lg text-xs font-semibold no-underline inline-flex items-center gap-1"
                                            title="Lihat Voucher Resmi"
                                        >
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Voucher</span>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route(auth()->user()->role === 'admin' ? 'admin.penyewaan.destroy' : 'pengelola.penyewaan.destroy', $sewa) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sewa {{ $sewa->kode_sewa }}?')"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="h-8 w-8 text-rose-600 hover:bg-rose-50 rounded-lg border border-transparent hover:border-rose-200 flex items-center justify-center cursor-pointer transition-all"
                                                title="Hapus Data Sewa"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">
                                    Tidak ada data penyewaan fasilitas yang sesuai kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($penyewaanList->hasPages())
                <div class="p-4 border-t border-slate-100 flex justify-center">
                    {{ $penyewaanList->links() }}
                </div>
            @endif
        </div>

        <!-- 5. Modal Pratinjau Bukti Struk Transfer -->
        <div
            x-show="showModalBukti"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4" @click.away="showModalBukti = false">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-sm truncate" x-text="buktiTitle">Bukti Transfer</h3>
                    <button type="button" @click="showModalBukti = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="max-h-[70vh] overflow-auto flex items-center justify-center bg-slate-50 rounded-xl p-2 border border-slate-200">
                    <img :src="buktiUrl" alt="Bukti Transfer" class="max-w-full h-auto rounded-lg">
                </div>
                <div class="text-right">
                    <button
                        type="button"
                        @click="showModalBukti = false"
                        class="h-9 px-4 bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- 6. Modal Alasan Tolak Pembayaran -->
        <div
            x-show="showModalTolak"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4" @click.away="showModalTolak = false">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-sm">Tolak Pembayaran <span x-text="tolakKode"></span></h3>
                    <button type="button" @click="showModalTolak = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form :action="tolakUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="aksi" value="tolak">
                    <div>
                        <label for="catatan_tolak" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alasan Penolakan Pembayaran <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            id="catatan_tolak"
                            name="catatan"
                            rows="3"
                            required
                            placeholder="Contoh: Bukti transfer tidak jelas / nominal tidak sesuai / dana belum masuk rekening BUMDes..."
                            class="w-full p-3 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600"
                        ></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button
                            type="button"
                            @click="showModalTolak = false"
                            class="h-9 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="h-9 px-4 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold cursor-pointer"
                        >
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
