<?php

namespace Database\Factories;

use App\Models\KategoriWisata;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<KategoriWisata>
 */
class KategoriWisataFactory extends Factory
{
    protected $model = KategoriWisata::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = fake()->unique()->words(2, true);

        return [
            'nama' => ucfirst($nama),
            'slug' => Str::slug($nama),
            'deskripsi' => fake()->sentence(),
        ];
    }
}
