<?php

namespace Database\Factories;

use App\Models\PengajuanIzin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PengajuanIzin>
 */
class PengajuanIzinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'siswa_id' => User::factory()->siswa(),
            'tanggal' => fake()->date(),
            'jenis' => fake()->randomElement(['izin', 'sakit']),
            'alasan' => fake()->sentence(),
            'status' => 'menunggu',
        ];
    }
}
