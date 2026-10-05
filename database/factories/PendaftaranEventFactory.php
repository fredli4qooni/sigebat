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
        ];
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
}
