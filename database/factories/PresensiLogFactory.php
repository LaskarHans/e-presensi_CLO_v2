<?php

namespace Database\Factories;

use App\Models\Presensi;
use App\Models\PresensiLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PresensiLog>
 */
class PresensiLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'presensi_id' => Presensi::factory(),
            'diubah_oleh' => User::factory()->waliKelas(),
            'status_sebelumnya' => 'Alpha',
            'status_terbaru' => 'Hadir',
            'alasan_koreksi' => fake()->sentence(),
        ];
    }
}
