<x-app-layout>
    <x-slot:title>Data Master Kategori Event</x-slot:title>
    <x-slot:header>Data Master: Kategori Event Budaya</x-slot:header>

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

        <!-- Papan Kuning (DESIGN.md 7.11: Event Budaya Berwarna Kuning) -->
        <x-papan warna="kuning" class="p-6">
            <h2 class="text-2xl font-bold font-papan text-aspal">Kategori Event Budaya</h2>
            <p class="text-aspal/80 text-sm mt-1">
                Klasifikasi kegiatan dan agenda kebudayaan (misalnya: Upacara Adat, Pagelaran Seni, Festival Musik Tradisional).
            </p>
        </x-papan>

        <!-- Form Tambah Kategori Event -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-6">
            <h3 class="text-lg font-bold font-papan text-aspal mb-4">Tambah kategori event baru</h3>
            <form method="POST" action="{{ route('admin.master.event.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <x-input-label for="nama" value="Nama kategori event" />
                    <x-text-input id="nama" name="nama" type="text" :value="old('nama')" required placeholder="Contoh: Upacara Adat & Tradisi" />
                    <x-input-error :messages="$errors->get('nama')" />
                </div>
                <div>
                    <x-input-label for="deskripsi" value="Keterangan singkat (opsional)" />
                    <x-text-input id="deskripsi" name="deskripsi" type="text" :value="old('deskripsi')" placeholder="Deskripsi ringkas kategori" />
                    <x-input-error :messages="$errors->get('deskripsi')" />
                </div>
                <div>
                    <x-primary-button class="w-full">
                        + Tambah Kategori
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Tabel Data Master Event -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between">
                <h3 class="font-bold font-papan text-aspal text-lg">Daftar kategori event</h3>
                <span class="text-xs text-abu">{{ $kategoriList->total() }} kategori terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Nama Kategori</th>
                            <th class="py-3 px-4">Slug URL</th>
                            <th class="py-3 px-4 text-center">Event Terkait</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-sm text-aspal">
                        @forelse($kategoriList as $index => $kategori)
                            <tr class="h-[60px] hover:bg-beton/40" x-data="{ editing: false }">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $kategoriList->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4 font-semibold">
                                    <template x-if="!editing">
                                        <div>
                                            <div class="text-[15px] font-bold text-aspal">{{ $kategori->nama }}</div>
                                            @if($kategori->deskripsi)
                                                <div class="text-xs text-abu">{{ $kategori->deskripsi }}</div>
                                            @endif
                                        </div>
                                    </template>
                                    <template x-if="editing">
                                        <form id="edit-event-form-{{ $kategori->id }}" method="POST" action="{{ route('admin.master.event.update', $kategori) }}" class="space-y-2 py-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nama" value="{{ $kategori->nama }}" required class="h-[40px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm">
                                            <input type="text" name="deskripsi" value="{{ $kategori->deskripsi }}" placeholder="Keterangan (opsional)" class="h-[36px] px-3 border border-abu rounded-kontrol w-full text-xs">
                                        </form>
                                    </template>
                                </td>
                                <td class="py-3 px-4 text-abu font-mono text-xs">
                                    {{ $kategori->slug }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($kategori->event_budaya_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-tag bg-kuning text-aspal font-bold text-xs">
                                            {{ $kategori->event_budaya_count }} event
                                        </span>
                                    @else
                                        <span class="text-xs text-abu">0 event</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <template x-if="!editing">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="editing = true" class="px-3 py-1.5 bg-putih border-2 border-aspal rounded-kontrol text-xs font-bold text-aspal hover:bg-beton cursor-pointer">
                                                Edit
                                            </button>

                                            <form method="POST" action="{{ route('admin.master.event.destroy', $kategori) }}" onsubmit="return confirm('Hapus kategori \'{{ $kategori->nama }}\'?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" @if($kategori->event_budaya_count > 0) disabled title="Tidak dapat dihapus karena masih digunakan" @endif class="px-3 py-1.5 bg-putih border-2 border-merah rounded-kontrol text-xs font-bold text-merah hover:bg-merah hover:text-putih cursor-pointer disabled:opacity-40 disabled:hover:bg-putih disabled:hover:text-merah disabled:cursor-not-allowed">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </template>
                                    <template x-if="editing">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="submit" form="edit-event-form-{{ $kategori->id }}" class="px-3 py-1.5 bg-aspal text-putih rounded-kontrol text-xs font-bold hover:bg-black cursor-pointer">
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
                                    Belum ada kategori event budaya. Tambahkan kategori pertama di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kategoriList->hasPages())
                <div class="p-4 border-t border-beton">
                    {{ $kategoriList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
