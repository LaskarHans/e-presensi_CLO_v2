<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $info = [
            'CLOUD' => ['nama' => 'CLOUD COMPUTING', 'dosen' => 'Abdullah Mochamad Said, S.Pd.'],
            'PJOK' => ['nama' => 'PEND. JASMANI OLAHRAGA DAN KESEHATAN', 'dosen' => 'Setyo Dendiyah, S.Or., Gr.'],
            'SEJARAH' => ['nama' => 'SEJARAH', 'dosen' => 'Heri Sutomo, S.Sos., Gr.'],
            'WEB-NATIVE' => ['nama' => 'WEB NATIVE PROGRAMMING', 'dosen' => 'M. Fathur Rohman, S.Kom.'],
            'RSPD' => ['nama' => 'REKAYASA SISTEM DAN PEMODELAN DATA', 'dosen' => 'Andy Istomo N., S.Kom.'],
            'WEB-FRAMEWORK' => ['nama' => 'WEB FRAMEWORK TECHNOLOGY', 'dosen' => 'Yudi Nur Ans, S.Kom., Gr.'],
            'PBO' => ['nama' => 'PEMROGRAMAN BERORIENTASI OBJEK', 'dosen' => 'Fadjar Rochman, S.Kom., M.Kom., Gr.'],
            'BAHASA-INGGRIS' => ['nama' => 'BAHASA INGGRIS', 'dosen' => 'Tutut Mardiyanti, S.S., S.Pd.'],
            'IOT' => ['nama' => 'INTERNET OF THINGS', 'dosen' => 'M. Fahrizal Yulianto, S.Kom.'],
            'KIK' => ['nama' => 'KREATIVITAS, INOVASI, DAN KEWIRAUSAHAAN', 'dosen' => 'Mario Kristiyanto, S.Kom., Gr.'],
            'PABP' => ['nama' => 'PENDIDIKAN AGAMA DAN BUDI PEKERTI', 'dosen' => 'Aulia Farida Pramoni, S.Pd., Gr.'],
            'PANCASILA' => ['nama' => 'PENDIDIKAN PANCASILA', 'dosen' => 'Aldo Prasetyo, S.Pd., Gr.'],
            'MOBILE-APP' => ['nama' => 'MOBILE APP TECHNOLOGY', 'dosen' => 'Qodri Hilal Anwar, S.Pd.'],
            'BAHASA-INDONESIA' => ['nama' => 'BAHASA INDONESIA', 'dosen' => 'Juna Himawan Alqis, S.Pd.'],
            'MATEMATIKA' => ['nama' => 'MATEMATIKA', 'dosen' => 'Endah Widiastuti, S.Pd., Gr.'],
            'JAVASCRIPT' => ['nama' => 'JAVASCRIPT', 'dosen' => 'I Kadek Bagus Fuzi, S.Kom., M.M., Gr.'],
        ];

        // [kode, hari, jam_mulai, jam_selesai]
        $jadwal = [
            ['CLOUD', 'Senin', '06:45', '07:30'], ['PJOK', 'Senin', '07:30', '08:15'],
            ['PJOK', 'Senin', '08:15', '09:00'], ['PJOK', 'Senin', '09:00', '09:45'],
            ['SEJARAH', 'Senin', '10:15', '11:00'], ['SEJARAH', 'Senin', '11:00', '11:45'],
            ['WEB-NATIVE', 'Senin', '12:15', '13:00'], ['WEB-NATIVE', 'Senin', '13:00', '13:45'],
            ['WEB-NATIVE', 'Senin', '13:45', '14:30'], ['WEB-NATIVE', 'Senin', '14:30', '15:15'],

            ['RSPD', 'Selasa', '06:45', '07:30'], ['RSPD', 'Selasa', '07:30', '08:15'],
            ['RSPD', 'Selasa', '08:15', '09:00'], ['RSPD', 'Selasa', '09:00', '09:45'],
            ['WEB-FRAMEWORK', 'Selasa', '10:15', '11:00'], ['WEB-FRAMEWORK', 'Selasa', '11:00', '11:45'],
            ['WEB-FRAMEWORK', 'Selasa', '12:15', '13:00'], ['PBO', 'Selasa', '13:00', '13:45'],
            ['PBO', 'Selasa', '13:45', '14:30'], ['PBO', 'Selasa', '14:30', '15:15'],

            ['BAHASA-INGGRIS', 'Rabu', '06:45', '07:30'], ['BAHASA-INGGRIS', 'Rabu', '07:30', '08:15'],
            ['IOT', 'Rabu', '08:15', '09:00'], ['IOT', 'Rabu', '09:00', '09:45'],
            ['KIK', 'Rabu', '10:15', '11:00'], ['KIK', 'Rabu', '11:00', '11:45'],
            ['PABP', 'Rabu', '12:15', '13:00'], ['PABP', 'Rabu', '13:00', '13:45'],
            ['KIK', 'Rabu', '13:45', '14:30'], ['KIK', 'Rabu', '14:30', '15:15'],

            ['PANCASILA', 'Kamis', '06:45', '07:30'], ['PANCASILA', 'Kamis', '07:30', '08:15'],
            ['BAHASA-INGGRIS', 'Kamis', '08:15', '09:00'], ['BAHASA-INGGRIS', 'Kamis', '09:00', '09:45'],
            ['MOBILE-APP', 'Kamis', '10:45', '11:30'], ['MOBILE-APP', 'Kamis', '11:30', '12:15'],
            ['MOBILE-APP', 'Kamis', '12:15', '13:00'], ['MOBILE-APP', 'Kamis', '13:00', '13:45'],
            ['KIK', 'Kamis', '13:45', '14:30'], ['KIK', 'Kamis', '14:30', '15:15'],

            ['BAHASA-INDONESIA', 'Jumat', '06:45', '07:30'], ['BAHASA-INDONESIA', 'Jumat', '07:30', '08:05'],
            ['BAHASA-INDONESIA', 'Jumat', '08:05', '08:40'], ['MATEMATIKA', 'Jumat', '08:40', '09:15'],
            ['MATEMATIKA', 'Jumat', '09:45', '10:20'], ['MATEMATIKA', 'Jumat', '10:20', '10:55'],
            ['JAVASCRIPT', 'Jumat', '12:45', '13:20'],
            ['JAVASCRIPT', 'Jumat', '13:20', '13:55'], ['JAVASCRIPT', 'Jumat', '13:55', '14:30'],
        ];

        MataKuliah::query()->delete();

        foreach ($jadwal as [$kode, $hari, $mulai, $selesai]) {
            MataKuliah::create([
                'kode' => $kode,
                'nama' => $info[$kode]['nama'],
                'dosen' => $info[$kode]['dosen'],
                'ruang' => null,
                'hari' => $hari,
                'jam_mulai' => $mulai,
                'jam_selesai' => $selesai,
            ]);
        }
    }
}
