<x-app-layout>
    <x-slot:title>Kelola Event Budaya</x-slot:title>
    <x-slot:header>Kalender & Agenda Event Budaya</x-slot:header>

    <div class="space-y-6">
        <!-- Papan Kuning (DESIGN.md 7.11: Event Budaya) -->
        <x-papan warna="kuning" class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-aspal">Agenda Event Budaya Digital</h2>
                    <p class="text-aspal/80 text-sm mt-1">
                        Jadwal upacara adat begawi, pagelaran tari, musik tradisional, dan festival budaya Kampung Gedung Batin.
                    </p>
                </div>
                <div>
                    <a
                        href="{{ route('pengelola.event.create') }}"
                        class="h-[44px] px-5 bg-aspal text-putih rounded-kontrol font-bold text-sm hover:bg-black no-underline inline-flex items-center gap-2"
                    >
                        <span class="text-lg leading-none">+</span>
                        <span>Jadwalkan Event Baru</span>
                    </a>
                </div>
            </div>
        </x-papan>

        <!-- Bar Filter & Pencarian -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-4 sm:p-5">
            <form method="GET" action="{{ route('pengelola.event.index') }}" class="flex flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="w-full sm:w-60">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari judul atau lokasi event..."
                        class="h-[44px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal placeholder-abu focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                </div>

                <!-- Kategori Filter -->
                <div class="w-44">
                    <select
                        name="kategori_id"
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                        <option value="">Semua kategori</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Turunan (Waktu) Filter -->
                <div class="w-44">
                    <select
                        name="status_turunan"
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                        <option value="">Semua status waktu</option>
                        <option value="akan_datang" {{ request('status_turunan') === 'akan_datang' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="berlangsung" {{ request('status_turunan') === 'berlangsung' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="selesai" {{ request('status_turunan') === 'selesai' ? 'selected' : '' }}>Telah Selesai</option>
                    </select>
                </div>

                <!-- Status Publikasi Filter -->
                <div class="w-36">
                    <select
                        name="status"
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                        <option value="">Semua status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="h-[44px] px-4 bg-aspal text-putih font-bold text-sm rounded-kontrol hover:bg-black cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['q', 'kategori_id', 'status_turunan', 'status']))
                        <a href="{{ route('pengelola.event.index') }}" class="h-[44px] px-3 flex items-center bg-beton text-aspal font-semibold text-xs border border-abu rounded-kontrol hover:bg-abu/20 no-underline">
                            Reset filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Event Budaya -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between">
                <h3 class="font-bold font-papan text-aspal text-lg">Daftar agenda kegiatan budaya</h3>
                <span class="text-xs text-abu">{{ $eventList->total() }} event terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4 w-16">Poster</th>
                            <th class="py-3 px-4">Judul & Kategori</th>
                            <th class="py-3 px-4">Jadwal & Waktu Pelaksanaan</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4 text-center">Status Waktu</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-sm text-aspal">
                        @forelse($eventList as $index => $ev)
                            <tr class="h-[72px] hover:bg-beton/40">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $eventList->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($ev->poster)
                                        <img src="{{ $ev->poster_url }}" alt="{{ $ev->judul }}" class="w-12 h-14 object-cover rounded-kontrol border border-aspal">
                                    @else
                                        <div class="w-12 h-14 rounded-kontrol bg-beton border border-abu/40 flex items-center justify-center text-[10px] text-abu font-semibold text-center p-1">
                                            Tanpa poster
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-aspal text-[15px] leading-tight">{{ $ev->judul }}</div>
                                    <div class="text-xs text-cokelat font-bold mt-0.5">{{ $ev->kategori?->nama ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    <div class="font-bold text-aspal font-mono">
                                        {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                        @if($ev->is_multi_hari)
                                            <span class="text-abu">s/d</span> {{ $ev->tanggal_selesai ? $ev->tanggal_selesai->translatedFormat('d M Y') : '' }}
                                        @endif
                                    </div>
                                    <div class="text-abu font-mono mt-0.5">
                                        {{ substr($ev->jam_mulai, 0, 5) }} - {{ substr($ev->jam_selesai, 0, 5) }} WIB
                                    </div>
                                    @if($ev->is_multi_hari)
                                        <span class="inline-block mt-0.5 px-1.5 py-0.2 rounded bg-beton text-aspal text-[10px] font-bold">
                                            Multi-hari
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs text-aspal max-w-xs">
                                    <div class="font-medium line-clamp-2">{{ $ev->lokasi }}</div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-tag text-xs font-bold {{ $ev->status_turunan_badge_class }}">
                                        {{ $ev->status_turunan }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <x-tag-status :status="$ev->status" />
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('pengelola.event.edit', $ev) }}"
                                            class="px-3 py-1.5 bg-putih border-2 border-aspal rounded-kontrol text-xs font-bold text-aspal hover:bg-beton no-underline"
                                        >
                                            Edit
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
                                                class="px-3 py-1.5 bg-putih border-2 border-merah rounded-kontrol text-xs font-bold text-merah hover:bg-merah hover:text-putih cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center text-abu">
                                    Belum ada event budaya yang dijadwalkan. Klik "+ Jadwalkan Event Baru" di atas untuk menambahkan kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($eventList->hasPages())
                <div class="p-4 border-t border-beton">
                    {{ $eventList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
