<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $info = [
            'IF301' => ['nama' => 'Struktur Data & Algoritma', 'dosen' => 'Dr. Marcus Reid'],
            'IF302' => ['nama' => 'Sistem Operasi', 'dosen' => 'Prof. Elena Torres'],
            'IF304' => ['nama' => 'Manajemen Basis Data', 'dosen' => 'Dr. James Okafor'],
            'IF305' => ['nama' => 'Jaringan Komputer', 'dosen' => 'Dr. Amanda Chen'],
            'IF307' => ['nama' => 'Rekayasa Perangkat Lunak', 'dosen' => 'Prof. David Kim'],
            'IF308' => ['nama' => 'Kecerdasan Buatan', 'dosen' => 'Dr. Rina Wijaya'],
        ];

        // [kode, hari, jam_mulai, jam_selesai, ruang]
        $jadwal = [
            ['IF301', 'Senin', '08:00', '09:30', 'Lab 4B'],
            ['IF302', 'Senin', '10:00', '11:30', 'Aula 2'],
            ['IF304', 'Senin', '13:00', '14:30', 'Ruang 301'],
            ['IF307', 'Senin', '15:00', '16:30', 'Ruang 204'],

            ['IF305', 'Selasa', '09:00', '10:30', 'Lab 2A'],
            ['IF308', 'Selasa', '11:00', '12:30', 'Aula 1'],

            ['IF301', 'Rabu', '08:00', '09:30', 'Lab 4B'],
            ['IF304', 'Rabu', '13:00', '14:30', 'Ruang 301'],

            ['IF302', 'Kamis', '10:00', '11:30', 'Aula 2'],
            ['IF305', 'Kamis', '14:00', '15:30', 'Lab 2A'],
            ['IF307', 'Kamis', '16:00', '17:30', 'Ruang 204'],

            ['IF308', 'Jumat', '09:00', '10:30', 'Aula 1'],
            ['IF301', 'Jumat', '11:00', '12:30', 'Lab 4B'],

            //SABTU TIDAK ADA

            ['IF301', 'Minggu', '08:00', '09:30', 'Lab 4B'],
        ];

        foreach ($jadwal as [$kode, $hari, $mulai, $selesai, $ruang]) {
            MataKuliah::create([
                'kode' => $kode,
                'nama' => $info[$kode]['nama'],
                'dosen' => $info[$kode]['dosen'],
                'ruang' => $ruang,
                'hari' => $hari,
                'jam_mulai' => $mulai,
                'jam_selesai' => $selesai,
            ]);
        }
    }
}
