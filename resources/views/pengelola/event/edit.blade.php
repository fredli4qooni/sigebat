<x-app-layout>
    <x-slot:title>Edit Event: {{ $event->judul }}</x-slot:title>
    <x-slot:header>Edit Agenda Kegiatan Budaya</x-slot:header>

    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Papan Kuning Judul Halaman -->
        <x-papan warna="kuning" class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-aspal">Edit Event: {{ $event->judul }}</h2>
                    <p class="text-aspal/80 text-sm mt-1">
                        Perbarui informasi tanggal, waktu pelaksanaan, deskripsi, atau poster promosi acara.
                    </p>
                </div>
                <a
                    href="{{ route('pengelola.event.index') }}"
                    class="px-3.5 py-2 bg-aspal text-putih font-bold text-xs rounded-kontrol hover:bg-black no-underline"
                >
                    &larr; Kembali ke agenda
                </a>
            </div>
        </x-papan>

        <!-- Form Edit Event -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-6">
            <form
                method="POST"
                action="{{ route('pengelola.event.update', $event) }}"
                enctype="multipart/form-data"
                class="space-y-4"
                x-data="{
                    previewUrl: null,
                    tglMulai: '{{ old('tanggal_mulai', $event->tanggal_mulai ? $event->tanggal_mulai->format('Y-m-d') : '') }}',
                    tglSelesai: '{{ old('tanggal_selesai', $event->tanggal_selesai ? $event->tanggal_selesai->format('Y-m-d') : '') }}',
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
                @method('PUT')

                <div>
                    <x-input-label for="judul" value="Judul event budaya" />
                    <x-text-input id="judul" name="judul" type="text" :value="old('judul', $event->judul)" required />
                    <x-input-error :messages="$errors->get('judul')" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="kategori_event_id" value="Kategori event" />
                        <select
                            id="kategori_event_id"
                            name="kategori_event_id"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_event_id', $event->kategori_event_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kategori_event_id')" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status publikasi" />
                        <select
                            id="status"
                            name="status"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            <option value="aktif" {{ old('status', $event->status) === 'aktif' ? 'selected' : '' }}>Aktif (Tampil di Kalender Digital)</option>
                            <option value="pending" {{ old('status', $event->status) === 'pending' ? 'selected' : '' }}>Pending (Draft)</option>
                            <option value="nonaktif" {{ old('status', $event->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Arsip)</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" />
                    </div>
                </div>

                <!-- Tanggal Mulai & Selesai -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="tanggal_mulai" value="Tanggal mulai" />
                        <input
                            type="date"
                            id="tanggal_mulai"
                            name="tanggal_mulai"
                            x-model="tglMulai"
                            @change="onMulaiChange"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                        <x-input-error :messages="$errors->get('tanggal_mulai')" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_selesai" value="Tanggal selesai" />
                        <input
                            type="date"
                            id="tanggal_selesai"
                            name="tanggal_selesai"
                            x-model="tglSelesai"
                            :min="tglMulai"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                        <x-input-error :messages="$errors->get('tanggal_selesai')" />
                    </div>
                </div>

                <!-- Jam Pelaksanaan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="jam_mulai" value="Jam mulai" />
                        <x-text-input id="jam_mulai" name="jam_mulai" type="text" :value="old('jam_mulai', substr($event->jam_mulai, 0, 5))" />
                        <x-input-error :messages="$errors->get('jam_mulai')" />
                    </div>

                    <div>
                        <x-input-label for="jam_selesai" value="Jam selesai" />
                        <x-text-input id="jam_selesai" name="jam_selesai" type="text" :value="old('jam_selesai', substr($event->jam_selesai, 0, 5))" />
                        <x-input-error :messages="$errors->get('jam_selesai')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="lokasi" value="Lokasi pelaksanaan" />
                    <x-text-input id="lokasi" name="lokasi" type="text" :value="old('lokasi', $event->lokasi)" required />
                    <x-input-error :messages="$errors->get('lokasi')" />
                </div>

                <div>
                    <x-input-label for="deskripsi" value="Deskripsi lengkap dan rangkaian acara" />
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        required
                        class="p-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                    >{{ old('deskripsi', $event->deskripsi) }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" />
                </div>

                <div>
                    <x-input-label for="poster" value="Ubah poster event (Biarkan kosong jika tidak diganti)" />
                    <input
                        type="file"
                        id="poster"
                        name="poster"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        @change="handleFile"
                        class="block w-full text-sm text-aspal file:mr-4 file:py-2.5 file:px-4 file:rounded-kontrol file:border-2 file:border-aspal file:text-xs file:font-bold file:bg-beton file:text-aspal hover:file:bg-abu/20 cursor-pointer"
                    >
                    <x-input-error :messages="$errors->get('poster')" />

                    <!-- Poster Saat Ini / Pratinjau Poster Baru -->
                    <div class="mt-3">
                        <template x-if="previewUrl">
                            <div class="p-2 bg-beton/40 border border-aspal rounded-kontrol">
                                <span class="block text-[11px] font-bold text-abu mb-1">Pratinjau poster baru:</span>
                                <img :src="previewUrl" alt="Pratinjau Baru" class="w-48 h-64 object-cover rounded-kontrol border border-aspal">
                            </div>
                        </template>
                        <template x-if="!previewUrl && {{ $event->poster ? 'true' : 'false' }}">
                            <div class="p-2 bg-beton/40 border border-beton rounded-kontrol">
                                <span class="block text-[11px] font-bold text-abu mb-1">Poster aktif saat ini:</span>
                                <img src="{{ $event->poster_url }}" alt="{{ $event->judul }}" class="w-48 h-64 object-cover rounded-kontrol border border-aspal">
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-beton">
                    <a
                        href="{{ route('pengelola.event.index') }}"
                        class="h-[44px] px-5 bg-putih border-2 border-aspal text-aspal font-bold text-sm rounded-kontrol hover:bg-beton no-underline flex items-center justify-center"
                    >
                        Batal
                    </a>
                    <x-primary-button>
                        Simpan Perubahan
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
