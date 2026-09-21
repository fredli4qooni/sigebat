<?php

namespace Database\Factories;

use App\Models\KategoriEvent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<KategoriEvent>
 */
class KategoriEventFactory extends Factory
{
    protected $model = KategoriEvent::class;

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
