<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'pengelola',
            'status' => 'aktif',
            'rejection_reason' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'status' => 'aktif',
        ]);
    }

    public function pengelola(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pengelola',
            'status' => 'aktif',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pengelola',
            'status' => 'pending',
        ]);
    }

    public function ditolak(?string $reason = 'Dokumen tidak sesuai'): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pengelola',
            'status' => 'ditolak',
            'rejection_reason' => $reason,
        ]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'nonaktif',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
