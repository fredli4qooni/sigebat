<?php

namespace Tests\Feature;

use App\Models\EventBudaya;
use App\Models\PendaftaranEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembayaranEventTest extends TestCase
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

    public function test_pendaftaran_event_gratis_otomatis_berstatus_lunas(): void
    {
        $event = EventBudaya::factory()->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'tipe_event' => 'gratis',
            'harga_tiket' => 0,
            'kuota_peserta' => 50,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->addDays(3)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->addDays(4)->format('Y-m-d'),
        ]);

        $payload = [
            'nama_lengkap' => 'Budi Utomo',
            'email' => 'budi@example.com',
            'nomor_telepon' => '081234567890',
            'asal_instansi' => 'Bandar Lampung',
            'jumlah_peserta' => 2,
        ];

        $response = $this->post(route('event.daftar', $event->slug), $payload);

        $pendaftaran = PendaftaranEvent::where('email', 'budi@example.com')->first();
        $this->assertNotNull($pendaftaran);
        $this->assertSame(0, $pendaftaran->total_tagihan);
        $this->assertTrue($pendaftaran->is_lunas);

        $response->assertRedirect(route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran));
    }

    public function test_pendaftaran_event_berbayar_menerbitkan_tagihan_dan_status_menunggu_pembayaran(): void
    {
        $event = EventBudaya::factory()->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'tipe_event' => 'berbayar',
            'harga_tiket' => 25000,
            'kuota_peserta' => 50,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->addDays(4)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->addDays(5)->format('Y-m-d'),
        ]);

        $payload = [
            'nama_lengkap' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'nomor_telepon' => '081298765432',
            'asal_instansi' => 'Palembang',
            'jumlah_peserta' => 3,
        ];

        $response = $this->post(route('event.daftar', $event->slug), $payload);

        $pendaftaran = PendaftaranEvent::where('email', 'siti@example.com')->first();
        $this->assertNotNull($pendaftaran);
        $this->assertSame(25000, $pendaftaran->nominal_per_tiket);
        $this->assertSame(75000, $pendaftaran->total_tagihan);
        $this->assertSame('menunggu_pembayaran', $pendaftaran->status_pembayaran);

        // Setelah daftar event berbayar, langsung diarahkan ke halaman pembayaran
        $response->assertRedirect(route('event.pendaftaran.bayar', $pendaftaran->kode_pendaftaran));
    }

    public function test_pengunjung_dapat_mengakses_halaman_pembayaran_event_berbayar(): void
    {
        $event = EventBudaya::factory()->berbayar(50000)->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
        ]);

        $pendaftaran = PendaftaranEvent::factory()->forEvent($event)->menungguPembayaran()->create([
            'jumlah_peserta' => 2,
            'nominal_per_tiket' => 50000,
            'total_tagihan' => 100000,
        ]);

        $response = $this->get(route('event.pendaftaran.bayar', $pendaftaran->kode_pendaftaran));

        $response->assertStatus(200);
        $response->assertSee('Rp 100.000');
        $response->assertSee('Transfer Bank BUMDes');
        $response->assertSee('QRIS Desa Wisata');
        $response->assertDontSee('Mode Sidang Skripsi');
    }

    public function test_pengunjung_dapat_mengunggah_bukti_transfer_pembayaran(): void
    {
        Storage::fake('public');

        $event = EventBudaya::factory()->berbayar(30000)->create();
        $pendaftaran = PendaftaranEvent::factory()->forEvent($event)->menungguPembayaran()->create([
            'nominal_per_tiket' => 30000,
            'total_tagihan' => 30000,
        ]);

        $file = UploadedFile::fake()->image('struk_transfer.jpg');

        $response = $this->post(route('event.pendaftaran.proses-bayar', $pendaftaran->kode_pendaftaran), [
            'metode_pembayaran' => 'transfer_bank',
            'bukti_pembayaran' => $file,
            'catatan_pembayar' => 'Transfer via Bank Lampung a.n Siti',
        ]);

        $pendaftaran->refresh();
        $this->assertSame('menunggu_verifikasi', $pendaftaran->status_pembayaran);
        $this->assertSame('transfer_bank', $pendaftaran->metode_pembayaran);
        $this->assertNotNull($pendaftaran->bukti_pembayaran);
        $this->assertNotNull($pendaftaran->waktu_pembayaran);

        Storage::disk('public')->assertExists($pendaftaran->bukti_pembayaran);

        $response->assertRedirect(route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran));
    }

    public function test_pengunjung_dapat_menjalankan_simulasi_bayar_cepat_untuk_sidang_skripsi(): void
    {
        $event = EventBudaya::factory()->berbayar(40000)->create();
        $pendaftaran = PendaftaranEvent::factory()->forEvent($event)->menungguPembayaran()->create([
            'nominal_per_tiket' => 40000,
            'total_tagihan' => 40000,
        ]);

        $response = $this->post(route('event.pendaftaran.simulasi-bayar', $pendaftaran->kode_pendaftaran));

        $pendaftaran->refresh();
        $this->assertSame('lunas', $pendaftaran->status_pembayaran);
        $this->assertSame('simulasi_demo', $pendaftaran->metode_pembayaran);
        $this->assertNotNull($pendaftaran->waktu_pembayaran);
        $this->assertNotNull($pendaftaran->waktu_verifikasi);

        $response->assertRedirect(route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran));
    }

    public function test_pengelola_dapat_melihat_daftar_transaksi_keuangan_dan_metrik(): void
    {
        $event = EventBudaya::factory()->berbayar(20000)->create(['judul' => 'Festival Tari Tradisi']);
        PendaftaranEvent::factory()->forEvent($event)->lunas()->create(['total_tagihan' => 40000, 'jumlah_peserta' => 2]);
        PendaftaranEvent::factory()->forEvent($event)->menungguVerifikasi()->create(['total_tagihan' => 20000]);

        $response = $this->actingAs($this->pengelola)->get(route('pengelola.pembayaran.index'));

        $response->assertStatus(200);
        $response->assertSee('Monitoring &amp; Verifikasi Pembayaran Event', false);
        $response->assertSee('Festival Tari Tradisi');
        $response->assertSee('Kas Masuk Lunas');
    }

    public function test_pengelola_dapat_menyetujui_verifikasi_pembayaran(): void
    {
        $event = EventBudaya::factory()->berbayar(50000)->create();
        $pendaftaran = PendaftaranEvent::factory()->forEvent($event)->menungguVerifikasi()->create([
            'total_tagihan' => 50000,
        ]);

        $response = $this->actingAs($this->pengelola)->patch(
            route('pengelola.pembayaran.verifikasi', $pendaftaran->id),
            [
                'aksi' => 'setujui',
                'catatan' => 'Transfer sesuai dengan mutasi kas BUMDes',
            ]
        );

        $pendaftaran->refresh();
        $this->assertSame('lunas', $pendaftaran->status_pembayaran);
        $this->assertSame($this->pengelola->id, $pendaftaran->diverifikasi_oleh);
        $this->assertNotNull($pendaftaran->waktu_verifikasi);

        $response->assertSessionHas('success');
    }

    public function test_pengelola_dapat_menolak_verifikasi_pembayaran_dengan_catatan(): void
    {
        $event = EventBudaya::factory()->berbayar(50000)->create();
        $pendaftaran = PendaftaranEvent::factory()->forEvent($event)->menungguVerifikasi()->create([
            'total_tagihan' => 50000,
        ]);

        $response = $this->actingAs($this->pengelola)->patch(
            route('pengelola.pembayaran.verifikasi', $pendaftaran->id),
            [
                'aksi' => 'tolak',
                'catatan' => 'Bukti struk buram dan tidak terbaca jelas.',
            ]
        );

        $pendaftaran->refresh();
        $this->assertSame('ditolak', $pendaftaran->status_pembayaran);
        $this->assertSame('Bukti struk buram dan tidak terbaca jelas.', $pendaftaran->catatan_verifikasi);

        $response->assertSessionHas('warning');
    }

    public function test_pengelola_dapat_mencetak_laporan_rekapitulasi_kas_event(): void
    {
        $event = EventBudaya::factory()->berbayar(25000)->create(['judul' => 'Pesta Danau Kampung Gedung Batin']);
        PendaftaranEvent::factory()->forEvent($event)->lunas()->create([
            'total_tagihan' => 50000,
            'jumlah_peserta' => 2,
        ]);

        $response = $this->actingAs($this->pengelola)->get(
            route('pengelola.pembayaran.cetak-laporan', ['event_id' => $event->id])
        );

        $response->assertStatus(200);
        $response->assertSee('Laporan Rekapitulasi Kas Penerimaan Event Budaya');
        $response->assertSee('Pesta Danau Kampung Gedung Batin');
        $response->assertSee('Rp 50.000');
    }

    public function test_guest_tidak_dapat_mengakses_panel_keuangan_pengelola(): void
    {
        $response = $this->get(route('pengelola.pembayaran.index'));

        $response->assertRedirect(route('login'));
    }
}
