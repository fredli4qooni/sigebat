<x-app-layout>
    <x-slot:title>Jadwalkan Event Budaya</x-slot:title>
    <x-slot:header>Jadwalkan Kegiatan Budaya Baru</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Formulir Event Budaya Baru</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Jadwalkan Event Budaya
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Masukkan agenda ritual adat, pertunjukan kesenian tradisional, atau festival kebudayaan Kampung Gedung Batin.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a
                    href="{{ route('pengelola.event.index') }}"
                    class="h-10 px-4 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-semibold text-xs sm:text-sm transition-all inline-flex items-center gap-2 no-underline"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Agenda</span>
                </a>
            </div>
        </div>

        <!-- 2. Formulir Jadwalkan Event -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-sm">
            <form
                method="POST"
                action="{{ route('pengelola.event.store') }}"
                enctype="multipart/form-data"
                class="space-y-5"
                x-data="{
                    previewUrl: null,
                    tglMulai: '{{ old('tanggal_mulai', date('Y-m-d')) }}',
                    tglSelesai: '{{ old('tanggal_selesai', date('Y-m-d')) }}',
                    handleFile(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                        }
                    },
                    onMulaiChange() {
                        if (this.tglSelesai < this.tglMulai) {
                            this.tglSelesai = this.tglMulai;
                        }
                    }
                }"
            >
                @csrf

                <!-- Baris 1: Judul Event & Kategori -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Judul Event Budaya <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            value="{{ old('judul') }}"
                            required
                            placeholder="Contoh: Upacara Adat Begawi Pepadun Kampung Gedung Batin"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                        >
                        <x-input-error :messages="$errors->get('judul')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="kategori_event_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kategori Event <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="kategori_event_id"
                            name="kategori_event_id"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                        >
                            <option value="">Pilih kategori event...</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_event_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kategori_event_id')" class="mt-1.5" />
                    </div>
                </div>

                <!-- Baris 2: Lokasi & Status Publikasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Lokasi Pelaksanaan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="lokasi"
                            name="lokasi"
                            value="{{ old('lokasi', 'Balai Adat Sesat Agung Kampung Gedung Batin') }}"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                        >
                        <x-input-error :messages="$errors->get('lokasi')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Status Publikasi <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                        >
                            <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif (Tampil di Kalender Digital)</option>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending (Draft)</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Arsip)</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1.5" />
                    </div>
                </div>

                <!-- Baris 3: Tanggal & Jam Pelaksanaan (4 Kolom pada Desktop) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label for="tanggal_mulai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tanggal Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="tanggal_mulai"
                            name="tanggal_mulai"
                            x-model="tglMulai"
                            @change="onMulaiChange"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                        >
                        <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tanggal Selesai <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="tanggal_selesai"
                            name="tanggal_selesai"
                            x-model="tglSelesai"
                            :min="tglMulai"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                        >
                        <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="jam_mulai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jam Mulai (Opsional)
                        </label>
                        <input
                            type="text"
                            id="jam_mulai"
                            name="jam_mulai"
                            value="{{ old('jam_mulai', '09:00') }}"
                            placeholder="Contoh: 09:00"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400 font-mono"
                        >
                        <x-input-error :messages="$errors->get('jam_mulai')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="jam_selesai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jam Selesai (Opsional)
                        </label>
                        <input
                            type="text"
                            id="jam_selesai"
                            name="jam_selesai"
                            value="{{ old('jam_selesai', '17:00') }}"
                            placeholder="Contoh: 17:00 / Selesai"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400 font-mono"
                        >
                        <x-input-error :messages="$errors->get('jam_selesai')" class="mt-1.5" />
                    </div>
                </div>

                <!-- Baris 4: Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Deskripsi Lengkap & Rangkaian Acara <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        required
                        placeholder="Uraikan tata urutan acara, keunikan ritual, partisipan yang diundang, dan pesan budaya..."
                        class="p-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400 leading-relaxed"
                    >{{ old('deskripsi') }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-1.5" />
                </div>

                <!-- Baris 5: Poster -->
                <div>
                    <label for="poster" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Poster Event / Foto Dokumentasi (Opsional, Maksimal 3 MB)
                    </label>
                    <input
                        type="file"
                        id="poster"
                        name="poster"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        @change="handleFile"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer"
                    >
                    <x-input-error :messages="$errors->get('poster')" class="mt-1.5" />

                    <!-- Pratinjau Poster -->
                    <template x-if="previewUrl">
                        <div class="mt-3 p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5 inline-block">
                            <span class="block text-[11px] font-semibold text-slate-500">Pratinjau poster:</span>
                            <img :src="previewUrl" alt="Pratinjau" class="w-44 h-56 object-cover rounded-xl border border-slate-200 shadow-2xs">
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a
                        href="{{ route('pengelola.event.index') }}"
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
                        <span>Jadwalkan Event</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
