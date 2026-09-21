<x-app-layout>
    <x-slot:title>Kelola Objek Wisata</x-slot:title>
    <x-slot:header>Data Objek Wisata Kampung Gedung Batin</x-slot:header>

    <div class="space-y-6">
        <!-- Papan Cokelat (DESIGN.md 7.11: Objek Wisata & Cagar Budaya) -->
        <x-papan warna="cokelat" class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-putih">Kelola Objek Wisata</h2>
                    <p class="text-putih/90 text-sm mt-1">
                        Daftar cagar budaya, rumah adat panggung, dan objek wisata alam Kampung Gedung Batin lengkap dengan koordinat LBS.
                    </p>
                </div>
                <div>
                    <a
                        href="{{ route('pengelola.wisata.create') }}"
                        class="h-[44px] px-5 bg-putih text-aspal border-2 border-aspal rounded-kontrol font-bold text-sm hover:bg-beton no-underline inline-flex items-center gap-2"
                    >
                        <span class="text-lg leading-none">+</span>
                        <span>Tambah Wisata Baru</span>
                    </a>
                </div>
            </div>
        </x-papan>

        <!-- Bar Filter & Pencarian -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-4 sm:p-5">
            <form method="GET" action="{{ route('pengelola.wisata.index') }}" class="flex flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="w-full sm:w-64">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama atau alamat wisata..."
                        class="h-[44px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal placeholder-abu focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                </div>

                <!-- Kategori Filter -->
                <div class="w-48">
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

                <!-- Status Filter -->
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

                    @if(request()->hasAny(['q', 'kategori_id', 'status']))
                        <a href="{{ route('pengelola.wisata.index') }}" class="h-[44px] px-3 flex items-center bg-beton text-aspal font-semibold text-xs border border-abu rounded-kontrol hover:bg-abu/20 no-underline">
                            Reset filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Objek Wisata -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between">
                <h3 class="font-bold font-papan text-aspal text-lg">Daftar objek wisata</h3>
                <span class="text-xs text-abu">{{ $wisataList->total() }} objek terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4 w-20">Foto</th>
                            <th class="py-3 px-4">Nama & Kategori</th>
                            <th class="py-3 px-4">Titik Koordinat (LBS)</th>
                            <th class="py-3 px-4">Operasional & Tiket</th>
                            <th class="py-3 px-4 text-center">Fasilitas</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-sm text-aspal">
                        @forelse($wisataList as $index => $w)
                            <tr class="h-[72px] hover:bg-beton/40">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $wisataList->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($w->foto_utama)
                                        <img src="{{ $w->foto_url }}" alt="{{ $w->nama }}" class="w-14 h-12 object-cover rounded-kontrol border border-aspal">
                                    @else
                                        <div class="w-14 h-12 rounded-kontrol bg-beton border border-abu/40 flex items-center justify-center text-[10px] text-abu font-semibold">
                                            Tanpa foto
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-aspal text-[15px] leading-tight">{{ $w->nama }}</div>
                                    <div class="text-xs text-cokelat font-bold mt-0.5">{{ $w->kategori?->nama ?? '-' }}</div>
                                    <div class="text-[11px] text-abu line-clamp-1 mt-0.5">{{ $w->alamat }}</div>
                                </td>
                                <td class="py-3 px-4 font-mono text-xs">
                                    <div class="text-aspal font-semibold">{{ number_format($w->latitude, 6) }}, {{ number_format($w->longitude, 6) }}</div>
                                    <a href="{{ $w->google_maps_url }}" target="_blank" class="text-biru hover:underline text-[11px] flex items-center gap-0.5 mt-0.5">
                                        <span>Buka Google Maps</span>
                                        <span class="text-[9px]">↗</span>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    <div class="font-semibold text-aspal">{{ $w->jam_operasional ?: '-' }}</div>
                                    <div class="text-abu mt-0.5">{{ $w->harga_tiket ?: 'Gratis' }}</div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($w->fasilitas_count > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-tag bg-biru text-putih text-xs font-bold">
                                            {{ $w->fasilitas_count }} fasilitas
                                        </span>
                                    @else
                                        <span class="text-xs text-abu">0 fasilitas</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <x-tag-status :status="$w->status" />
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('pengelola.wisata.edit', $w) }}"
                                            class="px-3 py-1.5 bg-putih border-2 border-aspal rounded-kontrol text-xs font-bold text-aspal hover:bg-beton no-underline"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('pengelola.wisata.destroy', $w) }}"
                                            onsubmit="return confirm('Hapus objek wisata \'{{ $w->nama }}\'? Data akan dipindahkan ke arsip.');"
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
                                    Belum ada objek wisata yang terdaftar. Klik "+ Tambah Wisata Baru" di atas untuk menambahkan objek wisata pertama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($wisataList->hasPages())
                <div class="p-4 border-t border-beton">
                    {{ $wisataList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
