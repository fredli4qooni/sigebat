<x-app-layout>
    <x-slot:title>Kelola Fasilitas Desa</x-slot:title>
    <x-slot:header>Data Fasilitas Desa Wisata</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Ruang Kerja Pengelola &bull; Sarana & Prasarana</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Kelola Fasilitas Desa
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Manajemen data sarana pendukung kenyamanan wisatawan, baik fasilitas umum desa maupun fasilitas khusus di objek wisata.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a
                    href="{{ route('pengelola.fasilitas.create') }}"
                    class="h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-xs sm:text-sm shadow-xs hover:shadow-emerald-600/20 transition-all inline-flex items-center gap-2 no-underline cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Fasilitas</span>
                </a>
            </div>
        </div>

        <!-- 2. Bar Filter & Pencarian Modern -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm">
            <form method="GET" action="{{ route('pengelola.fasilitas.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                <!-- Input Pencarian -->
                <div class="lg:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama atau keterangan lokasi fasilitas..."
                        class="h-11 pl-10 pr-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                    >
                </div>

                <!-- Dropdown Jenis -->
                <div class="lg:col-span-3">
                    <select
                        name="jenis_id"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                        <option value="">Semua Jenis Fasilitas</option>
                        @foreach($jenisList as $j)
                            <option value="{{ $j->id }}" {{ request('jenis_id') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Lingkup -->
                <div class="lg:col-span-2">
                    <select
                        name="lingkup"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                        <option value="">Semua Lingkup</option>
                        <option value="umum" {{ request('lingkup') === 'umum' ? 'selected' : '' }}>Fasilitas Umum</option>
                        <option value="khusus" {{ request('lingkup') === 'khusus' ? 'selected' : '' }}>Objek Wisata</option>
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

                    @if(request()->hasAny(['q', 'jenis_id', 'lingkup']))
                        <a
                            href="{{ route('pengelola.fasilitas.index') }}"
                            class="h-11 px-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition-all flex items-center justify-center no-underline"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 3. Tabel Data Fasilitas -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="font-bold tracking-tight text-slate-900 text-base">Daftar Fasilitas Desa</h3>
                    <p class="text-xs text-slate-500">Sarana pendukung kenyamanan umum dan fasilitas objek wisata</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $fasilitasList->total() }} Fasilitas Terdaftar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4 w-20">Foto</th>
                            <th class="py-3.5 px-4">Nama Fasilitas</th>
                            <th class="py-3.5 px-4">Jenis</th>
                            <th class="py-3.5 px-4">Lingkup / Objek Wisata</th>
                            <th class="py-3.5 px-4">Lokasi & Keterangan</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse($fasilitasList as $index => $f)
                            <tr class="h-[72px] hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400 text-xs">
                                    {{ $fasilitasList->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($f->foto)
                                        <img src="{{ $f->foto_url }}" alt="{{ $f->nama }}" class="w-14 h-11 object-cover rounded-xl border border-slate-200 shadow-2xs">
                                    @else
                                        <div class="w-14 h-11 rounded-xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            <span class="text-[9px] font-medium leading-none">Tanpa foto</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $f->nama }}</div>
                                    @if($f->deskripsi)
                                        <div class="text-xs text-slate-500 line-clamp-1 mt-1">{{ $f->deskripsi }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 border border-sky-200 text-xs font-semibold">
                                        {{ $f->jenis?->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    @if($f->is_umum)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                                            Umum (Desa)
                                        </span>
                                    @else
                                        <div class="font-bold text-slate-900">{{ $f->objekWisata?->nama ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">Fasilitas Khusus Wisata</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-700">
                                    <div class="font-medium text-slate-800">{{ $f->keterangan_lokasi }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('pengelola.fasilitas.edit', $f) }}"
                                            class="h-8 px-3 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold text-xs rounded-xl transition-all shadow-xs inline-flex items-center gap-1 no-underline"
                                        >
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Edit</span>
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
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="text-sm font-bold text-slate-700">Belum ada fasilitas yang ditemukan</div>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                        Sesuaikan filter pencarian atau klik tombol "Tambah Fasilitas" di atas untuk menambahkan sarana baru.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($fasilitasList->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                    {{ $fasilitasList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
