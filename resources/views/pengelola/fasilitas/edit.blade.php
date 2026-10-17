<x-app-layout>
    <x-slot:title>Edit Fasilitas: {{ $fasilitas->nama }}</x-slot:title>
    <x-slot:header>Edit Data Fasilitas</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Penyuntingan Sarana & Prasarana</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Edit Fasilitas: {{ $fasilitas->nama }}
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Perbarui informasi lokasi, jenis, status keterikatan, atau foto dokumentasi sarana pendukung.
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

        <!-- 2. Formulir Edit Fasilitas -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-sm">
            <form
                method="POST"
                action="{{ route('pengelola.fasilitas.update', $fasilitas) }}"
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
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Fasilitas <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="{{ old('nama', $fasilitas->nama) }}"
                            required
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
                            @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" {{ old('jenis_fasilitas_id', $fasilitas->jenis_fasilitas_id) == $j->id ? 'selected' : '' }}>
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
                                <option value="{{ $w->id }}" {{ old('objek_wisata_id', $fasilitas->objek_wisata_id) == $w->id ? 'selected' : '' }}>
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
                            value="{{ old('keterangan_lokasi', $fasilitas->keterangan_lokasi) }}"
                            required
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
                        class="p-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400 leading-relaxed"
                    >{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-1.5" />
                </div>

                <!-- Bagian Pengaturan Sewa & Reservasi Harian -->
                <div
                    x-data="{ bisaDisewa: {{ old('bisa_disewa', $fasilitas->bisa_disewa) ? 'true' : 'false' }} }"
                    class="p-5 sm:p-6 bg-slate-50/80 border border-slate-200 rounded-2xl space-y-4"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-900 flex items-center gap-2 flex-wrap">
                                <span>Penyewaan Fasilitas oleh Wisatawan</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">(Aktifkan toggle di samping untuk mengatur penyewaan)</span>
                            </label>
                            <p class="text-xs text-slate-500">
                                Aktifkan opsi ini jika fasilitas ini dapat dipesan dan disewa secara harian oleh wisatawan (terintegrasi rekening kas BUMDes).
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input
                                type="checkbox"
                                name="bisa_disewa"
                                value="1"
                                x-model="bisaDisewa"
                                class="sr-only peer"
                            >
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div x-show="bisaDisewa" x-cloak class="pt-4 border-t border-slate-200/80 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="harga_sewa_per_hari" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tarif Sewa Per Hari (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-3 text-xs font-bold text-slate-400">Rp</span>
                                <input
                                    type="number"
                                    id="harga_sewa_per_hari"
                                    name="harga_sewa_per_hari"
                                    value="{{ old('harga_sewa_per_hari', $fasilitas->harga_sewa_per_hari) }}"
                                    min="0"
                                    step="1000"
                                    placeholder="Contoh: 250000"
                                    class="h-11 pl-10 pr-3.5 border border-slate-300 rounded-xl w-full text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                                >
                            </div>
                            <x-input-error :messages="$errors->get('harga_sewa_per_hari')" class="mt-1.5" />
                        </div>

                        <div>
                            <label for="kapasitas" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Daya Tampung / Kapasitas (Orang)
                            </label>
                            <input
                                type="number"
                                id="kapasitas"
                                name="kapasitas"
                                value="{{ old('kapasitas', $fasilitas->kapasitas) }}"
                                min="1"
                                placeholder="Contoh: 6"
                                class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                            >
                            <x-input-error :messages="$errors->get('kapasitas')" class="mt-1.5" />
                        </div>

                        <div class="md:col-span-2">
                            <label for="fasilitas_include" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Fasilitas yang Didapatkan (Include)
                            </label>
                            <input
                                type="text"
                                id="fasilitas_include"
                                name="fasilitas_include"
                                value="{{ old('fasilitas_include', $fasilitas->fasilitas_include) }}"
                                placeholder="Contoh: Kasur queen, Kamar mandi dalam, Kipas angin, Sarapan 2 porsi, Listrik"
                                class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                            >
                            <x-input-error :messages="$errors->get('fasilitas_include')" class="mt-1.5" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200/60">
                        <label for="status_ketersediaan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Status Ketersediaan Operasional
                        </label>
                        <select
                            id="status_ketersediaan"
                            name="status_ketersediaan"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                            <option value="tersedia" {{ old('status_ketersediaan', $fasilitas->status_ketersediaan ?? 'tersedia') === 'tersedia' ? 'selected' : '' }}>Siap Digunakan / Disewa (Tersedia)</option>
                            <option value="perbaikan" {{ old('status_ketersediaan', $fasilitas->status_ketersediaan) === 'perbaikan' ? 'selected' : '' }}>Dalam Perbaikan / Renovasi</option>
                            <option value="nonaktif" {{ old('status_ketersediaan', $fasilitas->status_ketersediaan) === 'nonaktif' ? 'selected' : '' }}>Nonaktif / Tidak Dibuka</option>
                        </select>
                        <x-input-error :messages="$errors->get('status_ketersediaan')" class="mt-1.5" />
                    </div>
                </div>

                <div>
                    <label for="foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Ganti Foto Fasilitas (Opsional, Maksimal 3 MB)
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

                    <!-- Foto Saat Ini vs Foto Baru -->
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-2xl">
                        @if($fasilitas->foto)
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                                <span class="block text-[11px] font-semibold text-slate-500">Foto Saat Ini:</span>
                                <img src="{{ $fasilitas->foto_url }}" alt="{{ $fasilitas->nama }}" class="w-full h-44 object-cover rounded-xl border border-slate-200 shadow-2xs">
                            </div>
                        @endif

                        <template x-if="previewUrl">
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                                <span class="block text-[11px] font-semibold text-emerald-700">Foto Pengganti Terpilih:</span>
                                <img :src="previewUrl" alt="Pratinjau" class="w-full h-44 object-cover rounded-xl border border-emerald-300 shadow-2xs">
                            </div>
                        </template>
                    </div>
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
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
