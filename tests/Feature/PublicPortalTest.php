<?php

namespace Tests\Feature;

use App\Models\EventBudaya;
use App\Models\Fasilitas;
use App\Models\JenisFasilitas;
use App\Models\KategoriEvent;
use App\Models\KategoriWisata;
use App\Models\ObjekWisata;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_rendered_with_destinations_and_events(): void
    {
        $user = User::factory()->pengelola()->create();
        $kategoriWisata = KategoriWisata::factory()->create(['nama' => 'Budaya Khas']);
        $kategoriEvent = KategoriEvent::factory()->create(['nama' => 'Pentas Adat']);

        $wisata = ObjekWisata::factory()->create([
            'nama' => 'Rumah Panggung Bersejarah',
            'kategori_wisata_id' => $kategoriWisata->id,
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $event = EventBudaya::factory()->create([
            'judul' => 'Pagelaran Tari Cangget Agung',
            'kategori_event_id' => $kategoriEvent->id,
            'tanggal_mulai' => now('Asia/Jakarta')->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => now('Asia/Jakarta')->addDays(6)->format('Y-m-d'),
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Kampung Gedung Batin');
        $response->assertSee('Rumah Panggung Bersejarah');
        $response->assertSee('Pagelaran Tari Cangget Agung');
    }

    public function test_wisata_index_page_can_be_rendered_and_filtered(): void
    {
        $user = User::factory()->pengelola()->create();
        $kategori1 = KategoriWisata::factory()->create(['nama' => 'Cagar Budaya', 'slug' => 'cagar-budaya']);
        $kategori2 = KategoriWisata::factory()->create(['nama' => 'Wisata Alam', 'slug' => 'wisata-alam']);

        $wisata1 = ObjekWisata::factory()->create([
            'nama' => 'Sesat Agung Gedung Batin',
            'kategori_wisata_id' => $kategori1->id,
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $wisata2 = ObjekWisata::factory()->create([
            'nama' => 'Tepian Sungai Way Besai',
            'kategori_wisata_id' => $kategori2->id,
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        // 1. All Active Wisata
        $responseAll = $this->get(route('wisata.index'));
        $responseAll->assertOk();
        $responseAll->assertSee('Sesat Agung Gedung Batin');
        $responseAll->assertSee('Tepian Sungai Way Besai');

        // 2. Search by keyword
        $responseSearch = $this->get(route('wisata.index', ['q' => 'Sungai']));
        $responseSearch->assertOk();
        $responseSearch->assertSee('Tepian Sungai Way Besai');
        $responseSearch->assertDontSee('Sesat Agung Gedung Batin');

        // 3. Filter by category slug
        $responseCategory = $this->get(route('wisata.index', ['kategori' => 'cagar-budaya']));
        $responseCategory->assertOk();
        $responseCategory->assertSee('Sesat Agung Gedung Batin');
        $responseCategory->assertDontSee('Tepian Sungai Way Besai');
    }

    public function test_wisata_detail_page_can_be_rendered_with_facilities(): void
    {
        $user = User::factory()->pengelola()->create();
        $kategori = KategoriWisata::factory()->create(['nama' => 'Situs Adat']);
        $jenis = JenisFasilitas::factory()->create(['nama' => 'Musala']);

        $wisata = ObjekWisata::factory()->create([
            'nama' => 'Rumah Adat Kepala Marga',
            'slug' => 'rumah-adat-kepala-marga',
            'kategori_wisata_id' => $kategori->id,
            'alamat' => 'Jalan Utama Kampung Gedung Batin No. 1',
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $fasilitas = Fasilitas::factory()->create([
            'nama' => 'Musala Khusus Tamu Adat',
            'jenis_fasilitas_id' => $jenis->id,
            'objek_wisata_id' => $wisata->id,
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('wisata.show', 'rumah-adat-kepala-marga'));

        $response->assertOk();
        $response->assertSee('Rumah Adat Kepala Marga');
        $response->assertSee('Musala Khusus Tamu Adat');
        $response->assertSee('Buka Rute di Google Maps');
    }

    public function test_wisata_detail_returns_404_if_not_found_or_inactive(): void
    {
        $user = User::factory()->pengelola()->create();

        $inactiveWisata = ObjekWisata::factory()->create([
            'nama' => 'Wisata Rahasia Belum Siap',
            'slug' => 'wisata-rahasia',
            'status' => 'pending',
            'created_by' => $user->id,
        ]);

        $responseNotFound = $this->get(route('wisata.show', 'tidak-ada-slug'));
        $responseNotFound->assertNotFound();

        $responseInactive = $this->get(route('wisata.show', 'wisata-rahasia'));
        $responseInactive->assertNotFound();
    }

    public function test_interactive_peta_page_can_be_rendered(): void
    {
        $user = User::factory()->pengelola()->create();
        $wisata = ObjekWisata::factory()->create([
            'nama' => 'Pusaka Panggung Way Kanan',
            'status' => 'aktif',
            'latitude' => -4.540123,
            'longitude' => 104.665123,
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('peta.index'));

        $response->assertOk();
        $response->assertSee('Peta Sebaran Wisata');
        $response->assertSee('Pusaka Panggung Way Kanan');
        $response->assertSee('-4.540123');
        $response->assertSee('104.665123');
    }

    public function test_fasilitas_directory_page_can_be_rendered_with_filters(): void
    {
        $user = User::factory()->pengelola()->create();
        $jenis = JenisFasilitas::factory()->create(['nama' => 'Toilet Umum']);
        $wisata = ObjekWisata::factory()->create(['created_by' => $user->id]);

        $fasilitasUmum = Fasilitas::factory()->create([
            'nama' => 'Toilet Balai Kampung',
            'jenis_fasilitas_id' => $jenis->id,
            'objek_wisata_id' => null,
            'created_by' => $user->id,
        ]);

        $fasilitasWisata = Fasilitas::factory()->create([
            'nama' => 'Toilet Pengunjung Wisata',
            'jenis_fasilitas_id' => $jenis->id,
            'objek_wisata_id' => $wisata->id,
            'created_by' => $user->id,
        ]);

        // 1. All Facilities
        $response = $this->get(route('fasilitas.index'));
        $response->assertOk();
        $response->assertSee('Toilet Balai Kampung');
        $response->assertSee('Toilet Pengunjung Wisata');

        // 2. Filter Umum
        $responseUmum = $this->get(route('fasilitas.index', ['lingkup' => 'umum']));
        $responseUmum->assertOk();
        $responseUmum->assertSee('Toilet Balai Kampung');
        $responseUmum->assertDontSee('Toilet Pengunjung Wisata');

        // 3. Filter Khusus Wisata
        $responseKhusus = $this->get(route('fasilitas.index', ['lingkup' => 'khusus']));
        $responseKhusus->assertOk();
        $responseKhusus->assertSee('Toilet Pengunjung Wisata');
        $responseKhusus->assertDontSee('Toilet Balai Kampung');
    }

    public function test_api_wisata_returns_geojson_collection(): void
    {
        $user = User::factory()->pengelola()->create();
        $wisata = ObjekWisata::factory()->create([
            'nama' => 'Pohon Beringin Bersejarah',
            'latitude' => -4.541200,
            'longitude' => 104.665500,
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('api.wisata'));

        $response->assertOk();
        $response->assertJsonStructure([
            'type',
            'features' => [
                '*' => [
                    'type',
                    'geometry' => ['type', 'coordinates'],
                    'properties' => ['id', 'nama', 'slug', 'kategori', 'alamat'],
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'nama' => 'Pohon Beringin Bersejarah',
        ]);
    }
}
