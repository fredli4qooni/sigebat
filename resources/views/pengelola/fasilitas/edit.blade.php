<x-app-layout>
    <x-slot:title>Edit Fasilitas: {{ $fasilitas->nama }}</x-slot:title>
    <x-slot:header>Edit Data Fasilitas</x-slot:header>

    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Papan Biru Judul Halaman -->
        <x-papan warna="biru" class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-putih">Edit Fasilitas: {{ $fasilitas->nama }}</h2>
                    <p class="text-putih/90 text-sm mt-1">
                        Perbarui informasi lokasi, jenis, atau foto dokumentasi fasilitas pendukung.
                    </p>
                </div>
                <a
                    href="{{ route('pengelola.fasilitas.index') }}"
                    class="px-3.5 py-2 bg-putih/15 border border-putih/30 text-putih font-bold text-xs rounded-kontrol hover:bg-putih/25 no-underline"
                >
                    &larr; Kembali ke daftar
                </a>
            </div>
        </x-papan>

        <!-- Form Edit Fasilitas -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-6">
            <form
                method="POST"
                action="{{ route('pengelola.fasilitas.update', $fasilitas) }}"
                enctype="multipart/form-data"
                class="space-y-4"
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

                <div>
                    <x-input-label for="nama" value="Nama fasilitas" />
                    <x-text-input id="nama" name="nama" type="text" :value="old('nama', $fasilitas->nama)" required />
                    <x-input-error :messages="$errors->get('nama')" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="jenis_fasilitas_id" value="Jenis fasilitas" />
                        <select
                            id="jenis_fasilitas_id"
                            name="jenis_fasilitas_id"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" {{ old('jenis_fasilitas_id', $fasilitas->jenis_fasilitas_id) == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('jenis_fasilitas_id')" />
                    </div>

                    <div>
                        <x-input-label for="objek_wisata_id" value="Keterikatan objek wisata" />
                        <select
                            id="objek_wisata_id"
                            name="objek_wisata_id"
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            <option value="">-- Fasilitas Umum Desa (Tidak Terikat Objek) --</option>
                            @foreach($wisataList as $w)
                                <option value="{{ $w->id }}" {{ old('objek_wisata_id', $fasilitas->objek_wisata_id) == $w->id ? 'selected' : '' }}>
                                    {{ $w->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('objek_wisata_id')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="keterangan_lokasi" value="Keterangan penempatan / lokasi" />
                    <x-text-input id="keterangan_lokasi" name="keterangan_lokasi" type="text" :value="old('keterangan_lokasi', $fasilitas->keterangan_lokasi)" required />
                    <x-input-error :messages="$errors->get('keterangan_lokasi')" />
                </div>

                <div>
                    <x-input-label for="deskripsi" value="Deskripsi / keterangan kondisi" />
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="3"
                        class="p-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                    >{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" />
                </div>

                <div>
                    <x-input-label for="foto" value="Ubah foto fasilitas (Biarkan kosong jika tidak diganti)" />
                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        @change="handleFile"
                        class="block w-full text-sm text-aspal file:mr-4 file:py-2.5 file:px-4 file:rounded-kontrol file:border-2 file:border-aspal file:text-xs file:font-bold file:bg-beton file:text-aspal hover:file:bg-abu/20 cursor-pointer"
                    >
                    <x-input-error :messages="$errors->get('foto')" />

                    <!-- Foto Saat Ini / Pratinjau Foto Baru -->
                    <div class="mt-3">
                        <template x-if="previewUrl">
                            <div class="p-2 bg-beton/40 border border-aspal rounded-kontrol">
                                <span class="block text-[11px] font-bold text-abu mb-1">Pratinjau foto baru:</span>
                                <img :src="previewUrl" alt="Pratinjau Baru" class="w-full h-40 object-cover rounded-kontrol border border-aspal">
                            </div>
                        </template>
                        <template x-if="!previewUrl && {{ $fasilitas->foto ? 'true' : 'false' }}">
                            <div class="p-2 bg-beton/40 border border-beton rounded-kontrol">
                                <span class="block text-[11px] font-bold text-abu mb-1">Foto fasilitas saat ini:</span>
                                <img src="{{ $fasilitas->foto_url }}" alt="{{ $fasilitas->nama }}" class="w-full h-40 object-cover rounded-kontrol border border-aspal">
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-beton">
                    <a
                        href="{{ route('pengelola.fasilitas.index') }}"
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
