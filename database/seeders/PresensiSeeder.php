<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

    class PresensiSeeder extends Seeder
{
    private array $namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return;
        }

        $kodeUnik = MataKuliah::select('kode')->distinct()->pluck('kode');

        foreach ($kodeUnik as $kode) {
            $sesiMingguan = MataKuliah::where('kode', $kode)->get(); // 1 mata kuliah bisa >1 sesi/minggu
            $hariSesi = $sesiMingguan->pluck('hari');
            $jumlahPertemuan = fake()->numberBetween(14, 22);

            $tanggal = Carbon::yesterday();
            $dibuat = 0;
            $pengaman = 200;

            while ($dibuat < $jumlahPertemuan && $pengaman > 0) {
                $namaHariIni = $this->namaHari[$tanggal->dayOfWeek];

                if ($hariSesi->contains($namaHariIni)) {
                    $sesi = $sesiMingguan->firstWhere('hari', $namaHariIni);

                    $status = fake()->randomElement([
                        'Hadir', 'Hadir', 'Hadir', 'Hadir', 'Hadir', 'Hadir', 'Hadir',
                        'Terlambat', 'Terlambat',
                        'Tidak Hadir',
                    ]);

                    $waktuMasuk = null;
                    if ($status !== 'Tidak Hadir') {
                        $jamMulai = Carbon::parse($sesi->jam_mulai);
                        $offset = $status === 'Hadir'
                            ? fake()->numberBetween(-10, 14)
                            : fake()->numberBetween(16, 40);
                        $waktuMasuk = $jamMulai->addMinutes($offset)->format('H:i:s');
                    }

                    Presensi::create([
                        'user_id' => $user->id,
                        'mata_kuliah_id' => $sesi->id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'status' => $status,
                        'waktu_masuk' => $waktuMasuk,
                    ]);

                    $dibuat++;
                }

                $tanggal = $tanggal->copy()->subDay();
                $pengaman--;
            }
        }
    }
}
