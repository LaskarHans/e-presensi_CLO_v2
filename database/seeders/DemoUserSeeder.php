<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = User::updateOrCreate(
            ['nomor_induk' => '0012345678'],
            [
                'name' => 'Siswa Demo',
                'email' => 'siswa.demo@e-hadir.test',
                'role' => 'siswa',
                'password' => Hash::make('demo-siswa-2026'),
                'nim' => '20260001',
                'prodi' => 'Rekayasa Perangkat Lunak',
                'angkatan' => '2026',
            ],
        );

        $waliKelas = User::updateOrCreate(
            ['nomor_induk' => '198501012010011001'],
            [
                'name' => 'Wali Kelas Demo',
                'email' => 'wali.demo@e-hadir.test',
                'role' => 'wali_kelas',
                'password' => Hash::make('demo-wali-2026'),
            ],
        );

        $kelas = Kelas::updateOrCreate(
            ['nama' => 'XII RPL 1', 'tahun_ajaran' => '2026/2027'],
            ['tingkat' => 'XII'],
        );

        $kelas->siswa()->syncWithoutDetaching([
            $siswa->id => ['aktif' => true, 'mulai_pada' => '2026-07-01'],
        ]);
        $kelas->waliKelas()->syncWithoutDetaching([
            $waliKelas->id => ['aktif' => true, 'mulai_pada' => '2026-07-01'],
        ]);
    }
}
