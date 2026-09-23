<x-app-layout>
    <x-slot:title>Data Master Jenis Fasilitas</x-slot:title>
    <x-slot:header>Data Master: Jenis Fasilitas</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Tab Navigasi Data Master -->
        @include('admin.master.partials.tabs')

        <!-- 2. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 border border-sky-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-sky-400"></span>
                    <span>Sarana & Prasarana Wisata</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Jenis Fasilitas Pendukung
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Kelola klasifikasi sarana dan prasarana penunjang kenyamanan pengunjung Kampung Gedung Batin seperti Musala, Toilet Umum, Area Parkir, dan Pos Pemandu.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-semibold">
                    {{ $jenisList->total() }} Jenis Terdaftar
                </span>
            </div>
        </div>

        <!-- 3. Form Tambah Jenis Fasilitas Baru -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold tracking-tight text-slate-900">Tambah Jenis Fasilitas Baru</h3>
                    <p class="text-xs text-slate-500">Daftarkan kategori sarana atau prasarana baru untuk penunjang wisatawan</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.master.fasilitas.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Jenis Fasilitas
                    </label>
                    <input
                        id="nama"
                        name="nama"
                        type="text"
                        value="{{ old('nama') }}"
                        required
                        placeholder="Contoh: Pusat Medis & P3K"
                        class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                    >
                    <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                </div>
                <div>
                    <label for="icon" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kode Ikon (Phosphor / Teks)
                    </label>
                    <input
                        id="icon"
                        name="icon"
                        type="text"
                        value="{{ old('icon', 'buildings') }}"
                        placeholder="Contoh: buildings, mosque, toilet"
                        class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                    >
                    <x-input-error :messages="$errors->get('icon')" class="mt-1" />
                </div>
                <div>
                    <button
                        type="submit"
                        class="h-11 px-5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm shadow-xs transition-all cursor-pointer inline-flex items-center justify-center gap-2 w-full"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Jenis</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- 4. Tabel Data Master Jenis Fasilitas -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="font-bold tracking-tight text-slate-900 text-base">Daftar Jenis Fasilitas</h3>
                    <p class="text-xs text-slate-500">Kelola dan perbarui nama atau kode ikon jenis fasilitas pendukung</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $jenisList->total() }} Jenis Terdaftar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Nama Jenis Fasilitas</th>
                            <th class="py-3.5 px-4">Ikon</th>
                            <th class="py-3.5 px-4 text-center">Fasilitas Terkait</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse($jenisList as $index => $jenis)
                            <tr class="h-[64px] hover:bg-slate-50/60 transition-colors" x-data="{ editing: false }">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400 text-xs">
                                    {{ $jenisList->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold">
                                    <template x-if="!editing">
                                        <div class="text-[14px] font-bold text-slate-900">{{ $jenis->nama }}</div>
                                    </template>
                                    <template x-if="editing">
                                        <form id="edit-fasilitas-form-{{ $jenis->id }}" method="POST" action="{{ route('admin.master.fasilitas.update', $jenis) }}" class="space-y-2 py-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nama" value="{{ $jenis->nama }}" required class="h-10 px-3 border border-slate-300 rounded-xl w-full text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                                            <input type="text" name="icon" value="{{ $jenis->icon }}" placeholder="Kode Ikon" class="h-9 px-3 border border-slate-300 rounded-xl w-full text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                                        </form>
                                    </template>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                        {{ $jenis->icon ?? 'buildings' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($jenis->fasilitas_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-sky-50 text-sky-800 border border-sky-200/80 text-xs font-bold">
                                            {{ $jenis->fasilitas_count }} sarana
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">0 sarana</span>
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

                                            <form method="POST" action="{{ route('admin.master.fasilitas.destroy', $jenis) }}" onsubmit="return confirm('Hapus jenis fasilitas \'{{ $jenis->nama }}\'?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    @if($jenis->fasilitas_count > 0) disabled title="Tidak dapat dihapus karena masih digunakan oleh data fasilitas" @endif
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
                                                form="edit-fasilitas-form-{{ $jenis->id }}"
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                    </svg>
                                    <p class="text-xs font-medium text-slate-500">Belum ada jenis fasilitas terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jenisList->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                    {{ $jenisList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
