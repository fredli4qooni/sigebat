<?php

namespace Database\Factories;

use App\Models\JenisFasilitas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JenisFasilitas>
 */
class JenisFasilitasFactory extends Factory
{
    protected $model = JenisFasilitas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => ucfirst(fake()->unique()->word()),
            'icon' => 'buildings',
        ];
    }
}
