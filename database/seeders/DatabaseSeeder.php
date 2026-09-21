<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\EventBudaya;
use App\Models\Fasilitas;
use App\Models\JenisFasilitas;
use App\Models\KategoriEvent;
use App\Models\KategoriWisata;
use App\Models\ObjekWisata;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna
        $admin = User::firstOrCreate(
            ['email' => 'admin@sigebat.desa.id'],
            [
                'name' => 'Administrator SIGEBAT',
                'phone' => '081273000001',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );

        $pengelolaAktif = User::firstOrCreate(
            ['email' => 'pengelola@sigebat.desa.id'],
            [
                'name' => "O'os Syawal (Pengelola)",
                'phone' => '081273000002',
                'password' => Hash::make('password'),
                'role' => 'pengelola',
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );

        $pengelolaPending = User::firstOrCreate(
            ['email' => 'budi@sigebat.desa.id'],
            [
                'name' => 'Budi Hartono (Calon Pengelola)',
                'phone' => '081273000003',
                'password' => Hash::make('password'),
                'role' => 'pengelola',
                'status' => 'pending',
                'email_verified_at' => null,
            ]
        );

        // 2. Data Master: Kategori Wisata
        $katWisataBudaya = KategoriWisata::firstOrCreate(
            ['nama' => 'Wisata Budaya & Adat'],
            ['slug' => 'wisata-budaya-adat', 'deskripsi' => 'Rumah adat tradisional, balai musyawarah, dan warisan kebudayaan pepadun.']
        );

        $katWisataAlam = KategoriWisata::firstOrCreate(
            ['nama' => 'Wisata Alam & Sungai'],
            ['slug' => 'wisata-alam-sungai', 'deskripsi' => 'Pemandangan alam sungai Way Besai, persawahan asri, dan kebun hijau.']
        );

        $katWisataSejarah = KategoriWisata::firstOrCreate(
            ['nama' => 'Wisata Sejarah & Religi'],
            ['slug' => 'wisata-sejarah-religi', 'deskripsi' => 'Situs keramat tetua pendiri kampung dan jejak peninggalan masa lalu.']
        );

        $katWisataKerajinan = KategoriWisata::firstOrCreate(
            ['nama' => 'Agrowisata & Kerajinan'],
            ['slug' => 'agrowisata-kerajinan', 'deskripsi' => 'Sentra pembuatan kain tenun tapis, anyaman bambu, dan pertanian desa.']
        );

        // 3. Data Master: Kategori Event
        $katEventUpacara = KategoriEvent::firstOrCreate(
            ['nama' => 'Upacara Adat & Tradisi'],
            ['slug' => 'upacara-adat-tradisi', 'deskripsi' => 'Begawi adat, beguai jejama, dan ritual pelestarian nilai budaya leluhur.']
        );

        $katEventFestival = KategoriEvent::firstOrCreate(
            ['nama' => 'Festival & Pagelaran Budaya'],
            ['slug' => 'festival-pagelaran-budaya', 'deskripsi' => 'Festival tahunan kampung, karnaval pakaian adat, dan pekan kebudayaan.']
        );

        $katEventSeni = KategoriEvent::firstOrCreate(
            ['nama' => 'Pentas Seni Musik & Tari'],
            ['slug' => 'pentas-seni-musik-tari', 'deskripsi' => 'Pementasan tari cangget, tari melinting, dan petikan klasik gitar Lampung.']
        );

        $katEventPameran = KategoriEvent::firstOrCreate(
            ['nama' => 'Pameran & Pasar Kampung'],
            ['slug' => 'pameran-pasar-kampung', 'deskripsi' => 'Pameran kerajinan tapis, pasar kuliner seruit, dan cinderamata khas desa.']
        );

        // 4. Data Master: Jenis Fasilitas
        $jenisBalai = JenisFasilitas::firstOrCreate(['nama' => 'Pusat Informasi & Balai'], ['icon' => 'buildings']);
        $jenisIbadah = JenisFasilitas::firstOrCreate(['nama' => 'Musala / Tempat Ibadah'], ['icon' => 'mosque']);
        $jenisToilet = JenisFasilitas::firstOrCreate(['nama' => 'Toilet & Kamar Basuh'], ['icon' => 'toilet']);
        $jenisParkir = JenisFasilitas::firstOrCreate(['nama' => 'Area Parkir Kendaraan'], ['icon' => 'car']);
        $jenisWarung = JenisFasilitas::firstOrCreate(['nama' => 'Warung Makan & Minum'], ['icon' => 'coffee']);
        $jenisGazebo = JenisFasilitas::firstOrCreate(['nama' => 'Spot Foto & Gazebo'], ['icon' => 'camera']);

        // 5. Objek Wisata Kampung Gedung Batin
        $wisata1 = ObjekWisata::firstOrCreate(
            ['slug' => 'rumah-adat-sesat-agung-gedung-batin'],
            [
                'kategori_wisata_id' => $katWisataBudaya->id,
                'nama' => 'Rumah Adat Sesat Agung Gedung Batin',
                'deskripsi' => 'Rumah panggung kayu tradisional khas masyarakat adat Pepadun Way Kanan yang telah berusia ratusan tahun. Berkonstruksi kayu ulin kokoh dengan ukiran ornamen khas Lampung yang sarat filosofi persaudaraan dan musyawarah mufakat.',
                'alamat' => 'Jl. Lintas Adat No. 1, Kampung Gedung Batin, Kec. Umpu Semenguk, Way Kanan',
                'latitude' => -4.5085120,
                'longitude' => 104.5241890,
                'jam_operasional' => '08.00 - 17.00 WIB',
                'harga_tiket' => 'Gratis / Donasi Sukarela',
                'kontak' => '0812-7301-1122',
                'foto_utama' => null,
                'status' => 'aktif',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        $wisata2 = ObjekWisata::firstOrCreate(
            ['slug' => 'rumah-panggung-berukir-buay-pemuka-pengiran-tuha'],
            [
                'kategori_wisata_id' => $katWisataBudaya->id,
                'nama' => 'Rumah Panggung Berukir Buay Pemuka Pengiran Tuha',
                'deskripsi' => 'Rumah tua peninggalan tetua adat yang menyimpan pusaka keluarga pepadun, kain tapis antik sulam benang emas, dan ornamen ukiran tiang kayu jati kuno peninggalan abad ke-19.',
                'alamat' => 'Dusun 2, Kampung Gedung Batin, Kec. Umpu Semenguk, Way Kanan',
                'latitude' => -4.5098200,
                'longitude' => 104.5226400,
                'jam_operasional' => '08.30 - 16.30 WIB',
                'harga_tiket' => 'Rp 5.000 / orang',
                'kontak' => '0813-7902-3344',
                'foto_utama' => null,
                'status' => 'aktif',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        $wisata3 = ObjekWisata::firstOrCreate(
            ['slug' => 'tepian-sungai-way-besai-gedung-batin'],
            [
                'kategori_wisata_id' => $katWisataAlam->id,
                'nama' => 'Tepian Sungai Way Besai Gedung Batin',
                'deskripsi' => 'Pemandangan asri aliran Sungai Way Besai yang jernih dengan rimbun pepohonan tropis dan bebatuan alami. Lokasi favorit untuk memancing, menyewa perahu tradisional, serta menikmati semilir angin tepi sungai.',
                'alamat' => 'Bantaran Sungai Way Besai, Kampung Gedung Batin, Way Kanan',
                'latitude' => -4.5061400,
                'longitude' => 104.5273500,
                'jam_operasional' => '07.00 - 18.00 WIB',
                'harga_tiket' => 'Gratis',
                'kontak' => '0852-6789-5566',
                'foto_utama' => null,
                'status' => 'aktif',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        $wisata4 = ObjekWisata::firstOrCreate(
            ['slug' => 'situs-makam-tetua-adat-gedung-batin'],
            [
                'kategori_wisata_id' => $katWisataSejarah->id,
                'nama' => 'Situs Makam Tetua Adat Gedung Batin',
                'deskripsi' => 'Situs sejarah makam para perintis awal kampung Gedung Batin yang dihormati. Tempat bernuansa hening dengan pepohonan rindang berusia ratusan tahun yang kerap diziarahi pemerhati sejarah daerah.',
                'alamat' => 'Kawasan Hutan Lindung Desa, Kampung Gedung Batin, Way Kanan',
                'latitude' => -4.5124000,
                'longitude' => 104.5198000,
                'jam_operasional' => '08.00 - 16.00 WIB',
                'harga_tiket' => 'Sukarela',
                'kontak' => '0812-7301-1122',
                'foto_utama' => null,
                'status' => 'aktif',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        $wisata5 = ObjekWisata::firstOrCreate(
            ['slug' => 'sentra-kerajinan-tapis-dan-anyaman-gedung-batin'],
            [
                'kategori_wisata_id' => $katWisataKerajinan->id,
                'nama' => 'Sentra Kerajinan Tapis dan Anyaman Gedung Batin',
                'deskripsi' => 'Pusat bengkel karya kerajinan tradisional tempat wisatawan dapat melihat proses pembuatan kain tapis Lampung secara langsung dan mencoba menenun bersama pengrajin perempuan desa.',
                'alamat' => 'Dusun 1, Kampung Gedung Batin, Way Kanan',
                'latitude' => -4.5072000,
                'longitude' => 104.5255000,
                'jam_operasional' => '09.00 - 16.00 WIB',
                'harga_tiket' => 'Gratis',
                'kontak' => '0821-8899-7711',
                'foto_utama' => null,
                'status' => 'aktif',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        // Objek Wisata Status Pending (untuk uji filter pengelola/admin)
        ObjekWisata::firstOrCreate(
            ['slug' => 'taman-pematang-kuning-gedung-batin'],
            [
                'kategori_wisata_id' => $katWisataAlam->id,
                'nama' => 'Taman Pematang Kuning Gedung Batin',
                'deskripsi' => 'Rencana pengembangan agrowisata perkebunan buah dan bunga di pematang desa.',
                'alamat' => 'Dusun 3, Kampung Gedung Batin, Way Kanan',
                'latitude' => -4.5150000,
                'longitude' => 104.5200000,
                'jam_operasional' => '08.00 - 17.00 WIB',
                'harga_tiket' => 'Gratis',
                'kontak' => '0821-0000-1111',
                'foto_utama' => null,
                'status' => 'pending',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        // 6. Fasilitas Pendukung (Terkait Wisata & Umum)
        Fasilitas::firstOrCreate(
            ['nama' => 'Area Parkir Sesat Agung'],
            [
                'jenis_fasilitas_id' => $jenisParkir->id,
                'objek_wisata_id' => $wisata1->id,
                'deskripsi' => 'Lahan parkir kendaraan roda 2 dan roda 4 yang teduh di depan gerbang Sesat Agung.',
                'keterangan_lokasi' => 'Sebelah timur Rumah Adat Sesat Agung',
                'foto' => null,
                'created_by' => $pengelolaAktif->id,
            ]
        );

        Fasilitas::firstOrCreate(
            ['nama' => 'Musala An-Nur Gedung Batin'],
            [
                'jenis_fasilitas_id' => $jenisIbadah->id,
                'objek_wisata_id' => $wisata1->id,
                'deskripsi' => 'Tempat salat bersih berpendingin kipas angin dengan tempat wudu nyaman.',
                'keterangan_lokasi' => 'Samping kiri balai adat',
                'foto' => null,
                'created_by' => $pengelolaAktif->id,
            ]
        );

        Fasilitas::firstOrCreate(
            ['nama' => 'Toilet Umum Wisatawan'],
            [
                'jenis_fasilitas_id' => $jenisToilet->id,
                'objek_wisata_id' => $wisata1->id,
                'deskripsi' => 'Toilet bersih terpisah pria dan wanita dengan pasokan air sumur jernih.',
                'keterangan_lokasi' => 'Belakang musala balai adat',
                'foto' => null,
                'created_by' => $pengelolaAktif->id,
            ]
        );

        Fasilitas::firstOrCreate(
            ['nama' => 'Gazebo Bambu Tepian Way Besai'],
            [
                'jenis_fasilitas_id' => $jenisGazebo->id,
                'objek_wisata_id' => $wisata3->id,
                'deskripsi' => 'Gazebo bambu tempat istirahat sambil menikmati panorama aliran sungai.',
                'keterangan_lokasi' => 'Bantaran sungai Way Besai',
                'foto' => null,
                'created_by' => $pengelolaAktif->id,
            ]
        );

        Fasilitas::firstOrCreate(
            ['nama' => 'Warung Seruit Mak Etek'],
            [
                'jenis_fasilitas_id' => $jenisWarung->id,
                'objek_wisata_id' => $wisata3->id,
                'deskripsi' => 'Menyajikan hidangan seruit ikan sungai khas Lampung, lalapan segar, dan sambal terasi.',
                'keterangan_lokasi' => 'Dekat dermaga perahu Way Besai',
                'foto' => null,
                'created_by' => $pengelolaAktif->id,
            ]
        );

        // Fasilitas Umum Desa (objek_wisata_id = null)
        Fasilitas::firstOrCreate(
            ['nama' => 'Balai Pertemuan & Pusat Informasi Kampung'],
            [
                'jenis_fasilitas_id' => $jenisBalai->id,
                'objek_wisata_id' => null,
                'deskripsi' => 'Pusat pelayanan administrasi dan pusat informasi pemandu desa wisata Kampung Gedung Batin.',
                'keterangan_lokasi' => 'Jl. Utama Kampung Gedung Batin No. 10',
                'foto' => null,
                'created_by' => $pengelolaAktif->id,
            ]
        );

        Fasilitas::firstOrCreate(
            ['nama' => 'Pos Penjagaan & Gerbang Selamat Datang'],
            [
                'jenis_fasilitas_id' => $jenisBalai->id,
                'objek_wisata_id' => null,
                'deskripsi' => 'Pos pemantauan keamanan dan papan peta selamat datang di mulut kampung.',
                'keterangan_lokasi' => 'Pertigaan jalan masuk Kampung Gedung Batin',
                'foto' => null,
                'created_by' => $pengelolaAktif->id,
            ]
        );

        // 7. Event Budaya (Akan datang, Berlangsung, Selesai)
        $today = Carbon::now('Asia/Jakarta');

        EventBudaya::firstOrCreate(
            ['slug' => 'begawi-adat-cangget-agung-gedung-batin'],
            [
                'kategori_event_id' => $katEventUpacara->id,
                'judul' => 'Begawi Adat Cangget Agung Gedung Batin',
                'deskripsi' => 'Upacara agung penganugerahan gelar adat (adok) masyarakat Pepadun dengan iringan musik tala balak, tari cangget mepadun semalam suntuk, dan jamuan adat seruit bersama para tetua kampung se-Kabupaten Way Kanan.',
                'tanggal_mulai' => $today->copy()->addDays(3)->toDateString(),
                'tanggal_selesai' => $today->copy()->addDays(5)->toDateString(),
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '22:00:00',
                'lokasi' => 'Balai Adat Sesat Agung Kampung Gedung Batin',
                'poster' => null,
                'status' => 'aktif',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        EventBudaya::firstOrCreate(
            ['slug' => 'pameran-kerajinan-tapis-dan-pasar-kuliner-kampung'],
            [
                'kategori_event_id' => $katEventPameran->id,
                'judul' => 'Pameran Kerajinan Tapis dan Pasar Kuliner Kampung',
                'deskripsi' => 'Gelar produk lokal hasil tenun ibu-ibu desa Gedung Batin serta festival kuliner tradisional mencicipi seruit ikan baung sungai Way Besai dan kue-kue tradisional khas Lampung.',
                'tanggal_mulai' => $today->copy()->toDateString(),
                'tanggal_selesai' => $today->copy()->addDay()->toDateString(),
                'jam_mulai' => '08:30:00',
                'jam_selesai' => '17:00:00',
                'lokasi' => 'Halaman Balai Pertemuan Kampung Gedung Batin',
                'poster' => null,
                'status' => 'aktif',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        EventBudaya::firstOrCreate(
            ['slug' => 'malam-pentas-seni-gitar-klasik-lampung'],
            [
                'kategori_event_id' => $katEventSeni->id,
                'judul' => 'Malam Pentas Seni Gitar Klasik Lampung',
                'deskripsi' => 'Pagelaran malam seni petikan gitar klasik Lampung melantunkan bait-bait sastra tutur, pantun adat (wadih), dan tarian sambutan tradisional di tepi sungai.',
                'tanggal_mulai' => $today->copy()->subDays(14)->toDateString(),
                'tanggal_selesai' => $today->copy()->subDays(14)->toDateString(),
                'jam_mulai' => '19:30:00',
                'jam_selesai' => '23:00:00',
                'lokasi' => 'Panggung Budaya Tepian Way Besai',
                'poster' => null,
                'status' => 'aktif',
                'created_by' => $pengelolaAktif->id,
            ]
        );

        // 8. Catat Activity Log Awal
        ActivityLog::firstOrCreate(
            ['aksi' => 'SYSTEM_INIT'],
            [
                'user_id' => $admin->id,
                'entitas_tipe' => 'SYSTEM',
                'entitas_id' => 1,
                'keterangan' => ['pesan' => 'Inisialisasi sistem, basis data, dan seeder data awal Kampung Gedung Batin.'],
                'ip_address' => '127.0.0.1',
                'created_at' => now(),
            ]
        );
    }
}
