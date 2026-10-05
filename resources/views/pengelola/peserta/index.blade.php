<x-app-layout>
    <x-slot:title>Monitoring Peserta Event Budaya — SIGEBAT</x-slot:title>
    <x-slot:header>Sistem Informasi Manajemen Peserta Event Budaya</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Sistem Informasi Manajemen &bull; Partisipasi Wisatawan</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Monitoring &amp; Presensi Peserta Event
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Kelola data pendaftaran wisatawan, monitor kapasitas kuota acara, lakukan konfirmasi kehadiran di lokasi (*check-in*), serta cetak lembar daftar hadir resmi untuk evaluasi kegiatan.
                </p>
            </div>

            <!-- Tombol Aksi Banner -->
            <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                @if(request('event_id') && $selectedEvent)
                    <a
                        href="{{ route(auth()->user()->role === 'admin' ? 'admin.event.cetak-peserta' : 'pengelola.event.cetak-peserta', $selectedEvent->id) }}"
                        target="_blank"
                        class="h-11 px-5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs sm:text-sm shadow-xs transition-all inline-flex items-center gap-2 no-underline cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Cetak Presensi Event Ini</span>
                    </a>
                @endif

                <a
                    href="{{ route(auth()->user()->role === 'admin' ? 'admin.master.event' : 'pengelola.event.index') }}"
                    class="h-11 px-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl font-semibold text-xs sm:text-sm transition-all inline-flex items-center gap-2 no-underline"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Daftar Event Budaya</span>
                </a>
            </div>
        </div>

        <!-- 2. Kartu Metrik Statistik SIM (4 Kolom) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Metrik 1: Total Registrasi Formulir -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-1">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <span>Total Pendaftar</span>
                    <span class="p-1.5 rounded-lg bg-sky-50 text-sky-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ number_format($totalPendaftar) }}
                </div>
                <div class="text-xs text-slate-500">Formulir pendaftaran masuk</div>
            </div>

            <!-- Metrik 2: Total Akumulasi Peserta (Orang) -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-1">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <span>Estimasi Pengunjung</span>
                    <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-emerald-800 tracking-tight">
                    {{ number_format($totalOrang) }}
                </div>
                <div class="text-xs text-slate-500">Akumulasi orang terdaftar</div>
            </div>

            <!-- Metrik 3: Hadir di Lokasi (Check-in) -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-1">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <span>Konfirmasi Hadir</span>
                    <span class="p-1.5 rounded-lg bg-teal-50 text-teal-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-teal-700 tracking-tight">
                    {{ number_format($totalHadir) }}
                </div>
                <div class="text-xs text-slate-500">Peserta check-in di hari H</div>
            </div>

            <!-- Metrik 4: Pembatalan -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm space-y-1">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <span>Pembatalan</span>
                    <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-500 tracking-tight">
                    {{ number_format($totalBatal) }}
                </div>
                <div class="text-xs text-slate-500">Registrasi dibatalkan</div>
            </div>
        </div>

        <!-- 3. Bar Filter & Pencarian -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm">
            <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                <!-- Pencarian Teks -->
                <div class="lg:col-span-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama, kode registrasi, HP, instansi..."
                        class="h-11 pl-10 pr-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                    >
                </div>

                <!-- Filter Event -->
                <div class="lg:col-span-4">
                    <select
                        name="event_id"
                        onchange="this.form.submit()"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                        <option value="">Semua Event Budaya ({{ $eventList->count() }})</option>
                        @foreach($eventList as $ev)
                            <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>
                                {{ Str::limit($ev->judul, 40) }} ({{ $ev->tanggal_mulai ? $ev->tanggal_mulai->format('d/m/Y') : '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status Kehadiran -->
                <div class="lg:col-span-2">
                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                        <option value="">Semua Status</option>
                        <option value="terdaftar" {{ request('status') === 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir di Lokasi</option>
                        <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button
                        type="submit"
                        class="h-11 flex-1 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-xs"
                    >
                        Filter
                    </button>
                    @if(request()->hasAny(['q', 'event_id', 'status']))
                        <a
                            href="{{ url()->current() }}"
                            class="h-11 px-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all inline-flex items-center justify-center no-underline"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 4. Tabel Data Peserta Event (SIM) -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider">
                            <th class="py-4 px-4 sm:px-6">Kode Registrasi</th>
                            <th class="py-4 px-4">Event Budaya</th>
                            <th class="py-4 px-4">Nama Peserta & Rombongan</th>
                            <th class="py-4 px-4">Kontak & Asal</th>
                            <th class="py-4 px-4">Status Kehadiran</th>
                            <th class="py-4 px-4">Waktu Daftar</th>
                            <th class="py-4 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-normal text-slate-800">
                        @forelse($pesertaList as $p)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Kode Registrasi -->
                                <td class="py-4 px-4 sm:px-6 align-top">
                                    <span class="font-mono text-xs font-bold text-slate-900 bg-slate-100 px-2 py-1 rounded-lg border border-slate-200 inline-block">
                                        {{ $p->kode_pendaftaran }}
                                    </span>
                                </td>

                                <!-- Event Budaya -->
                                <td class="py-4 px-4 align-top max-w-[200px]">
                                    <div class="font-semibold text-slate-900 leading-snug line-clamp-2">
                                        {{ $p->event->judul }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $p->event->tanggal_mulai ? $p->event->tanggal_mulai->format('d M Y') : '' }}
                                    </div>
                                </td>

                                <!-- Nama Peserta & Rombongan -->
                                <td class="py-4 px-4 align-top">
                                    <div class="font-bold text-slate-900">
                                        {{ $p->nama_lengkap }}
                                    </div>
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            {{ $p->jumlah_peserta }} Orang
                                        </span>
                                        @if($p->catatan)
                                            <span 
                                                class="text-xs text-slate-400 cursor-pointer underline decoration-dotted" 
                                                title="{{ $p->catatan }}"
                                            >
                                                (Catatan)
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Kontak & Asal -->
                                <td class="py-4 px-4 align-top text-xs space-y-1">
                                    <div class="font-medium text-slate-800">
                                        {{ $p->asal_instansi }}
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <a
                                            href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $p->nomor_telepon)) }}"
                                            target="_blank"
                                            class="text-emerald-700 hover:text-emerald-800 font-semibold no-underline inline-flex items-center gap-1"
                                        >
                                            <span>WA: {{ $p->nomor_telepon }}</span>
                                        </a>
                                    </div>
                                    <div class="text-slate-400 truncate max-w-[160px]">
                                        {{ $p->email }}
                                    </div>
                                </td>

                                <!-- Status Kehadiran (Dropdown Presensi Cepat) -->
                                <td class="py-4 px-4 align-top">
                                    <form 
                                        method="POST" 
                                        action="{{ route(auth()->user()->role === 'admin' ? 'admin.peserta.update-status' : 'pengelola.peserta.update-status', $p->id) }}"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <select
                                            name="status"
                                            onchange="this.form.submit()"
                                            class="text-xs font-bold rounded-lg border px-2.5 py-1.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/20 {{ $p->status_badge_class }}"
                                        >
                                            <option value="terdaftar" {{ $p->status === 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                                            <option value="hadir" {{ $p->status === 'hadir' ? 'selected' : '' }}>Hadir di Lokasi</option>
                                            <option value="batal" {{ $p->status === 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </form>
                                </td>

                                <!-- Waktu Daftar -->
                                <td class="py-4 px-4 align-top text-xs text-slate-500 whitespace-nowrap">
                                    {{ $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-' }}
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-4 align-top text-center">
                                    <form
                                        method="POST"
                                        action="{{ route(auth()->user()->role === 'admin' ? 'admin.peserta.destroy' : 'pengelola.peserta.destroy', $p->id) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftaran {{ $p->nama_lengkap }} ({{ $p->kode_pendaftaran }})?');"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer"
                                            title="Hapus Data Peserta"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-6 text-center text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="font-bold text-slate-700 text-sm">Belum Ada Data Peserta Terdaftar</div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        @if(request()->hasAny(['q', 'event_id', 'status']))
                                            Tidak ditemukan peserta yang sesuai dengan filter pencarian.
                                        @else
                                            Data wisatawan yang mendaftar pada kegiatan budaya akan tampil di sini.
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if($pesertaList->hasPages())
                <div class="p-4 sm:p-5 border-t border-slate-200">
                    {{ $pesertaList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
