<?php

namespace Database\Factories;

use App\Models\EventBudaya;
use App\Models\PendaftaranEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PendaftaranEvent>
 */
class PendaftaranEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_budaya_id' => EventBudaya::factory(),
            'nama_lengkap' => fake()->name(),
            'email' => fake()->safeEmail(),
            'nomor_telepon' => '08'.fake()->numerify('##########'),
            'asal_instansi' => fake()->city(),
            'jumlah_peserta' => fake()->numberBetween(1, 3),
            'catatan' => fake()->optional(0.3)->sentence(),
            'status' => 'terdaftar',
            'nominal_per_tiket' => 0,
            'total_tagihan' => 0,
            'status_pembayaran' => 'gratis',
            'metode_pembayaran' => null,
            'bukti_pembayaran' => null,
            'waktu_pembayaran' => null,
            'diverifikasi_oleh' => null,
            'waktu_verifikasi' => null,
            'catatan_verifikasi' => null,
        ];
    }

    public function forEvent(EventBudaya $event): static
    {
        return $this->state(fn () => [
            'event_budaya_id' => $event->id,
        ]);
    }

    public function hadir(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'hadir',
        ]);
    }

    public function batal(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'batal',
        ]);
    }

    public function menungguPembayaran(int $nominal = 25000): static
    {
        return $this->state(function (array $attributes) use ($nominal) {
            $jumlah = $attributes['jumlah_peserta'] ?? 1;

            return [
                'nominal_per_tiket' => $nominal,
                'total_tagihan' => $jumlah * $nominal,
                'status_pembayaran' => 'menunggu_pembayaran',
            ];
        });
    }

    public function menungguVerifikasi(int $nominal = 25000): static
    {
        return $this->state(function (array $attributes) use ($nominal) {
            $jumlah = $attributes['jumlah_peserta'] ?? 1;

            return [
                'nominal_per_tiket' => $nominal,
                'total_tagihan' => $jumlah * $nominal,
                'status_pembayaran' => 'menunggu_verifikasi',
                'metode_pembayaran' => 'transfer_bank',
                'bukti_pembayaran' => 'bukti_pembayaran/dummy.jpg',
                'waktu_pembayaran' => now(),
            ];
        });
    }

    public function lunas(int $nominal = 25000): static
    {
        return $this->state(function (array $attributes) use ($nominal) {
            $jumlah = $attributes['jumlah_peserta'] ?? 1;

            return [
                'nominal_per_tiket' => $nominal,
                'total_tagihan' => $jumlah * $nominal,
                'status_pembayaran' => 'lunas',
                'metode_pembayaran' => 'transfer_bank',
                'bukti_pembayaran' => 'bukti_pembayaran/dummy.jpg',
                'waktu_pembayaran' => now(),
                'waktu_verifikasi' => now(),
            ];
        });
    }
}
