<?php

namespace Database\Factories;

use App\Models\Fasilitas;
use App\Models\JenisFasilitas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fasilitas>
 */
class FasilitasFactory extends Factory
{
    protected $model = Fasilitas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenis_fasilitas_id' => JenisFasilitas::factory(),
            'objek_wisata_id' => null,
            'nama' => fake()->words(2, true),
            'deskripsi' => fake()->sentence(),
            'keterangan_lokasi' => 'Dekat Balai Kampung Gedung Batin',
            'foto' => null,
            'created_by' => User::factory(),
            'bisa_disewa' => false,
            'harga_sewa_per_hari' => 0,
            'kapasitas' => null,
            'fasilitas_include' => null,
            'status_ketersediaan' => 'tersedia',
        ];
    }

    public function disewakan(int $harga = 250000, int $kapasitas = 6): static
    {
        return $this->state(fn () => [
            'bisa_disewa' => true,
            'harga_sewa_per_hari' => $harga,
            'kapasitas' => $kapasitas,
            'fasilitas_include' => 'Listrik, Kasur, Toilet dalam, Kipas angin',
            'status_ketersediaan' => 'tersedia',
        ]);
    }
}
