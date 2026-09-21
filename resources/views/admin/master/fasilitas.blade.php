<x-app-layout>
    <x-slot:title>Data Master Jenis Fasilitas</x-slot:title>
    <x-slot:header>Data Master: Jenis Fasilitas</x-slot:header>

    <div class="space-y-6">
        <!-- Tab Navigasi Data Master -->
        <div class="flex flex-wrap gap-2 border-b-2 border-aspal pb-3">
            <a href="{{ route('admin.master.wisata') }}" class="px-4 py-2 rounded-kontrol font-bold text-sm no-underline {{ request()->routeIs('admin.master.wisata*') ? 'bg-cokelat text-putih' : 'bg-putih border-2 border-aspal text-aspal hover:bg-beton' }}">
                Kategori Wisata
            </a>
            <a href="{{ route('admin.master.event') }}" class="px-4 py-2 rounded-kontrol font-bold text-sm no-underline {{ request()->routeIs('admin.master.event*') ? 'bg-kuning text-aspal' : 'bg-putih border-2 border-aspal text-aspal hover:bg-beton' }}">
                Kategori Event Budaya
            </a>
            <a href="{{ route('admin.master.fasilitas') }}" class="px-4 py-2 rounded-kontrol font-bold text-sm no-underline {{ request()->routeIs('admin.master.fasilitas*') ? 'bg-biru text-putih' : 'bg-putih border-2 border-aspal text-aspal hover:bg-beton' }}">
                Jenis Fasilitas
            </a>
        </div>

        <!-- Papan Biru (DESIGN.md 7.11: Fasilitas Berwarna Biru) -->
        <x-papan warna="biru" class="p-6">
            <h2 class="text-2xl font-bold font-papan text-putih">Jenis Fasilitas Pendukung</h2>
            <p class="text-putih/90 text-sm mt-1">
                Klasifikasi sarana dan prasarana penunjang kenyamanan wisatawan (misalnya: Musala, Toilet, Area Parkir, Warung).
            </p>
        </x-papan>

        <!-- Form Tambah Jenis Fasilitas -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-6">
            <h3 class="text-lg font-bold font-papan text-aspal mb-4">Tambah jenis fasilitas baru</h3>
            <form method="POST" action="{{ route('admin.master.fasilitas.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <x-input-label for="nama" value="Nama jenis fasilitas" />
                    <x-text-input id="nama" name="nama" type="text" :value="old('nama')" required placeholder="Contoh: Pusat Medis & P3K" />
                    <x-input-error :messages="$errors->get('nama')" />
                </div>
                <div>
                    <x-input-label for="icon" value="Kode ikon (Phosphor / Teks)" />
                    <x-text-input id="icon" name="icon" type="text" :value="old('icon', 'buildings')" placeholder="Contoh: buildings, mosque, toilet" />
                    <x-input-error :messages="$errors->get('icon')" />
                </div>
                <div>
                    <x-primary-button class="w-full">
                        + Tambah Jenis
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Tabel Data Master Jenis Fasilitas -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between">
                <h3 class="font-bold font-papan text-aspal text-lg">Daftar jenis fasilitas</h3>
                <span class="text-xs text-abu">{{ $jenisList->total() }} jenis terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Nama Jenis Fasilitas</th>
                            <th class="py-3 px-4">Ikon</th>
                            <th class="py-3 px-4 text-center">Fasilitas Terkait</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-sm text-aspal">
                        @forelse($jenisList as $index => $jenis)
                            <tr class="h-[60px] hover:bg-beton/40" x-data="{ editing: false }">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $jenisList->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4 font-semibold">
                                    <template x-if="!editing">
                                        <div class="text-[15px] font-bold text-aspal">{{ $jenis->nama }}</div>
                                    </template>
                                    <template x-if="editing">
                                        <form id="edit-fasilitas-form-{{ $jenis->id }}" method="POST" action="{{ route('admin.master.fasilitas.update', $jenis) }}" class="space-y-2 py-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nama" value="{{ $jenis->nama }}" required class="h-[40px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm">
                                            <input type="text" name="icon" value="{{ $jenis->icon }}" placeholder="Ikon" class="h-[36px] px-3 border border-abu rounded-kontrol w-full text-xs">
                                        </form>
                                    </template>
                                </td>
                                <td class="py-3 px-4 text-abu text-xs">
                                    <span class="px-2 py-1 rounded-sm bg-beton text-aspal font-mono">{{ $jenis->icon ?? 'buildings' }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($jenis->fasilitas_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-tag bg-biru text-putih font-bold text-xs">
                                            {{ $jenis->fasilitas_count }} sarana
                                        </span>
                                    @else
                                        <span class="text-xs text-abu">0 sarana</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <template x-if="!editing">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="editing = true" class="px-3 py-1.5 bg-putih border-2 border-aspal rounded-kontrol text-xs font-bold text-aspal hover:bg-beton cursor-pointer">
                                                Edit
                                            </button>

                                            <form method="POST" action="{{ route('admin.master.fasilitas.destroy', $jenis) }}" onsubmit="return confirm('Hapus jenis fasilitas \'{{ $jenis->nama }}\'?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" @if($jenis->fasilitas_count > 0) disabled title="Tidak dapat dihapus karena masih digunakan" @endif class="px-3 py-1.5 bg-putih border-2 border-merah rounded-kontrol text-xs font-bold text-merah hover:bg-merah hover:text-putih cursor-pointer disabled:opacity-40 disabled:hover:bg-putih disabled:hover:text-merah disabled:cursor-not-allowed">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </template>
                                    <template x-if="editing">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="submit" form="edit-fasilitas-form-{{ $jenis->id }}" class="px-3 py-1.5 bg-aspal text-putih rounded-kontrol text-xs font-bold hover:bg-black cursor-pointer">
                                                Simpan
                                            </button>
                                            <button type="button" @click="editing = false" class="px-3 py-1.5 bg-putih border-2 border-aspal text-aspal rounded-kontrol text-xs font-bold hover:bg-beton cursor-pointer">
                                                Batal
                                            </button>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-abu">
                                    Belum ada jenis fasilitas. Tambahkan jenis fasilitas pertama di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jenisList->hasPages())
                <div class="p-4 border-t border-beton">
                    {{ $jenisList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
