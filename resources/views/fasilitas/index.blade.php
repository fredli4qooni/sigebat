<x-portal-layout>
    <x-slot:title>Direktori Fasilitas Desa — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Daftar fasilitas penunjang kenyamanan wisatawan di Desa Wisata Kampung Gedung Batin: musala, toilet umum, pos ronda, dan area parkir.</x-slot:description>

    <div class="py-8 md:py-12">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
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
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <a
                            href="{{ route('sewa.cek') }}"
                            class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-slate-800/90 hover:bg-slate-700/90 text-white font-semibold text-xs sm:text-sm border border-slate-700 transition-all no-underline whitespace-nowrap"
                        >
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Lacak Status Sewa</span>
                        </a>
                        <a
                            href="/peta"
                            class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-semibold text-xs sm:text-sm shadow-md transition-all no-underline whitespace-nowrap"
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
                    <div class="relative w-full sm:w-60">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari nama fasilitas..."
                            class="h-11 pl-9 pr-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <div class="w-44">
                        <select
                            name="jenis_id"
                            class="h-11 px-3 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                            <option value="">Semua Jenis</option>
                            @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" {{ request('jenis_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-44">
                        <select
                            name="sewa"
                            class="h-11 px-3 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                            <option value="">Semua Opsi Sewa</option>
                            <option value="disewa" {{ request('sewa') === 'disewa' ? 'selected' : '' }}>Bisa Disewa (Homestay/dll)</option>
                            <option value="gratis" {{ request('sewa') === 'gratis' ? 'selected' : '' }}>Fasilitas Umum Bebas Biaya</option>
                        </select>
                    </div>

                    <div class="w-40">
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

                        @if(request()->hasAny(['q', 'jenis_id', 'sewa', 'lingkup']))
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
                                <div class="h-44 bg-slate-100 overflow-hidden relative">
                                    <img src="{{ $f->foto_url }}" alt="{{ $f->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @if($f->bisa_disewa)
                                        <div class="absolute top-3 right-3 px-3 py-1 bg-emerald-600/90 backdrop-blur-xs text-white text-xs font-bold rounded-lg shadow-sm">
                                            {{ $f->harga_sewa_format }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="p-6 space-y-3">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <span class="px-2.5 py-0.5 bg-sky-50 text-sky-700 border border-sky-200 text-xs font-semibold rounded-md">
                                        {{ $f->jenis?->nama ?? 'Fasilitas' }}
                                    </span>

                                    @if($f->bisa_disewa)
                                        <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                            Bisa Disewa Harian
                                        </span>
                                    @elseif($f->is_umum)
                                        <span class="text-[11px] font-medium text-slate-700 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">
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

                                @if($f->bisa_disewa && ! $f->foto)
                                    <div class="text-sm font-bold text-emerald-700">
                                        {{ $f->harga_sewa_format }}
                                    </div>
                                @endif

                                <div class="text-xs text-slate-500 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="line-clamp-1">{{ $f->keterangan_lokasi }}</span>
                                </div>

                                @if($f->bisa_disewa && $f->kapasitas)
                                    <div class="text-xs text-slate-600 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>Daya tampung: <strong>{{ $f->kapasitas }} orang</strong></span>
                                    </div>
                                @endif

                                @if($f->fasilitas_include)
                                    <div class="text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-100 line-clamp-2">
                                        <span class="font-semibold text-slate-700">Termasuk:</span> {{ $f->fasilitas_include }}
                                    </div>
                                @endif

                                @if($f->deskripsi)
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed border-t border-slate-100 pt-2">
                                        {{ $f->deskripsi }}
                                    </p>
                                @endif

                                @if($f->objekWisata)
                                    <div class="pt-1 text-xs text-slate-600">
                                        Terhubung dengan: <a href="{{ route('wisata.show', $f->objekWisata->slug) }}" class="font-semibold text-emerald-700 hover:underline">{{ $f->objekWisata->nama }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Aksi -->
                        <div class="p-6 pt-0 space-y-2">
                            @if($f->bisa_disewa)
                                <div class="flex items-center gap-2">
                                    <a
                                        href="{{ route('fasilitas.sewa', $f) }}"
                                        class="flex-1 h-10 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs no-underline flex items-center justify-center gap-1.5 transition-all shadow-xs"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Sewa Sekarang</span>
                                    </a>
                                    <a
                                        href="/peta"
                                        class="h-10 w-10 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center transition-all flex-shrink-0"
                                        title="Lihat di Peta"
                                    >
                                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <a
                                    href="/peta"
                                    class="w-full h-10 px-4 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-700 font-semibold text-xs no-underline flex items-center justify-center gap-1.5 transition-all"
                                >
                                    <span>Lihat Posisi di Peta</span>
                                    <span>&rarr;</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200 p-8 space-y-3">
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
