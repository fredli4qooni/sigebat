<x-app-layout>
    <x-slot:title>Kelola Event Budaya</x-slot:title>
    <x-slot:header>Kalender & Agenda Event Budaya</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Ruang Kerja Pengelola &bull; Kalender Kebudayaan</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Agenda Event Budaya Digital
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Manajemen jadwal ritual adat, pentas seni tari, musik tradisional, dan festival budaya digital Kampung Gedung Batin.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a
                    href="{{ route('pengelola.event.create') }}"
                    class="h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-xs sm:text-sm shadow-xs hover:shadow-emerald-600/20 transition-all inline-flex items-center gap-2 no-underline cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Jadwalkan Event Baru</span>
                </a>
            </div>
        </div>

        <!-- 2. Bar Filter & Pencarian Modern -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm">
            <form method="GET" action="{{ route('pengelola.event.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                <!-- Input Pencarian -->
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
                        placeholder="Cari judul atau lokasi event..."
                        class="h-11 pl-10 pr-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                    >
                </div>

                <!-- Dropdown Kategori -->
                <div class="lg:col-span-3">
                    <select
                        name="kategori_id"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Status Waktu Pelaksanaan -->
                <div class="lg:col-span-2">
                    <select
                        name="status_turunan"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                        <option value="">Semua Waktu</option>
                        <option value="akan_datang" {{ request('status_turunan') === 'akan_datang' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="berlangsung" {{ request('status_turunan') === 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="selesai" {{ request('status_turunan') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <!-- Dropdown Status Publikasi -->
                <div class="lg:col-span-1">
                    <select
                        name="status"
                        class="h-11 px-2.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                        <option value="">Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Draft</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Arsip</option>
                    </select>
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button
                        type="submit"
                        class="h-11 flex-1 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm rounded-xl transition-all cursor-pointer flex items-center justify-center gap-1.5 shadow-xs"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if(request()->hasAny(['q', 'kategori_id', 'status_turunan', 'status']))
                        <a
                            href="{{ route('pengelola.event.index') }}"
                            class="h-11 px-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center no-underline"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 3. Tabel Data Event Budaya -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="font-bold tracking-tight text-slate-900 text-base">Daftar Agenda Kegiatan Budaya</h3>
                    <p class="text-xs text-slate-500">Jadwal kegiatan adat, ritual sakral, dan atraksi kesenian</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $eventList->total() }} Event Terdaftar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4 w-20">Poster</th>
                            <th class="py-3.5 px-4">Judul & Kategori</th>
                            <th class="py-3.5 px-4">Jadwal & Waktu Pelaksanaan</th>
                            <th class="py-3.5 px-4">Lokasi</th>
                            <th class="py-3.5 px-4 text-center whitespace-nowrap">Status Waktu</th>
                            <th class="py-3.5 px-4 text-center whitespace-nowrap">Publikasi</th>
                            <th class="py-3.5 px-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse($eventList as $index => $ev)
                            <tr class="h-[76px] hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400 text-xs">
                                    {{ $eventList->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($ev->poster)
                                        <img src="{{ $ev->poster_url }}" alt="{{ $ev->judul }}" class="w-14 h-14 object-cover rounded-xl border border-slate-200 shadow-2xs">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-[9px] font-medium leading-none">Tanpa poster</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $ev->judul }}</div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/90 whitespace-nowrap shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                            <span>{{ $ev->kategori?->nama ?? '-' }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <div class="font-bold text-slate-900 font-mono">
                                        {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                        @if($ev->is_multi_hari)
                                            <span class="text-slate-400 font-normal">s/d</span> {{ $ev->tanggal_selesai ? $ev->tanggal_selesai->translatedFormat('d M Y') : '' }}
                                        @endif
                                    </div>
                                    <div class="text-slate-500 font-mono mt-0.5">
                                        {{ substr($ev->jam_mulai, 0, 5) }} - {{ substr($ev->jam_selesai, 0, 5) }} WIB
                                    </div>
                                    @if($ev->is_multi_hari)
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-semibold border border-slate-200">
                                            Multi-hari
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-700 max-w-xs">
                                    <div class="font-medium line-clamp-2">{{ $ev->lokasi }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <x-tag-status :status="$ev->status_turunan" />
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <x-tag-status :status="$ev->status" />
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('pengelola.event.edit', $ev) }}"
                                            class="h-8 px-3 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold text-xs rounded-xl transition-all shadow-xs inline-flex items-center gap-1 no-underline"
                                        >
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('pengelola.event.destroy', $ev) }}"
                                            onsubmit="return confirm('Hapus agenda event \'{{ $ev->judul }}\'?');"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="h-8 px-3 bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 font-semibold text-xs rounded-xl transition-all shadow-xs inline-flex items-center gap-1 cursor-pointer"
                                            >
                                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="text-sm font-bold text-slate-700">Belum ada agenda kegiatan yang ditemukan</div>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                        Sesuaikan filter pencarian atau klik tombol "Jadwalkan Event Baru" di atas untuk menambahkan agenda kegiatan budaya.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($eventList->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                    {{ $eventList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
