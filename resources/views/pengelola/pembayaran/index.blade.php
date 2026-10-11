<x-app-layout>
    <x-slot:title>Keuangan &amp; Verifikasi Pembayaran Event — SIGEBAT</x-slot:title>
    <x-slot:header>Sistem Informasi Manajemen Kas Event Budaya</x-slot:header>

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
                    <span>Modul Keuangan &amp; Kas Masuk BUMDes &bull; SIM SIGEBAT</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Monitoring &amp; Verifikasi Pembayaran Event
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Kelola aliran kas masuk dari pendaftaran tiket event berbayar, verifikasi struk transfer atau QRIS wisatawan, dan terbitkan rekapitulasi laporan pendapatan kas desa secara terpadu.
                </p>
            </div>

            <!-- Tombol Aksi Banner -->
            <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                <a
                    href="{{ route(auth()->user()->role === 'admin' ? 'admin.pembayaran.cetak-laporan' : 'pengelola.pembayaran.cetak-laporan', ['event_id' => request('event_id')]) }}"
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
                    <span>Atur Rekening &amp; QRIS</span>
                </a>

                <a
                    href="{{ route(auth()->user()->role === 'admin' ? 'admin.peserta.index' : 'pengelola.peserta.index') }}"
                    class="h-11 px-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl font-semibold text-xs sm:text-sm transition-all inline-flex items-center gap-2 no-underline"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Presensi Peserta</span>
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

        <!-- 2. Kartu Metrik Keuangan SIM (4 Kolom) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Metrik 1: Total Pemasukan Kas Lunas -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-1">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <span>Kas Masuk Lunas</span>
                    <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">
                    Rp {{ number_format($metrics['total_kas_masuk'], 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-emerald-700 font-medium">
                    Total {{ $metrics['total_transaksi_lunas'] }} transaksi lunas
                </div>
            </div>

            <!-- Metrik 2: Menunggu Verifikasi Struk -->
            <div class="bg-white border {{ $metrics['total_menunggu_verifikasi'] > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-200/90' }} rounded-2xl p-5 shadow-sm space-y-1">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <span>Perlu Verifikasi</span>
                    <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-amber-700 tracking-tight font-mono">
                    {{ $metrics['total_menunggu_verifikasi'] }}
                </div>
                <div class="text-[11px] text-amber-800 font-medium">
                    {{ $metrics['total_menunggu_verifikasi'] > 0 ? 'Ada bukti bayar baru yang harus ditinjau' : 'Semua bukti telah diperiksa' }}
                </div>
            </div>

            <!-- Metrik 3: Menunggu Pembayaran Pendaftar -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-1">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <span>Menunggu Bayar</span>
                    <span class="p-1.5 rounded-lg bg-slate-100 text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono">
                    {{ $metrics['total_menunggu_pembayaran'] }}
                </div>
                <div class="text-[11px] text-slate-500 font-medium">
                    Invoice aktif belum transfer
                </div>
            </div>

            <!-- Metrik 4: Tiket Terbayar -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-1">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <span>Tiket Terbayar</span>
                    <span class="p-1.5 rounded-lg bg-sky-50 text-sky-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-sky-700 tracking-tight font-mono">
                    {{ $metrics['total_tiket_terjual'] }} <span class="text-xs font-sans text-slate-500 font-normal">Orang</span>
                </div>
                <div class="text-[11px] text-sky-800 font-medium">
                    Kapasitas peserta terkonfirmasi
                </div>
            </div>
        </div>

        <!-- 3. Form Filter & Pencarian SIM -->
        <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <!-- Filter Event -->
                <div class="lg:col-span-4 space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Event Budaya</label>
                    <select
                        name="event_id"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white"
                    >
                        <option value="">Semua Kegiatan Budaya</option>
                        @foreach($events as $evt)
                            <option value="{{ $evt->id }}" {{ request('event_id') == $evt->id ? 'selected' : '' }}>
                                {{ $evt->judul }} ({{ $evt->tipe_event === 'berbayar' ? 'Berbayar' : 'Gratis' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status Pembayaran -->
                <div class="lg:col-span-3 space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Status Pembayaran</label>
                    <select
                        name="status_pembayaran"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white"
                    >
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi" {{ request('status_pembayaran') === 'menunggu_verifikasi' ? 'selected' : '' }}>
                            Perlu Verifikasi (Unggah Bukti)
                        </option>
                        <option value="menunggu_pembayaran" {{ request('status_pembayaran') === 'menunggu_pembayaran' ? 'selected' : '' }}>
                            Menunggu Pembayaran
                        </option>
                        <option value="lunas" {{ request('status_pembayaran') === 'lunas' ? 'selected' : '' }}>
                            Lunas (Terverifikasi)
                        </option>
                        <option value="ditolak" {{ request('status_pembayaran') === 'ditolak' ? 'selected' : '' }}>
                            Ditolak
                        </option>
                    </select>
                </div>

                <!-- Input Pencarian -->
                <div class="lg:col-span-3 space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Cari Data</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Kode / Nama / Email / No. HP"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

                <!-- Tombol Submit & Reset -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button
                        type="submit"
                        class="flex-1 h-10 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs transition-all shadow-xs flex items-center justify-center gap-1.5 cursor-pointer"
                    >
                        <span>Filter</span>
                    </button>
                    @if(request()->anyFilled(['event_id', 'status_pembayaran', 'metode', 'search']))
                        <a
                            href="{{ route(auth()->user()->role === 'admin' ? 'admin.pembayaran.index' : 'pengelola.pembayaran.index') }}"
                            class="h-10 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-xs transition-all flex items-center justify-center no-underline"
                            title="Reset Filter"
                        >
                            &times;
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 4. Tabel Data Transaksi Keuangan -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 uppercase tracking-wider text-[10px] font-bold border-b border-slate-200">
                            <th class="py-3.5 px-4">No</th>
                            <th class="py-3.5 px-4">Kode &amp; Waktu</th>
                            <th class="py-3.5 px-4">Peserta &amp; Kontak</th>
                            <th class="py-3.5 px-4">Event Budaya</th>
                            <th class="py-3.5 px-4">Nominal Tagihan</th>
                            <th class="py-3.5 px-4">Metode / Bukti</th>
                            <th class="py-3.5 px-4">Status Kas</th>
                            <th class="py-3.5 px-4 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($pembayaranList as $idx => $p)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $p->status_pembayaran === 'menunggu_verifikasi' ? 'bg-amber-50/30' : '' }}">
                                <td class="py-3.5 px-4 text-slate-400 font-mono">
                                    {{ $pembayaranList->firstItem() + $idx }}
                                </td>

                                <!-- Kode & Waktu -->
                                <td class="py-3.5 px-4 space-y-0.5">
                                    <div class="font-mono font-bold text-slate-900">
                                        {{ $p->kode_pendaftaran }}
                                    </div>
                                    <div class="text-[10px] text-slate-500">
                                        {{ $p->created_at ? $p->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </div>
                                </td>

                                <!-- Peserta & Kontak -->
                                <td class="py-3.5 px-4 space-y-0.5">
                                    <div class="font-bold text-slate-900">{{ $p->nama_lengkap }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $p->nomor_telepon }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[150px]">{{ $p->email }}</div>
                                </td>

                                <!-- Event Budaya -->
                                <td class="py-3.5 px-4 space-y-0.5">
                                    <div class="font-semibold text-slate-900 max-w-[180px] leading-snug">
                                        {{ $p->event->judul }}
                                    </div>
                                    <div class="text-[10px] text-emerald-700 font-medium">
                                        {{ $p->jumlah_peserta }} Peserta ({{ $p->event->tipe_event === 'berbayar' ? 'Berbayar' : 'Gratis' }})
                                    </div>
                                </td>

                                <!-- Nominal Tagihan -->
                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-bold text-slate-900 text-sm">
                                        {{ $p->total_tagihan_format }}
                                    </div>
                                    @if($p->nominal_per_tiket)
                                        <div class="text-[10px] text-slate-400">
                                            @ Rp {{ number_format($p->nominal_per_tiket, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Metode & Bukti Bayar -->
                                <td class="py-3.5 px-4 space-y-1">
                                    <div class="text-[11px] font-semibold text-slate-800">
                                        {{ $p->metode_pembayaran_label }}
                                    </div>
                                    @if($p->bukti_pembayaran)
                                        <button
                                            type="button"
                                            @click="bukaBukti('{{ asset('storage/' . $p->bukti_pembayaran) }}', 'Bukti Transfer: {{ $p->kode_pendaftaran }} - {{ $p->nama_lengkap }}')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-[10px] border border-emerald-300 transition-all cursor-pointer"
                                        >
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Lihat Struk</span>
                                        </button>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">Belum ada berkas</span>
                                    @endif
                                </td>

                                <!-- Status Kas -->
                                <td class="py-3.5 px-4 space-y-1">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase {{ $p->status_pembayaran_badge_class }}">
                                        {{ $p->status_pembayaran_label }}
                                    </span>
                                    @if($p->verifier)
                                        <div class="text-[9px] text-slate-400">
                                            Oleh: {{ $p->verifier->name }}
                                        </div>
                                    @endif
                                    @if($p->catatan_verifikasi && $p->status_pembayaran === 'ditolak')
                                        <div class="text-[10px] text-rose-600 italic max-w-[140px] truncate" title="{{ $p->catatan_verifikasi }}">
                                            "{{ $p->catatan_verifikasi }}"
                                        </div>
                                    @endif
                                </td>

                                <!-- Aksi Otorisasi Pengelola -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($p->status_pembayaran === 'menunggu_verifikasi' || $p->status_pembayaran === 'menunggu_pembayaran')
                                            <!-- Tombol Setujui Lunas -->
                                            <form
                                                action="{{ route(auth()->user()->role === 'admin' ? 'admin.pembayaran.verifikasi' : 'pengelola.pembayaran.verifikasi', $p->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Konfirmasi bahwa pembayaran ini sah dan ubah status ke LUNAS?');"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="aksi" value="setujui">
                                                <button
                                                    type="submit"
                                                    class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold text-[11px] shadow-2xs transition-all flex items-center gap-1 cursor-pointer"
                                                    title="Setujui Pembayaran (Lunas)"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>Setujui</span>
                                                </button>
                                            </form>

                                            <!-- Tombol Tolak Modal -->
                                            <button
                                                type="button"
                                                @click="bukaModalTolak('{{ route(auth()->user()->role === 'admin' ? 'admin.pembayaran.verifikasi' : 'pengelola.pembayaran.verifikasi', $p->id) }}', '{{ $p->kode_pendaftaran }}')"
                                                class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-300 rounded-lg font-bold text-[11px] transition-all flex items-center gap-1 cursor-pointer"
                                                title="Tolak Pembayaran"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                <span>Tolak</span>
                                            </button>
                                        @elseif($p->status_pembayaran === 'lunas')
                                            <a
                                                href="{{ route('event.pendaftaran.bukti', $p->kode_pendaftaran) }}"
                                                target="_blank"
                                                class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold text-[11px] transition-all no-underline inline-flex items-center gap-1"
                                                title="Lihat Tanda Bukti / E-Tiket"
                                            >
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>E-Tiket</span>
                                            </a>
                                        @else
                                            <!-- Ditolak: Opsi Buka kembali jika transfer ulang -->
                                            <form
                                                action="{{ route(auth()->user()->role === 'admin' ? 'admin.pembayaran.verifikasi' : 'pengelola.pembayaran.verifikasi', $p->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Ubah kembali status ke LUNAS?');"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="aksi" value="setujui">
                                                <button
                                                    type="submit"
                                                    class="px-2 py-1 bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 rounded text-[10px] font-semibold cursor-pointer"
                                                >
                                                    Pulihkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-sm font-bold text-slate-800">Belum ada transaksi pembayaran</div>
                                    <div class="text-xs text-slate-500 mt-1">Data pembayaran akan muncul ketika peserta mendaftar event budaya.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pembayaranList->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $pembayaranList->links() }}
                </div>
            @endif
        </div>

        <!-- 5. Modal Preview Bukti Struk Pembayaran -->
        <div
            x-show="showModalBukti"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/75 backdrop-blur-xs"
            @keydown.escape.window="showModalBukti = false"
        >
            <div
                class="bg-white rounded-2xl max-w-xl w-full p-6 space-y-4 shadow-2xl border border-slate-200"
                @click.away="showModalBukti = false"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-sm truncate max-w-md" x-text="buktiTitle"></h3>
                    <button
                        type="button"
                        @click="showModalBukti = false"
                        class="text-slate-400 hover:text-slate-700 text-xl font-bold cursor-pointer"
                    >
                        &times;
                    </button>
                </div>

                <div class="max-h-[65vh] overflow-y-auto rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center p-2">
                    <img :src="buktiUrl" alt="Bukti Transfer" class="max-h-[60vh] object-contain rounded-lg">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a
                        :href="buktiUrl"
                        target="_blank"
                        class="text-xs text-emerald-700 hover:text-emerald-800 font-semibold underline underline-offset-2 flex items-center gap-1"
                    >
                        <span>Buka Gambar Ukuran Penuh</span>
                        <span>&rarr;</span>
                    </a>
                    <button
                        type="button"
                        @click="showModalBukti = false"
                        class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 cursor-pointer"
                    >
                        Tutup Preview
                    </button>
                </div>
            </div>
        </div>

        <!-- 6. Modal Alasan Tolak Pembayaran -->
        <div
            x-show="showModalTolak"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/75 backdrop-blur-xs"
            @keydown.escape.window="showModalTolak = false"
        >
            <div
                class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200"
                @click.away="showModalTolak = false"
            >
                <div class="space-y-1">
                    <h3 class="font-bold text-slate-900 text-base">
                        Tolak Verifikasi Pembayaran
                    </h3>
                    <p class="text-xs text-slate-500">
                        Kode: <span class="font-mono font-bold text-slate-800" x-text="tolakKode"></span>
                    </p>
                </div>

                <form :action="tolakUrl" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="aksi" value="tolak">

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Alasan Penolakan <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            name="catatan"
                            x-model="catatanTolak"
                            rows="3"
                            required
                            placeholder="Contoh: Bukti buram / nominal transfer tidak sesuai (kurang) / nama pengirim beda."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button
                            type="button"
                            @click="showModalTolak = false"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-xs cursor-pointer"
                        >
                            Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
