<x-app-layout>
    <x-slot:title>Tambah Fasilitas Desa</x-slot:title>
    <x-slot:header>Tambah Fasilitas Baru</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Formulir Fasilitas Baru</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Tambah Fasilitas Baru
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Daftarkan sarana pendukung umum desa atau fasilitas khusus di sekitar kawasan objek wisata.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a
                    href="{{ route('pengelola.fasilitas.index') }}"
                    class="h-10 px-4 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-semibold text-xs sm:text-sm transition-all inline-flex items-center gap-2 no-underline"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>

        <!-- 2. Formulir Tambah Fasilitas -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-sm">
            <form
                method="POST"
                action="{{ route('pengelola.fasilitas.store') }}"
                enctype="multipart/form-data"
                class="space-y-5"
                x-data="{
                    previewUrl: null,
                    handleFile(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                        }
                    }
                }"
            >
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Fasilitas <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="{{ old('nama') }}"
                            required
                            placeholder="Contoh: Toilet Umum & Ruang Bilas"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                        >
                        <x-input-error :messages="$errors->get('nama')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="jenis_fasilitas_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jenis Fasilitas <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="jenis_fasilitas_id"
                            name="jenis_fasilitas_id"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                        >
                            <option value="">Pilih jenis fasilitas...</option>
                            @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" {{ old('jenis_fasilitas_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('jenis_fasilitas_id')" class="mt-1.5" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="objek_wisata_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Keterikatan Objek Wisata
                        </label>
                        <select
                            id="objek_wisata_id"
                            name="objek_wisata_id"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                        >
                            <option value="">-- Fasilitas Umum Desa (Tidak Terikat Objek) --</option>
                            @foreach($wisataList as $w)
                                <option value="{{ $w->id }}" {{ old('objek_wisata_id') == $w->id ? 'selected' : '' }}>
                                    {{ $w->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('objek_wisata_id')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="keterangan_lokasi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Keterangan Penempatan / Lokasi <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="keterangan_lokasi"
                            name="keterangan_lokasi"
                            value="{{ old('keterangan_lokasi') }}"
                            required
                            placeholder="Contoh: Samping Balai Adat / Gerbang Masuk Kampung"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                        >
                        <x-input-error :messages="$errors->get('keterangan_lokasi')" class="mt-1.5" />
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Deskripsi / Keterangan Kondisi (Opsional)
                    </label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="3"
                        placeholder="Contoh: Kondisi bersih, 2 bilik toilet, sumber air mengalir lancar 24 jam..."
                        class="p-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400 leading-relaxed"
                    >{{ old('deskripsi') }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-1.5" />
                </div>

                <div>
                    <label for="foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Foto Fasilitas (Opsional, Maksimal 3 MB)
                    </label>
                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        @change="handleFile"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer"
                    >
                    <x-input-error :messages="$errors->get('foto')" class="mt-1.5" />

                    <!-- Pratinjau Foto -->
                    <template x-if="previewUrl">
                        <div class="mt-3 p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5 inline-block">
                            <span class="block text-[11px] font-semibold text-slate-500">Pratinjau foto terpilih:</span>
                            <img :src="previewUrl" alt="Pratinjau" class="w-72 h-48 object-cover rounded-xl border border-slate-200 shadow-2xs">
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a
                        href="{{ route('pengelola.fasilitas.index') }}"
                        class="h-11 px-6 bg-white border border-slate-200 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl hover:bg-slate-50 transition-all no-underline flex items-center justify-center cursor-pointer shadow-xs"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="h-11 px-7 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-emerald-600/20 transition-all cursor-pointer flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Fasilitas</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
