<?php

namespace Database\Factories;

use App\Models\Presensi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Presensi>
 */
class PresensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->siswa(),
            'mata_kuliah_id' => null,
            'tanggal' => today(),
            'status' => 'Hadir',
            'waktu_masuk' => '06:45:00',
        ];
    }
}
