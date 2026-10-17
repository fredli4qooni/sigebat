<?php

namespace Database\Factories;

use App\Models\Fasilitas;
use App\Models\PenyewaanFasilitas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenyewaanFasilitas>
 */
class PenyewaanFasilitasFactory extends Factory
{
    protected $model = PenyewaanFasilitas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hargaPerHari = 250000;
        $durasiHari = 2;
        $totalTagihan = $hargaPerHari * $durasiHari;
        $startDate = now()->addDays(2)->format('Y-m-d');
        $endDate = now()->addDays(3)->format('Y-m-d');

        return [
            'fasilitas_id' => Fasilitas::factory()->disewakan($hargaPerHari),
            'nama_penyewa' => fake()->name(),
            'email' => fake()->safeEmail(),
            'nomor_telepon' => '08'.fake()->numerify('##########'),
            'asal_instansi' => fake()->company(),
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'durasi_hari' => $durasiHari,
            'jumlah_tamu' => 4,
            'harga_per_hari' => $hargaPerHari,
            'total_tagihan' => $totalTagihan,
            'catatan' => fake()->optional(0.3)->sentence(),
            'status_sewa' => 'diajukan',
            'status_pembayaran' => 'menunggu_pembayaran',
            'metode_pembayaran' => null,
            'bukti_pembayaran' => null,
            'waktu_pembayaran' => null,
            'diverifikasi_oleh' => null,
            'waktu_verifikasi' => null,
            'catatan_verifikasi' => null,
        ];
    }

    public function lunas(): static
    {
        return $this->state(fn () => [
            'status_sewa' => 'dikonfirmasi',
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => 'transfer_bank',
            'bukti_pembayaran' => 'bukti_pembayaran/dummy.jpg',
            'waktu_pembayaran' => now()->subHours(2),
            'diverifikasi_oleh' => User::factory(),
            'waktu_verifikasi' => now()->subHour(),
            'catatan_verifikasi' => 'Pembayaran valid via BUMDes',
        ]);
    }

    public function menungguVerifikasi(): static
    {
        return $this->state(fn () => [
            'status_pembayaran' => 'menunggu_verifikasi',
            'metode_pembayaran' => 'qris',
            'bukti_pembayaran' => 'bukti_pembayaran/dummy.jpg',
            'waktu_pembayaran' => now()->subMinutes(30),
        ]);
    }
}
