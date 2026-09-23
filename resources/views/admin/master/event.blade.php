<x-app-layout>
    <x-slot:title>Data Master Kategori Event</x-slot:title>
    <x-slot:header>Data Master: Kategori Event Budaya</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Tab Navigasi Data Master -->
        @include('admin.master.partials.tabs')

        <!-- 2. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span>Agenda & Kalender Budaya</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Kategori Event Budaya
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Kelola klasifikasi kegiatan dan agenda kebudayaan Kampung Gedung Batin seperti Upacara Adat Begawi, Pagelaran Tari Cangget, dan Festival Tradisional.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-semibold">
                    {{ $kategoriList->total() }} Kategori Terdaftar
                </span>
            </div>
        </div>

        <!-- 3. Form Tambah Kategori Event Baru -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold tracking-tight text-slate-900">Tambah Kategori Event Baru</h3>
                    <p class="text-xs text-slate-500">Daftarkan jenis klasifikasi baru untuk agenda kebudayaan desa</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.master.event.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Kategori Event
                    </label>
                    <input
                        id="nama"
                        name="nama"
                        type="text"
                        value="{{ old('nama') }}"
                        required
                        placeholder="Contoh: Upacara Adat & Tradisi"
                        class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                    >
                    <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                </div>
                <div>
                    <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Keterangan Singkat (Opsional)
                    </label>
                    <input
                        id="deskripsi"
                        name="deskripsi"
                        type="text"
                        value="{{ old('deskripsi') }}"
                        placeholder="Deskripsi ringkas kategori"
                        class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                    >
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-1" />
                </div>
                <div>
                    <button
                        type="submit"
                        class="h-11 px-5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm shadow-xs transition-all cursor-pointer inline-flex items-center justify-center gap-2 w-full"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Kategori</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- 4. Tabel Data Master Kategori Event -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="font-bold tracking-tight text-slate-900 text-base">Daftar Kategori Event Budaya</h3>
                    <p class="text-xs text-slate-500">Kelola dan perbarui nama atau keterangan kategori event desa</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $kategoriList->total() }} Kategori Terdaftar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Nama Kategori</th>
                            <th class="py-3.5 px-4">Slug URL</th>
                            <th class="py-3.5 px-4 text-center">Event Terkait</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse($kategoriList as $index => $kategori)
                            <tr class="h-[64px] hover:bg-slate-50/60 transition-colors" x-data="{ editing: false }">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400 text-xs">
                                    {{ $kategoriList->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold">
                                    <template x-if="!editing">
                                        <div>
                                            <div class="text-[14px] font-bold text-slate-900">{{ $kategori->nama }}</div>
                                            @if($kategori->deskripsi)
                                                <div class="text-xs text-slate-500 font-normal mt-0.5">{{ $kategori->deskripsi }}</div>
                                            @endif
                                        </div>
                                    </template>
                                    <template x-if="editing">
                                        <form id="edit-event-form-{{ $kategori->id }}" method="POST" action="{{ route('admin.master.event.update', $kategori) }}" class="space-y-2 py-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nama" value="{{ $kategori->nama }}" required class="h-10 px-3 border border-slate-300 rounded-xl w-full text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                                            <input type="text" name="deskripsi" value="{{ $kategori->deskripsi }}" placeholder="Keterangan (opsional)" class="h-9 px-3 border border-slate-300 rounded-xl w-full text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                                        </form>
                                    </template>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                        {{ $kategori->slug }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($kategori->event_budaya_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-100/80 text-amber-900 border border-amber-300/80 text-xs font-bold">
                                            {{ $kategori->event_budaya_count }} event
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">0 event</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <template x-if="!editing">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="button"
                                                @click="editing = true"
                                                class="px-3 py-1.5 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-700 transition-all cursor-pointer inline-flex items-center gap-1.5 shadow-2xs"
                                            >
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            <form method="POST" action="{{ route('admin.master.event.destroy', $kategori) }}" onsubmit="return confirm('Hapus kategori \'{{ $kategori->nama }}\'?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    @if($kategori->event_budaya_count > 0) disabled title="Tidak dapat dihapus karena masih digunakan oleh data event budaya" @endif
                                                    class="px-3 py-1.5 bg-rose-50/70 border border-rose-200 hover:bg-rose-100 hover:border-rose-300 rounded-xl text-xs font-semibold text-rose-700 transition-all cursor-pointer inline-flex items-center gap-1.5 disabled:opacity-40 disabled:hover:bg-rose-50/70 disabled:hover:border-rose-200 disabled:cursor-not-allowed shadow-2xs"
                                                >
                                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </template>
                                    <template x-if="editing">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="submit"
                                                form="edit-event-form-{{ $kategori->id }}"
                                                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold shadow-xs transition-all cursor-pointer"
                                            >
                                                Simpan
                                            </button>
                                            <button
                                                type="button"
                                                @click="editing = false"
                                                class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition-all cursor-pointer"
                                            >
                                                Batal
                                            </button>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 space-y-2">
                                    <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                    </svg>
                                    <p class="text-xs font-medium text-slate-500">Belum ada kategori event terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kategoriList->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                    {{ $kategoriList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
