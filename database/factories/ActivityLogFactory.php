<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'aksi' => 'LOGIN',
            'entitas_tipe' => null,
            'entitas_id' => null,
            'keterangan' => ['catatan' => 'User berhasil login'],
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ];
    }
}
