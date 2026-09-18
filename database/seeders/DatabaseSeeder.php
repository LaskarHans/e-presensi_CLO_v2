<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\SiswaProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $waliKelas = User::updateOrCreate(
            ['nomor_induk' => '999'],
            [
                'name' => 'Wali Kelas XI RPL 1',
                'email' => 'wali.kelas.999@example.com',
                'role' => 'wali_kelas',
                'password' => Hash::make('999'),
            ],
        );

        $kelas = Kelas::updateOrCreate(
            ['nama' => 'XI RPL 1'],
            ['wali_kelas_id' => $waliKelas->id],
        );

        foreach (range(1, 26) as $nomor) {
            $nomorInduk = str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
            $siswa = User::updateOrCreate(
                ['nomor_induk' => $nomorInduk],
                [
                    'name' => "Siswa {$nomor}",
                    'email' => "siswa.{$nomorInduk}@example.com",
                    'role' => 'siswa',
                    'password' => Hash::make('password'),
                ],
            );

            SiswaProfile::updateOrCreate(
                ['user_id' => $siswa->id],
                ['kelas_id' => $kelas->id],
            );
        }

        $this->call([
            MataKuliahSeeder::class,
            PresensiSeeder::class,
        ]);
    }
}
