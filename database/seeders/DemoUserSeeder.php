<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['nomor_induk' => '0012345678'],
            [
                'name' => 'Siswa Demo',
                'email' => 'siswa.demo@e-hadir.test',
                'role' => 'siswa',
                'password' => Hash::make('demo-siswa-2026'),
            ],
        );

        User::updateOrCreate(
            ['nomor_induk' => '198501012010011001'],
            [
                'name' => 'Wali Kelas Demo',
                'email' => 'wali.demo@e-hadir.test',
                'role' => 'wali_kelas',
                'password' => Hash::make('demo-wali-2026'),
            ],
        );
    }
}
