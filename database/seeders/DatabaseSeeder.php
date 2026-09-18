<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $kelas = Kelas::updateOrCreate(
            ['nama' => 'XI RPL 1'],
            ['tingkat' => 'XI', 'tahun_ajaran' => '2026/2027'],
        );

        $waliKelas = User::updateOrCreate(
            ['nomor_induk' => '999'],
            [
                'name' => 'Wali Kelas XI RPL 1',
                'email' => 'wali.kelas.999@e-hadir.test',
                'role' => 'wali_kelas',
                'password' => Hash::make('999'),
            ],
        );

        $kelas->waliKelas()->sync([
            $waliKelas->id => ['aktif' => true, 'mulai_pada' => today()->toDateString()],
        ]);

        $siswa = [];
        for ($nomor = 1; $nomor <= 26; $nomor++) {
            $nomorInduk = str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
            $siswaUser = User::updateOrCreate(
                ['nomor_induk' => $nomorInduk],
                [
                    'name' => "Siswa {$nomor}",
                    'email' => "siswa.{$nomorInduk}@e-hadir.test",
                    'role' => 'siswa',
                    'password' => Hash::make('password'),
                ],
            );

            $siswa[$siswaUser->id] = [
                'aktif' => true,
                'mulai_pada' => today()->toDateString(),
            ];
        }

        $kelas->siswa()->sync($siswa);

        $this->call([
            MataKuliahSeeder::class,
            PresensiSeeder::class,
        ]);
    }
}
