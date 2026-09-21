<x-portal-layout>
    <x-slot:title>Direktori Fasilitas Desa — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Daftar fasilitas penunjang kenyamanan wisatawan di Desa Wisata Kampung Gedung Batin: musala, toilet umum, pos ronda, dan area parkir.</x-slot:description>

    <div class="py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Header Fasilitas Modern -->
            <div class="bg-slate-900 border border-slate-800 text-white rounded-2xl p-8 md:p-10 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-3 max-w-2xl">
                        <span class="inline-block px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 text-xs font-semibold">
                            Sarana & Prasarana Pendukung
                        </span>
                        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                            Fasilitas Pendukung Wisatawan
                        </h1>
                        <p class="text-slate-300 text-sm leading-relaxed font-normal">
                            Informasi ketersediaan musala, toilet umum, pos keamanan adat, dan fasilitas penunjang lainnya demi kenyamanan kunjungan wisatawan di Kampung Gedung Batin.
                        </p>
                    </div>
                    <div>
                        <a
                            href="/peta"
                            class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-semibold text-sm shadow-md transition-all no-underline whitespace-nowrap"
                        >
                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span>Lihat di Peta Desa</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bar Filter & Pencarian -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5">
                <form method="GET" action="{{ route('fasilitas.index') }}" class="flex flex-wrap items-center gap-3">
                    <div class="relative w-full sm:w-64">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari nama atau lokasi sarana..."
                            class="h-11 pl-9 pr-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <div class="w-48">
                        <select
                            name="jenis_id"
                            class="h-11 px-3 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                            <option value="">Semua Jenis Fasilitas</option>
                            @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" {{ request('jenis_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-48">
                        <select
                            name="lingkup"
                            class="h-11 px-3 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                            <option value="">Semua Lingkup</option>
                            <option value="umum" {{ request('lingkup') === 'umum' ? 'selected' : '' }}>Fasilitas Umum Desa</option>
                            <option value="khusus" {{ request('lingkup') === 'khusus' ? 'selected' : '' }}>Di Objek Wisata</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl transition-colors cursor-pointer shadow-xs">
                            Filter
                        </button>

                        @if(request()->hasAny(['q', 'jenis_id', 'lingkup']))
                            <a href="{{ route('fasilitas.index') }}" class="h-11 px-3.5 flex items-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl no-underline transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Grid Kartu Fasilitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @forelse($fasilitasList as $f)
                    <div class="card-modern overflow-hidden flex flex-col justify-between group">
                        <div>
                            <!-- Foto Fasilitas / Thumbnail -->
                            @if($f->foto)
                                <div class="h-44 bg-slate-100 overflow-hidden">
                                    <img src="{{ $f->foto_url }}" alt="{{ $f->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @endif

                            <div class="p-6 space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-0.5 bg-sky-50 text-sky-700 border border-sky-200 text-xs font-semibold rounded-md">
                                        {{ $f->jenis?->nama ?? 'Fasilitas' }}
                                    </span>

                                    @if($f->is_umum)
                                        <span class="text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                            Umum Desa
                                        </span>
                                    @else
                                        <span class="text-[11px] font-medium text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                            Di Objek Wisata
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug">
                                    {{ $f->nama }}
                                </h3>

                                <div class="text-xs text-slate-500 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="line-clamp-1">{{ $f->keterangan_lokasi }}</span>
                                </div>

                                @if($f->deskripsi)
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed border-t border-slate-100 pt-2">
                                        {{ $f->deskripsi }}
                                    </p>
                                @endif

                                @if($f->objekWisata)
                                    <div class="pt-2 text-xs text-slate-600">
                                        Terhubung dengan: <a href="{{ route('wisata.show', $f->objekWisata->slug) }}" class="font-semibold text-emerald-700 hover:underline">{{ $f->objekWisata->nama }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Aksi -->
                        <div class="p-6 pt-0">
                            <a
                                href="/peta"
                                class="w-full h-10 px-4 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-700 font-semibold text-xs no-underline flex items-center justify-center gap-1.5 transition-all"
                            >
                                <span>Lihat Posisi di Peta</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @empty
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="text-lg font-bold text-slate-800">Tidak ada fasilitas yang sesuai filter</div>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            Coba sesuaikan kata kunci pencarian atau jenis fasilitas untuk menemukan sarana pendukung lainnya.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Paginasi -->
            @if($fasilitasList->hasPages())
                <div class="pt-4 flex justify-center">
                    {{ $fasilitasList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-portal-layout>
