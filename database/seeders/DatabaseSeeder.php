<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@example.com',
            'nim' => 'MHS-2024-0892',
            'prodi' => 'S1 Ilmu Komputer',
            'angkatan' => '2024',
        ]);

        $this->call([
            MataKuliahSeeder::class,
            PresensiSeeder::class,
        ]);
    }
}
