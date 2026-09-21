<?php

namespace Database\Factories;

use App\Models\KategoriWisata;
use App\Models\ObjekWisata;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ObjekWisata>
 */
class ObjekWisataFactory extends Factory
{
    protected $model = ObjekWisata::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = 'Wisata '.fake()->unique()->words(2, true);

        return [
            'kategori_wisata_id' => KategoriWisata::factory(),
            'nama' => ucwords($nama),
            'slug' => Str::slug($nama),
            'deskripsi' => fake()->paragraphs(2, true),
            'alamat' => 'Kampung Gedung Batin, Way Kanan, Lampung',
            'latitude' => fake()->latitude(-4.60, -4.40),
            'longitude' => fake()->longitude(104.40, 104.70),
            'jam_operasional' => '08.00 - 17.00 WIB',
            'harga_tiket' => 'Gratis / Sukarela',
            'kontak' => '081234567890',
            'foto_utama' => null,
            'status' => 'aktif',
            'created_by' => User::factory(),
        ];
    }
}
