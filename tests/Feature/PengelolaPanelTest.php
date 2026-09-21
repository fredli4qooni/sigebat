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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengelolaPanelTest extends TestCase
{
    use RefreshDatabase;

    private User $pengelola;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pengelola = User::factory()->pengelola()->create([
            'email' => 'pengelola@gedungbatin.desa.id',
        ]);
    }

    public function test_pengelola_dashboard_can_be_rendered_for_authenticated_pengelola(): void
    {
        $response = $this->actingAs($this->pengelola)->get(route('pengelola.dashboard'));
        $response->assertOk();
        $response->assertSee('Ruang pengelolaan data objek wisata');
        $response->assertSee('Ringkasan konten desa');
    }

    public function test_guest_is_redirected_from_pengelola_dashboard(): void
    {
        $response = $this->get(route('pengelola.dashboard'));
        $response->assertRedirect('/login');
    }

    public function test_admin_is_forbidden_from_pengelola_dashboard(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('pengelola.dashboard'));
        $response->assertForbidden();
    }

    public function test_pengelola_can_view_wisata_list_with_search_and_filter(): void
    {
        $kategori1 = KategoriWisata::factory()->create(['nama' => 'Budaya']);
        $kategori2 = KategoriWisata::factory()->create(['nama' => 'Alam']);

        $wisata1 = ObjekWisata::factory()->create([
            'nama' => 'Rumah Tradisional 1',
            'kategori_wisata_id' => $kategori1->id,
            'created_by' => $this->pengelola->id,
        ]);
        $wisata2 = ObjekWisata::factory()->create([
            'nama' => 'Air Terjun Indah',
            'kategori_wisata_id' => $kategori2->id,
            'created_by' => $this->pengelola->id,
        ]);

        $response = $this->actingAs($this->pengelola)->get(route('pengelola.wisata.index', [
            'q' => 'Tradisional',
        ]));

        $response->assertOk();
        $response->assertSee('Rumah Tradisional 1');
        $response->assertDontSee('Air Terjun Indah');
    }

    public function test_pengelola_can_create_new_wisata_with_coordinates_and_upload(): void
    {
        Storage::fake('public');
        $kategori = KategoriWisata::factory()->create();

        $photo = UploadedFile::fake()->image('wisata.jpg', 800, 600);

        $response = $this->actingAs($this->pengelola)->post(route('pengelola.wisata.store'), [
            'nama' => 'Situs Sesat Agung',
            'kategori_wisata_id' => $kategori->id,
            'latitude' => -4.541234,
            'longitude' => 104.665678,
            'alamat' => 'Kampung Gedung Batin, Way Kanan',
            'jam_operasional' => '08.00 - 16.00 WIB',
            'harga_tiket' => 'Gratis',
            'kontak' => '08123456789',
            'deskripsi' => 'Pusat musyawarah adat pepadun yang bersejarah.',
            'foto_utama' => $photo,
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('pengelola.wisata.index'));

        $this->assertDatabaseHas('objek_wisata', [
            'nama' => 'Situs Sesat Agung',
            'kategori_wisata_id' => $kategori->id,
            'status' => 'aktif',
            'created_by' => $this->pengelola->id,
        ]);

        $wisata = ObjekWisata::where('nama', 'Situs Sesat Agung')->first();
        $this->assertNotNull($wisata->foto_utama);
        Storage::disk('public')->assertExists($wisata->foto_utama);

        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'CREATE_WISATA',
            'user_id' => $this->pengelola->id,
            'entitas_id' => $wisata->id,
        ]);
    }

    public function test_wisata_creation_fails_with_invalid_coordinates(): void
    {
        $kategori = KategoriWisata::factory()->create();

        $response = $this->actingAs($this->pengelola)->post(route('pengelola.wisata.store'), [
            'nama' => 'Wisata Salah Koordinat',
            'kategori_wisata_id' => $kategori->id,
            'latitude' => 120.0, // Invalid latitude (> 90)
            'longitude' => 200.0, // Invalid longitude (> 180)
            'alamat' => 'Alamat test',
            'deskripsi' => 'Deskripsi test',
            'status' => 'aktif',
        ]);

        $response->assertSessionHasErrors(['latitude', 'longitude']);
        $this->assertDatabaseMissing('objek_wisata', ['nama' => 'Wisata Salah Koordinat']);
    }

    public function test_pengelola_can_update_wisata(): void
    {
        $wisata = ObjekWisata::factory()->create(['nama' => 'Nama Awal', 'created_by' => $this->pengelola->id]);

        $response = $this->actingAs($this->pengelola)->put(route('pengelola.wisata.update', $wisata), [
            'nama' => 'Nama Terkoreksi',
            'kategori_wisata_id' => $wisata->kategori_wisata_id,
            'latitude' => -4.540111,
            'longitude' => 104.664222,
            'alamat' => $wisata->alamat,
            'jam_operasional' => '09.00 - 18.00 WIB',
            'harga_tiket' => 'Rp 5.000',
            'kontak' => $wisata->kontak,
            'deskripsi' => 'Deskripsi baru yang lebih mendalam.',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('pengelola.wisata.index'));
        $this->assertDatabaseHas('objek_wisata', [
            'id' => $wisata->id,
            'nama' => 'Nama Terkoreksi',
            'harga_tiket' => 'Rp 5.000',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'UPDATE_WISATA',
            'user_id' => $this->pengelola->id,
            'entitas_id' => $wisata->id,
        ]);
    }

    public function test_pengelola_can_soft_delete_wisata(): void
    {
        $wisata = ObjekWisata::factory()->create(['created_by' => $this->pengelola->id]);

        $response = $this->actingAs($this->pengelola)->delete(route('pengelola.wisata.destroy', $wisata));
        $response->assertRedirect(route('pengelola.wisata.index'));

        $this->assertSoftDeleted('objek_wisata', ['id' => $wisata->id]);
        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'DELETE_WISATA',
            'user_id' => $this->pengelola->id,
            'entitas_id' => $wisata->id,
        ]);
    }

    public function test_pengelola_can_create_and_manage_fasilitas_umum_and_khusus(): void
    {
        $jenis = JenisFasilitas::factory()->create(['nama' => 'Musala']);
        $wisata = ObjekWisata::factory()->create(['created_by' => $this->pengelola->id]);

        // 1. Create Fasilitas Umum (desa)
        $responseUmum = $this->actingAs($this->pengelola)->post(route('pengelola.fasilitas.store'), [
            'nama' => 'Musala Al-Hidayah Kampung',
            'jenis_fasilitas_id' => $jenis->id,
            'objek_wisata_id' => null,
            'keterangan_lokasi' => 'Pusat Balai Desa',
            'deskripsi' => 'Dapat menampung 50 jemaah',
        ]);
        $responseUmum->assertRedirect(route('pengelola.fasilitas.index'));

        $this->assertDatabaseHas('fasilitas', [
            'nama' => 'Musala Al-Hidayah Kampung',
            'objek_wisata_id' => null,
        ]);

        // 2. Create Fasilitas Khusus (linked to wisata)
        $responseKhusus = $this->actingAs($this->pengelola)->post(route('pengelola.fasilitas.store'), [
            'nama' => 'Toilet Khusus Pengunjung Rumah Panggung',
            'jenis_fasilitas_id' => $jenis->id,
            'objek_wisata_id' => $wisata->id,
            'keterangan_lokasi' => 'Belakang rumah panggung',
        ]);
        $responseKhusus->assertRedirect(route('pengelola.fasilitas.index'));

        $this->assertDatabaseHas('fasilitas', [
            'nama' => 'Toilet Khusus Pengunjung Rumah Panggung',
            'objek_wisata_id' => $wisata->id,
        ]);

        $fasilitasKhusus = Fasilitas::where('nama', 'Toilet Khusus Pengunjung Rumah Panggung')->first();

        // 3. Update
        $responseUpdate = $this->actingAs($this->pengelola)->put(route('pengelola.fasilitas.update', $fasilitasKhusus), [
            'nama' => 'Toilet & Ruang Wudu',
            'jenis_fasilitas_id' => $jenis->id,
            'objek_wisata_id' => $wisata->id,
            'keterangan_lokasi' => 'Belakang rumah panggung nomor 2',
        ]);
        $responseUpdate->assertRedirect(route('pengelola.fasilitas.index'));
        $this->assertDatabaseHas('fasilitas', [
            'id' => $fasilitasKhusus->id,
            'nama' => 'Toilet & Ruang Wudu',
        ]);

        // 4. Soft Delete
        $responseDelete = $this->actingAs($this->pengelola)->delete(route('pengelola.fasilitas.destroy', $fasilitasKhusus));
        $responseDelete->assertRedirect(route('pengelola.fasilitas.index'));
        $this->assertSoftDeleted('fasilitas', ['id' => $fasilitasKhusus->id]);
    }

    public function test_pengelola_can_create_and_manage_event_budaya(): void
    {
        $kategori = KategoriEvent::factory()->create(['nama' => 'Upacara Adat']);

        // 1. Validation error if tanggal_selesai is before tanggal_mulai
        $invalidResponse = $this->actingAs($this->pengelola)->post(route('pengelola.event.store'), [
            'judul' => 'Event Salah Tanggal',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => '2026-10-15',
            'tanggal_selesai' => '2026-10-10', // Invalid!
            'lokasi' => 'Balai Adat',
            'deskripsi' => 'Test event',
            'status' => 'aktif',
        ]);
        $invalidResponse->assertSessionHasErrors('tanggal_selesai');

        // 2. Successful store with valid multi-day
        $response = $this->actingAs($this->pengelola)->post(route('pengelola.event.store'), [
            'judul' => 'Festival Budaya Pepadun',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => '2026-11-01',
            'tanggal_selesai' => '2026-11-03',
            'jam_mulai' => '08:00',
            'jam_selesai' => '17:00',
            'lokasi' => 'Lapangan Kampung Gedung Batin',
            'deskripsi' => 'Pementasan seni tari cangget agung dan pertunjukan musik cetik.',
            'status' => 'aktif',
        ]);
        $response->assertRedirect(route('pengelola.event.index'));

        $this->assertDatabaseHas('event_budaya', [
            'judul' => 'Festival Budaya Pepadun',
            'kategori_event_id' => $kategori->id,
            'status' => 'aktif',
            'created_by' => $this->pengelola->id,
        ]);

        $event = EventBudaya::where('judul', 'Festival Budaya Pepadun')->first();
        $this->assertTrue($event->is_multi_hari);

        // 3. Update
        $responseUpdate = $this->actingAs($this->pengelola)->put(route('pengelola.event.update', $event), [
            'judul' => 'Festival Budaya Pepadun Raya',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => '2026-11-01',
            'tanggal_selesai' => '2026-11-04',
            'jam_mulai' => '08:00',
            'jam_selesai' => '18:00',
            'lokasi' => 'Lapangan Utama Kampung Gedung Batin',
            'deskripsi' => 'Pementasan seni tari cangget agung dan pertunjukan musik cetik diperpanjang 1 hari.',
            'status' => 'aktif',
        ]);
        $responseUpdate->assertRedirect(route('pengelola.event.index'));
        $this->assertDatabaseHas('event_budaya', [
            'id' => $event->id,
            'judul' => 'Festival Budaya Pepadun Raya',
        ]);

        // 4. Soft Delete
        $responseDelete = $this->actingAs($this->pengelola)->delete(route('pengelola.event.destroy', $event));
        $responseDelete->assertRedirect(route('pengelola.event.index'));
        $this->assertSoftDeleted('event_budaya', ['id' => $event->id]);
    }

    public function test_event_budaya_status_turunan_filtering(): void
    {
        $kategori = KategoriEvent::factory()->create();

        // 1. Akan Datang (start next month)
        $eventAkanDatang = EventBudaya::factory()->create([
            'judul' => 'Event Masa Depan',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => now('Asia/Jakarta')->addDays(10)->format('Y-m-d'),
            'tanggal_selesai' => now('Asia/Jakarta')->addDays(12)->format('Y-m-d'),
            'created_by' => $this->pengelola->id,
        ]);

        // 2. Berlangsung (start yesterday, end tomorrow)
        $eventBerlangsung = EventBudaya::factory()->create([
            'judul' => 'Event Hari Ini',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => now('Asia/Jakarta')->subDay()->format('Y-m-d'),
            'tanggal_selesai' => now('Asia/Jakarta')->addDay()->format('Y-m-d'),
            'created_by' => $this->pengelola->id,
        ]);

        // 3. Selesai (last week)
        $eventSelesai = EventBudaya::factory()->create([
            'judul' => 'Event Masa Lalu',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => now('Asia/Jakarta')->subDays(10)->format('Y-m-d'),
            'tanggal_selesai' => now('Asia/Jakarta')->subDays(8)->format('Y-m-d'),
            'created_by' => $this->pengelola->id,
        ]);

        // Test filter: akan_datang
        $response = $this->actingAs($this->pengelola)->get(route('pengelola.event.index', ['status_turunan' => 'akan_datang']));
        $response->assertOk();
        $response->assertSee('Event Masa Depan');
        $response->assertDontSee('Event Hari Ini');
        $response->assertDontSee('Event Masa Lalu');

        // Test filter: berlangsung
        $responseBerlangsung = $this->actingAs($this->pengelola)->get(route('pengelola.event.index', ['status_turunan' => 'berlangsung']));
        $responseBerlangsung->assertOk();
        $responseBerlangsung->assertSee('Event Hari Ini');
        $responseBerlangsung->assertDontSee('Event Masa Depan');
        $responseBerlangsung->assertDontSee('Event Masa Lalu');

        // Test filter: selesai
        $responseSelesai = $this->actingAs($this->pengelola)->get(route('pengelola.event.index', ['status_turunan' => 'selesai']));
        $responseSelesai->assertOk();
        $responseSelesai->assertSee('Event Masa Lalu');
        $responseSelesai->assertDontSee('Event Masa Depan');
        $responseSelesai->assertDontSee('Event Hari Ini');
    }
}
