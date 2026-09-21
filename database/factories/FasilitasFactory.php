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
        ];
    }
}
