<x-app-layout>
    <x-slot:title>Kelola Fasilitas Desa</x-slot:title>
    <x-slot:header>Data Fasilitas Desa Wisata</x-slot:header>

    <div class="space-y-6">
        <!-- Papan Biru (DESIGN.md 7.11: Fasilitas Pendukung) -->
        <x-papan warna="biru" class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-putih">Kelola Fasilitas Desa</h2>
                    <p class="text-putih/90 text-sm mt-1">
                        Daftar sarana pendukung kenyamanan wisatawan, baik fasilitas umum desa maupun fasilitas khusus di objek wisata.
                    </p>
                </div>
                <div>
                    <a
                        href="{{ route('pengelola.fasilitas.create') }}"
                        class="h-[44px] px-5 bg-putih text-aspal border-2 border-aspal rounded-kontrol font-bold text-sm hover:bg-beton no-underline inline-flex items-center gap-2"
                    >
                        <span class="text-lg leading-none">+</span>
                        <span>Tambah Fasilitas</span>
                    </a>
                </div>
            </div>
        </x-papan>

        <!-- Bar Filter & Pencarian -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-4 sm:p-5">
            <form method="GET" action="{{ route('pengelola.fasilitas.index') }}" class="flex flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="w-full sm:w-64">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama atau lokasi fasilitas..."
                        class="h-[44px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal placeholder-abu focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                </div>

                <!-- Jenis Filter -->
                <div class="w-48">
                    <select
                        name="jenis_id"
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                        <option value="">Semua jenis</option>
                        @foreach($jenisList as $j)
                            <option value="{{ $j->id }}" {{ request('jenis_id') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Lingkup Filter -->
                <div class="w-48">
                    <select
                        name="lingkup"
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                        <option value="">Semua lingkup</option>
                        <option value="umum" {{ request('lingkup') === 'umum' ? 'selected' : '' }}>Fasilitas Umum Desa</option>
                        <option value="khusus" {{ request('lingkup') === 'khusus' ? 'selected' : '' }}>Di Objek Wisata</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="h-[44px] px-4 bg-aspal text-putih font-bold text-sm rounded-kontrol hover:bg-black cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['q', 'jenis_id', 'lingkup']))
                        <a href="{{ route('pengelola.fasilitas.index') }}" class="h-[44px] px-3 flex items-center bg-beton text-aspal font-semibold text-xs border border-abu rounded-kontrol hover:bg-abu/20 no-underline">
                            Reset filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Fasilitas -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between">
                <h3 class="font-bold font-papan text-aspal text-lg">Daftar fasilitas desa</h3>
                <span class="text-xs text-abu">{{ $fasilitasList->total() }} fasilitas terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4 w-16">Foto</th>
                            <th class="py-3 px-4">Nama Fasilitas</th>
                            <th class="py-3 px-4">Jenis</th>
                            <th class="py-3 px-4">Lingkup / Objek Wisata</th>
                            <th class="py-3 px-4">Lokasi & Keterangan</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-sm text-aspal">
                        @forelse($fasilitasList as $index => $f)
                            <tr class="h-[64px] hover:bg-beton/40">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $fasilitasList->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($f->foto)
                                        <img src="{{ $f->foto_url }}" alt="{{ $f->nama }}" class="w-12 h-10 object-cover rounded-kontrol border border-aspal">
                                    @else
                                        <div class="w-12 h-10 rounded-kontrol bg-beton border border-abu/40 flex items-center justify-center text-[10px] text-abu font-semibold">
                                            -
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-aspal text-[15px] leading-tight">{{ $f->nama }}</div>
                                    @if($f->deskripsi)
                                        <div class="text-xs text-abu line-clamp-1 mt-0.5">{{ $f->deskripsi }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-semibold text-xs text-biru">
                                    {{ $f->jenis?->nama ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    @if($f->is_umum)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-tag bg-hijau text-putih font-bold">
                                            Umum (Desa)
                                        </span>
                                    @else
                                        <div class="font-bold text-aspal">{{ $f->objekWisata?->nama ?? '-' }}</div>
                                        <div class="text-[11px] text-abu">Fasilitas Wisata</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs text-aspal">
                                    <div class="font-medium">{{ $f->keterangan_lokasi }}</div>
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('pengelola.fasilitas.edit', $f) }}"
                                            class="px-3 py-1.5 bg-putih border-2 border-aspal rounded-kontrol text-xs font-bold text-aspal hover:bg-beton no-underline"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('pengelola.fasilitas.destroy', $f) }}"
                                            onsubmit="return confirm('Hapus fasilitas \'{{ $f->nama }}\'?');"
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
                                <td colspan="7" class="py-10 text-center text-abu">
                                    Belum ada data fasilitas yang ditambahkan. Klik "+ Tambah Fasilitas" untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($fasilitasList->hasPages())
                <div class="p-4 border-t border-beton">
                    {{ $fasilitasList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
