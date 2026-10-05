<?php

namespace Tests\Feature;

use App\Models\EventBudaya;
use App\Models\PendaftaranEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranEventTest extends TestCase
{
    use RefreshDatabase;

    private User $pengelola;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pengelola = User::factory()->pengelola()->create();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_pengunjung_dapat_melihat_form_pendaftaran_pada_event_aktif(): void
    {
        $event = EventBudaya::factory()->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'kuota_peserta' => 50,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->addDays(6)->format('Y-m-d'),
        ]);

        $response = $this->get(route('event.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Kehadiran Kegiatan');
        $response->assertSee('Pendaftaran Dibuka');
        $response->assertSee('50 Kuota');
        $response->assertSee('action="'.route('event.daftar', $event->slug).'"', false);
    }

    public function test_pengunjung_dapat_mendaftar_event_dan_mendapat_kode_registrasi(): void
    {
        $event = EventBudaya::factory()->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'kuota_peserta' => 30,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->addDays(3)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->addDays(4)->format('Y-m-d'),
        ]);

        $payload = [
            'nama_lengkap' => 'Ahmad Fauzi',
            'email' => 'ahmad@example.com',
            'nomor_telepon' => '081234567890',
            'asal_instansi' => 'Universitas Lampung',
            'jumlah_peserta' => 2,
            'catatan' => 'Rombongan mahasiswa studi budaya',
        ];

        $response = $this->post(route('event.daftar', $event->slug), $payload);

        $this->assertDatabaseHas('pendaftaran_event', [
            'event_budaya_id' => $event->id,
            'nama_lengkap' => 'Ahmad Fauzi',
            'email' => 'ahmad@example.com',
            'jumlah_peserta' => 2,
            'status' => 'terdaftar',
        ]);

        $pendaftaran = PendaftaranEvent::where('email', 'ahmad@example.com')->first();
        $this->assertNotNull($pendaftaran);
        $this->assertStringStartsWith('SGB-EVT-', $pendaftaran->kode_pendaftaran);

        $response->assertRedirect(route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran));
        $response->assertSessionHas('success');

        // Buka halaman bukti pendaftaran
        $buktiResponse = $this->get(route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran));
        $buktiResponse->assertStatus(200);
        $buktiResponse->assertSee($pendaftaran->kode_pendaftaran);
        $buktiResponse->assertSee('Ahmad Fauzi');
        $buktiResponse->assertSee('Tanda Bukti Pendaftaran');
    }

    public function test_validasi_pendaftaran_wajib_mengisi_bidang_esensial(): void
    {
        $event = EventBudaya::factory()->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->addDays(3)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->addDays(4)->format('Y-m-d'),
        ]);

        $response = $this->from(route('event.show', $event->slug))
            ->post(route('event.daftar', $event->slug), []);

        $response->assertSessionHasErrors([
            'nama_lengkap',
            'email',
            'nomor_telepon',
            'asal_instansi',
            'jumlah_peserta',
        ]);
        $this->assertEquals(0, PendaftaranEvent::count());
    }

    public function test_validasi_pendaftaran_ditolak_jika_melebihi_kuota(): void
    {
        $event = EventBudaya::factory()->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'kuota_peserta' => 5,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->addDays(3)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->addDays(4)->format('Y-m-d'),
        ]);

        $response = $this->from(route('event.show', $event->slug))
            ->post(route('event.daftar', $event->slug), [
                'nama_lengkap' => 'Rombongan Besar',
                'email' => 'rombongan@example.com',
                'nomor_telepon' => '081299998888',
                'asal_instansi' => 'Komunitas Budaya',
                'jumlah_peserta' => 10, // melebihi 5
            ]);

        $response->assertSessionHasErrors('jumlah_peserta');
        $this->assertEquals(0, PendaftaranEvent::count());
    }

    public function test_pendaftaran_ditutup_jika_kuota_penuh(): void
    {
        $event = EventBudaya::factory()->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'kuota_peserta' => 3,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->addDays(3)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->addDays(4)->format('Y-m-d'),
        ]);

        // Isi kuota hingga penuh (3 orang)
        PendaftaranEvent::factory()->create([
            'event_budaya_id' => $event->id,
            'jumlah_peserta' => 3,
            'status' => 'terdaftar',
        ]);

        $this->assertTrue($event->fresh()->is_kuota_penuh);
        $this->assertFalse($event->fresh()->is_pendaftaran_bisa_dilakukan);

        $response = $this->get(route('event.show', $event->slug));
        $response->assertSee('Batas Kuota Peserta Telah Terpenuhi');

        // Percobaan daftar berikutnya ditolak
        $postResponse = $this->post(route('event.daftar', $event->slug), [
            'nama_lengkap' => 'Peserta Terlambat',
            'email' => 'terlambat@example.com',
            'nomor_telepon' => '081277776666',
            'asal_instansi' => 'Umum',
            'jumlah_peserta' => 1,
        ]);

        $postResponse->assertSessionHas('error');
    }

    public function test_pendaftaran_ditolak_jika_event_telah_selesai(): void
    {
        $event = EventBudaya::factory()->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->subDays(10)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->subDays(8)->format('Y-m-d'),
        ]);

        $this->assertEquals('Selesai', $event->status_turunan);
        $this->assertFalse($event->is_pendaftaran_bisa_dilakukan);

        $response = $this->post(route('event.daftar', $event->slug), [
            'nama_lengkap' => 'Peserta Event Lewat',
            'email' => 'lewat@example.com',
            'nomor_telepon' => '081277776666',
            'asal_instansi' => 'Umum',
            'jumlah_peserta' => 1,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_pengelola_dapat_melihat_monitoring_peserta(): void
    {
        $event = EventBudaya::factory()->create();
        $pendaftaran = PendaftaranEvent::factory()->create([
            'event_budaya_id' => $event->id,
            'nama_lengkap' => 'Siti Nurhaliza',
        ]);

        $response = $this->actingAs($this->pengelola)
            ->get(route('pengelola.peserta.index'));

        $response->assertStatus(200);
        $response->assertSee('Monitoring & Presensi Peserta Event');
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee($pendaftaran->kode_pendaftaran);
    }

    public function test_pengelola_dapat_mengubah_status_kehadiran_peserta(): void
    {
        $peserta = PendaftaranEvent::factory()->create([
            'status' => 'terdaftar',
        ]);

        $response = $this->actingAs($this->pengelola)
            ->patch(route('pengelola.peserta.update-status', $peserta->id), [
                'status' => 'hadir',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pendaftaran_event', [
            'id' => $peserta->id,
            'status' => 'hadir',
        ]);
    }

    public function test_pengelola_dapat_membuka_lembar_cetak_presensi(): void
    {
        $event = EventBudaya::factory()->create();
        PendaftaranEvent::factory()->count(3)->create([
            'event_budaya_id' => $event->id,
        ]);

        $response = $this->actingAs($this->pengelola)
            ->get(route('pengelola.event.cetak-peserta', $event->id));

        $response->assertStatus(200);
        $response->assertSee('Lembar Presensi & Rekapitulasi Kehadiran Peserta Kegiatan');
        $response->assertSee($event->judul);
    }

    public function test_admin_dapat_mengakses_monitoring_peserta(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.peserta.index'));

        $response->assertStatus(200);
        $response->assertSee('Monitoring & Presensi Peserta Event');
    }

    public function test_pengunjung_dapat_mengakses_halaman_cek_pendaftaran(): void
    {
        $response = $this->get(route('event.pendaftaran.cek'));

        $response->assertStatus(200);
        $response->assertSee('Cek dan Lacak Bukti Pendaftaran');
        $response->assertSee('Layanan Mandiri Peserta');
    }

    public function test_pencarian_dengan_kode_pendaftaran_langsung_redirect_ke_bukti(): void
    {
        $pendaftaran = PendaftaranEvent::factory()->create([
            'kode_pendaftaran' => 'SGB-EVT-2610-TEST1',
        ]);

        $response = $this->get(route('event.pendaftaran.cek', ['q' => 'SGB-EVT-2610-TEST1']));

        $response->assertRedirect(route('event.pendaftaran.bukti', 'SGB-EVT-2610-TEST1'));
    }

    public function test_pencarian_dengan_email_menampilkan_daftar_pendaftaran(): void
    {
        $pendaftaran = PendaftaranEvent::factory()->create([
            'email' => 'peserta.mandiri@example.com',
            'nama_lengkap' => 'Budi Santoso',
        ]);

        $response = $this->get(route('event.pendaftaran.cek', ['q' => 'peserta.mandiri@example.com']));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee($pendaftaran->kode_pendaftaran);
        $response->assertSee(route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran));
    }

    public function test_pencarian_dengan_nomor_telepon_menampilkan_hasil(): void
    {
        $pendaftaran = PendaftaranEvent::factory()->create([
            'nomor_telepon' => '089876543210',
            'nama_lengkap' => 'Rina Melati',
        ]);

        $response = $this->get(route('event.pendaftaran.cek', ['q' => '089876543210']));

        $response->assertStatus(200);
        $response->assertSee('Rina Melati');
        $response->assertSee($pendaftaran->kode_pendaftaran);
    }

    public function test_pencarian_tidak_ditemukan_menampilkan_pesan_sesuai(): void
    {
        $response = $this->get(route('event.pendaftaran.cek', ['q' => 'tidakada@example.com']));

        $response->assertStatus(200);
        $response->assertSee('Data Pendaftaran Tidak Ditemukan');
    }
}
