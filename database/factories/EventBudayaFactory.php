<?php

namespace Database\Factories;

use App\Models\EventBudaya;
use App\Models\KategoriEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EventBudaya>
 */
class EventBudayaFactory extends Factory
{
    protected $model = EventBudaya::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $judul = 'Festival '.fake()->unique()->words(2, true);
        $mulai = fake()->dateTimeBetween('+1 days', '+30 days');
        $selesai = (clone $mulai)->modify('+2 days');

        return [
            'kategori_event_id' => KategoriEvent::factory(),
            'judul' => ucwords($judul),
            'slug' => Str::slug($judul),
            'deskripsi' => fake()->paragraphs(2, true),
            'tanggal_mulai' => $mulai->format('Y-m-d'),
            'tanggal_selesai' => $selesai->format('Y-m-d'),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '17:00:00',
            'lokasi' => 'Balai Adat Kampung Gedung Batin',
            'poster' => null,
            'status' => 'aktif',
            'created_by' => User::factory(),
        ];
    }
}
