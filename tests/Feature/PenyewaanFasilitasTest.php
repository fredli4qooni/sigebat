<?php

namespace Tests\Feature;

use App\Models\Fasilitas;
use App\Models\JenisFasilitas;
use App\Models\PengaturanPembayaran;
use App\Models\PenyewaanFasilitas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PenyewaanFasilitasTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $pengelola;

    private Fasilitas $homestay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->pengelola = User::factory()->create([
            'role' => 'pengelola',
            'status' => 'aktif',
        ]);

        $jenis = JenisFasilitas::create([
            'nama' => 'Penginapan / Homestay',
            'deskripsi' => 'Akomodasi bermalam wisatawan',
        ]);

        $this->homestay = Fasilitas::create([
            'jenis_fasilitas_id' => $jenis->id,
            'nama' => 'Homestay Adat Sesat Agung',
            'keterangan_lokasi' => 'Kompleks Rumah Panggung Gedung Batin',
            'bisa_disewa' => true,
            'harga_sewa_per_hari' => 350000,
            'kapasitas' => 8,
            'fasilitas_include' => 'Kasur spring bed, Listrik, Kamar mandi dalam, Kipas angin, Sarapan',
            'status_ketersediaan' => 'tersedia',
            'created_by' => $this->pengelola->id,
        ]);

        PengaturanPembayaran::getSetting();
    }

    public function test_wisatawan_dapat_melihat_halaman_sewa_fasilitas_yang_bisa_disewa(): void
    {
        $response = $this->get(route('fasilitas.sewa', $this->homestay));

        $response->assertOk();
        $response->assertSee('Homestay Adat Sesat Agung');
        $response->assertSee('Rp 350.000 / hari');
        $response->assertSee('Formulir Reservasi');
    }

    public function test_wisatawan_tidak_dapat_menyewa_fasilitas_yang_tidak_dibuka_untuk_sewa(): void
    {
        $fasilitasUmum = Fasilitas::factory()->create([
            'nama' => 'Toilet Umum Desa',
            'bisa_disewa' => false,
            'status_ketersediaan' => 'tersedia',
        ]);

        $response = $this->get(route('fasilitas.sewa', $fasilitasUmum));

        $response->assertRedirect(route('fasilitas.index'));
        $response->assertSessionHas('error');
    }

    public function test_wisatawan_tidak_dapat_menyewa_fasilitas_dalam_perbaikan(): void
    {
        $this->homestay->update(['status_ketersediaan' => 'perbaikan']);

        $response = $this->get(route('fasilitas.sewa', $this->homestay));

        $response->assertRedirect(route('fasilitas.index'));
        $response->assertSessionHas('error');
    }

    public function test_wisatawan_berhasil_mengajukan_booking_sewa_fasilitas(): void
    {
        $startDate = now()->addDays(3)->format('Y-m-d');
        $endDate = now()->addDays(5)->format('Y-m-d'); // 3 hari durasi: day 3, 4, 5

        $response = $this->post(route('fasilitas.sewa.store', $this->homestay), [
            'nama_penyewa' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'nomor_telepon' => '081234567890',
            'asal_instansi' => 'Keluarga Besar Jakarta',
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'jumlah_tamu' => 5,
            'catatan' => 'Mohon sediakan selimut tambahan',
        ]);

        $sewa = PenyewaanFasilitas::where('email', 'budi@example.com')->first();
        $this->assertNotNull($sewa);
        $this->assertStringStartsWith('SGB-SWA-', $sewa->kode_sewa);
        $this->assertEquals(3, $sewa->durasi_hari);
        $this->assertEquals(350000, $sewa->harga_per_hari);
        $this->assertEquals(1050000, $sewa->total_tagihan);
        $this->assertEquals('diajukan', $sewa->status_sewa);
        $this->assertEquals('menunggu_pembayaran', $sewa->status_pembayaran);

        $response->assertRedirect(route('sewa.bayar', $sewa->kode_sewa));
    }

    public function test_anti_double_booking_mencegah_penyewaan_pada_tanggal_yang_sama(): void
    {
        // Booking 1: 10 Okt s/d 12 Okt
        PenyewaanFasilitas::factory()->create([
            'fasilitas_id' => $this->homestay->id,
            'tanggal_mulai' => '2026-10-10',
            'tanggal_selesai' => '2026-10-12',
            'status_sewa' => 'dikonfirmasi',
            'status_pembayaran' => 'lunas',
        ]);

        // Booking 2: mencoba memesan tanggal overlap (11 Okt s/d 13 Okt)
        $response = $this->from(route('fasilitas.sewa', $this->homestay))
            ->post(route('fasilitas.sewa.store', $this->homestay), [
                'nama_penyewa' => 'Rina Wijaya',
                'email' => 'rina@example.com',
                'nomor_telepon' => '089876543210',
                'tanggal_mulai' => '2026-10-11',
                'tanggal_selesai' => '2026-10-13',
                'jumlah_tamu' => 4,
            ]);

        $response->assertRedirect(route('fasilitas.sewa', $this->homestay));
        $response->assertSessionHasErrors('tanggal_mulai');
        $this->assertEquals(1, PenyewaanFasilitas::where('fasilitas_id', $this->homestay->id)->count());
    }

    public function test_wisatawan_dapat_melihat_instruksi_pembayaran_rekening_desa(): void
    {
        $sewa = PenyewaanFasilitas::factory()->create([
            'fasilitas_id' => $this->homestay->id,
            'total_tagihan' => 700000,
        ]);

        $response = $this->get(route('sewa.bayar', $sewa->kode_sewa));

        $response->assertOk();
        $response->assertSee($sewa->kode_sewa);
        $response->assertSee('BANK LAMPUNG');
        $response->assertSee('381.03.01.09882.1');
        $response->assertSee('BUMDES KAMPUNG GEDUNG BATIN');
    }

    public function test_wisatawan_berhasil_mengunggah_bukti_transfer_sewa(): void
    {
        Storage::fake('public');

        $sewa = PenyewaanFasilitas::factory()->create([
            'fasilitas_id' => $this->homestay->id,
            'status_pembayaran' => 'menunggu_pembayaran',
        ]);

        $file = UploadedFile::fake()->image('struk_transfer.jpg', 600, 800);

        $response = $this->post(route('sewa.proses-bayar', $sewa->kode_sewa), [
            'metode_pembayaran' => 'transfer_bank',
            'bukti_pembayaran' => $file,
        ]);

        $sewa->refresh();
        $this->assertEquals('menunggu_verifikasi', $sewa->status_pembayaran);
        $this->assertEquals('transfer_bank', $sewa->metode_pembayaran);
        $this->assertNotNull($sewa->bukti_pembayaran);
        Storage::disk('public')->assertExists($sewa->bukti_pembayaran);

        $response->assertRedirect(route('sewa.bukti', $sewa->kode_sewa));
    }

    public function test_wisatawan_dapat_mengakses_voucher_tanda_bukti_sewa(): void
    {
        $sewa = PenyewaanFasilitas::factory()->lunas()->create([
            'fasilitas_id' => $this->homestay->id,
        ]);

        $response = $this->get(route('sewa.bukti', $sewa->kode_sewa));

        $response->assertOk();
        $response->assertSee($sewa->kode_sewa);
        $response->assertSee($sewa->nama_penyewa);
        $response->assertSee('Homestay Adat Sesat Agung');
        $response->assertSee('Lunas');
    }

    public function test_wisatawan_dapat_melacak_bukti_sewa_secara_mandiri(): void
    {
        $sewa = PenyewaanFasilitas::factory()->create([
            'fasilitas_id' => $this->homestay->id,
            'nama_penyewa' => 'Siti Aisyah',
            'nomor_telepon' => '082188889999',
            'email' => 'siti@example.com',
        ]);

        // 1. Pencarian tepat dengan kode sewa langsung mengarah ke halaman voucher
        $responseExact = $this->get(route('sewa.cek', ['q' => $sewa->kode_sewa]));
        $responseExact->assertRedirect(route('sewa.bukti', $sewa->kode_sewa));

        // 2. Pencarian fleksibel nomor telepon menampilkan daftar hasil
        $responsePhone = $this->get(route('sewa.cek', ['q' => '082188889999']));
        $responsePhone->assertOk();
        $responsePhone->assertSee($sewa->kode_sewa);
        $responsePhone->assertSee('Siti Aisyah');
    }

    public function test_pengelola_dapat_melihat_monitoring_sewa_fasilitas(): void
    {
        PenyewaanFasilitas::factory()->count(3)->create([
            'fasilitas_id' => $this->homestay->id,
        ]);

        $response = $this->actingAs($this->pengelola)->get(route('pengelola.penyewaan.index'));

        $response->assertOk();
        $response->assertSee('Monitoring &amp; Verifikasi Sewa Fasilitas', false);
        $response->assertSee('Homestay Adat Sesat Agung');
    }

    public function test_pengelola_dapat_menyetujui_verifikasi_pembayaran_sewa(): void
    {
        $sewa = PenyewaanFasilitas::factory()->menungguVerifikasi()->create([
            'fasilitas_id' => $this->homestay->id,
            'status_sewa' => 'diajukan',
        ]);

        $response = $this->actingAs($this->pengelola)->patch(route('pengelola.penyewaan.verifikasi', $sewa), [
            'aksi' => 'setujui',
            'catatan' => 'Dana transfer Rp 700.000 sudah masuk ke rekening kas BUMDes.',
        ]);

        $sewa->refresh();
        $this->assertEquals('lunas', $sewa->status_pembayaran);
        $this->assertEquals('dikonfirmasi', $sewa->status_sewa);
        $this->assertEquals($this->pengelola->id, $sewa->diverifikasi_oleh);
        $this->assertNotNull($sewa->waktu_verifikasi);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_pengelola_dapat_menolak_pembayaran_sewa_dengan_catatan(): void
    {
        $sewa = PenyewaanFasilitas::factory()->menungguVerifikasi()->create([
            'fasilitas_id' => $this->homestay->id,
        ]);

        $response = $this->actingAs($this->pengelola)->patch(route('pengelola.penyewaan.verifikasi', $sewa), [
            'aksi' => 'tolak',
            'catatan' => 'Foto struk buram dan tidak terbaca jelas.',
        ]);

        $sewa->refresh();
        $this->assertEquals('ditolak', $sewa->status_pembayaran);
        $this->assertEquals('Foto struk buram dan tidak terbaca jelas.', $sewa->catatan_verifikasi);

        $response->assertRedirect();
        $response->assertSessionHas('warning');
    }

    public function test_pengelola_dapat_mengubah_status_operasional_sewa(): void
    {
        $sewa = PenyewaanFasilitas::factory()->lunas()->create([
            'fasilitas_id' => $this->homestay->id,
            'status_sewa' => 'dikonfirmasi',
        ]);

        $response = $this->actingAs($this->pengelola)->patch(route('pengelola.penyewaan.update-status', $sewa), [
            'status_sewa' => 'digunakan',
        ]);

        $sewa->refresh();
        $this->assertEquals('digunakan', $sewa->status_sewa);
        $response->assertRedirect();
    }

    public function test_pengelola_dapat_mencetak_laporan_rekapitulasi_kas_sewa_a4(): void
    {
        PenyewaanFasilitas::factory()->lunas()->create([
            'fasilitas_id' => $this->homestay->id,
            'total_tagihan' => 1050000,
            'durasi_hari' => 3,
        ]);

        $response = $this->actingAs($this->pengelola)->get(route('pengelola.penyewaan.cetak-laporan'));

        $response->assertOk();
        $response->assertSee('Laporan Rekapitulasi Pendapatan Kas Sewa Fasilitas Desa');
        $response->assertSee('Rp 1.050.000');
        $response->assertSee('3 Hari');
    }
}
