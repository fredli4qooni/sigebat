<?php

namespace Tests\Feature;

use App\Models\EventBudaya;
use App\Models\PendaftaranEvent;
use App\Models\PengaturanPembayaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaturanPembayaranTest extends TestCase
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

    public function test_pengelola_dapat_melihat_halaman_pengaturan_rekening_dan_qris(): void
    {
        $response = $this->actingAs($this->pengelola)
            ->get(route('pengelola.pengaturan-pembayaran.edit'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Rekening Kas &amp; QRIS Pembayaran', false);
        $response->assertSee('Rekening Bank Utama (BUMDes)');
        $response->assertSee('Kode QRIS Resmi Desa Wisata');
    }

    public function test_admin_dapat_melihat_halaman_pengaturan_rekening_dan_qris(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.pengaturan-pembayaran.edit'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Rekening Kas &amp; QRIS Pembayaran', false);
    }

    public function test_pengelola_dapat_memperbarui_nomor_rekening_bumdes(): void
    {
        $response = $this->actingAs($this->pengelola)
            ->put(route('pengelola.pengaturan-pembayaran.update'), [
                'nama_bank_utama' => 'Bank Mandiri',
                'nomor_rekening_utama' => '1140029384721',
                'atas_nama_utama' => 'BUMDes Mandiri Gedung Batin',
                'nama_bank_sekunder' => 'Bank BNI',
                'nomor_rekening_sekunder' => '0981238472',
                'atas_nama_sekunder' => 'Pengelola Wisata BNI',
                'nama_qris' => 'QRIS BUMDes Maju Bersama',
                'nmid_qris' => 'ID9988776655',
                'petunjuk_transfer' => 'Harap konfirmasi setelah transfer.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = PengaturanPembayaran::getSetting();
        $this->assertSame('BANK MANDIRI', $setting->nama_bank_utama);
        $this->assertSame('1140029384721', $setting->nomor_rekening_utama);
        $this->assertSame('BUMDES MANDIRI GEDUNG BATIN', $setting->atas_nama_utama);
        $this->assertSame('BANK BNI', $setting->nama_bank_sekunder);
        $this->assertSame('0981238472', $setting->nomor_rekening_sekunder);
        $this->assertSame('QRIS BUMDes Maju Bersama', $setting->nama_qris);
    }

    public function test_pengelola_dapat_mengunggah_gambar_qris_baru_dan_menghapusnya(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('qris_resmi.png');

        $response = $this->actingAs($this->pengelola)
            ->put(route('pengelola.pengaturan-pembayaran.update'), [
                'nama_bank_utama' => 'Bank Lampung',
                'nomor_rekening_utama' => '3810301098821',
                'atas_nama_utama' => 'BUMDes Gedung Batin',
                'nama_qris' => 'QRIS Resmi Desa',
                'qris_image' => $file,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = PengaturanPembayaran::getSetting();
        $this->assertNotNull($setting->qris_image);
        Storage::disk('public')->assertExists($setting->qris_image);

        // Uji hapus QRIS
        $responseHapus = $this->actingAs($this->pengelola)
            ->put(route('pengelola.pengaturan-pembayaran.update'), [
                'nama_bank_utama' => 'Bank Lampung',
                'nomor_rekening_utama' => '3810301098821',
                'atas_nama_utama' => 'BUMDes Gedung Batin',
                'nama_qris' => 'QRIS Resmi Desa',
                'hapus_qris' => true,
            ]);

        $responseHapus->assertRedirect();
        $setting->refresh();
        $this->assertNull($setting->qris_image);
    }

    public function test_halaman_pembayaran_publik_menampilkan_rekening_dan_qris_dinamis(): void
    {
        // Ubah pengaturan pembayaran terlebih dahulu
        $setting = PengaturanPembayaran::getSetting();
        $setting->update([
            'nama_bank_utama' => 'BANK SYARIAH INDONESIA',
            'nomor_rekening_utama' => '7182930491',
            'atas_nama_utama' => 'BUMDES SYARIAH GEDUNG BATIN',
            'nama_qris' => 'QRIS BUMDes BSI',
        ]);

        $event = EventBudaya::factory()->berbayar(25000)->create([
            'status' => 'aktif',
            'buka_pendaftaran' => true,
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->addDays(3)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now('Asia/Jakarta')->addDays(4)->format('Y-m-d'),
        ]);

        $pendaftaran = PendaftaranEvent::factory()->forEvent($event)->menungguPembayaran()->create([
            'total_tagihan' => 25000,
        ]);

        $response = $this->get(route('event.pendaftaran.bayar', $pendaftaran->kode_pendaftaran));

        $response->assertStatus(200);
        $response->assertSee('BANK SYARIAH INDONESIA');
        $response->assertSee('7182930491');
        $response->assertSee('BUMDES SYARIAH GEDUNG BATIN');
        $response->assertSee('QRIS BUMDes BSI');
    }

    public function test_guest_tidak_dapat_mengakses_halaman_pengaturan_pembayaran(): void
    {
        $response = $this->get(route('pengelola.pengaturan-pembayaran.edit'));

        $response->assertRedirect(route('login'));
    }
}
