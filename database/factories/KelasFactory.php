<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->bothify('X-?#'),
            'tingkat' => fake()->randomElement(['X', 'XI', 'XII']),
            'tahun_ajaran' => '2026/2027',
        ];
    }
}
