<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveIzinRequest;
use App\Http\Requests\KoreksiPresensiRequest;
use App\Http\Requests\RejectIzinRequest;
use App\Http\Requests\RekapKelasRequest;
use App\Models\Kelas;
use App\Models\PengajuanIzin;
use App\Models\Presensi;
use App\Models\PresensiLog;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WaliKelasController extends Controller
{
    public function dashboard(Request $request): View
    {
        $kelas = $this->kelasBinaan($request->user());
        $siswa = $kelas->siswaAktif()->orderBy('name')->get();
        $presensiHariIni = $this->presensiHarian($siswa->modelKeys(), today())->keyBy('user_id');
        $ringkasan = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];

        $daftarSiswa = $siswa->map(function (User $siswa) use ($presensiHariIni, &$ringkasan): object {
            $presensi = $presensiHariIni->get($siswa->id);
            $status = $presensi?->status ?? 'Alpha';
            $kategori = match ($status) {
                'Hadir', 'Terlambat' => 'Hadir',
                'Izin', 'Sakit' => $status,
                default => 'Alpha',
            };
            $ringkasan[$kategori]++;

            return (object) [
                'siswa' => $siswa,
                'presensi' => $presensi,
                'status' => $status,
            ];
        });

        return view('wali-kelas.dashboard', compact('kelas', 'ringkasan', 'daftarSiswa'));
    }

    public function pengajuanIzin(Request $request): View
    {
        $kelas = $this->kelasBinaan($request->user());
        $siswaIds = $kelas->siswaAktif()->pluck('users.id');
        $pengajuans = PengajuanIzin::query()
            ->with('siswa')
            ->whereIn('siswa_id', $siswaIds)
            ->where('status', 'menunggu')
            ->orderBy('tanggal')
            ->get();

        return view('wali-kelas.pengajuan-izin', compact('kelas', 'pengajuans'));
    }

    public function approveIzin(ApproveIzinRequest $request, int $id): RedirectResponse
    {
        $waliKelas = $request->user();
        $kelas = $this->kelasBinaan($waliKelas);
        $pengajuan = PengajuanIzin::query()->where('status', 'menunggu')->findOrFail($id);
        $this->siswaDalamKelas($kelas, $pengajuan->siswa_id);

        DB::transaction(function () use ($request, $waliKelas, $pengajuan): void {
            $pengajuan->update([
                'status' => 'disetujui',
                'diverifikasi_oleh' => $waliKelas->id,
                'diverifikasi_pada' => now(),
                'catatan_verifikasi' => $request->validated('catatan_verifikasi'),
            ]);

            Presensi::updateOrCreate(
                [
                    'user_id' => $pengajuan->siswa_id,
                    'mata_kuliah_id' => null,
                    'tanggal' => $pengajuan->tanggal,
                ],
                ['status' => ucfirst($pengajuan->jenis)],
            );
        });

        return redirect()->route('wali-kelas.pengajuan-izin.index')
            ->with('success', 'Pengajuan berhasil disetujui dan presensi diperbarui.');
    }

    public function rejectIzin(RejectIzinRequest $request, int $id): RedirectResponse
    {
        $waliKelas = $request->user();
        $kelas = $this->kelasBinaan($waliKelas);
        $pengajuan = PengajuanIzin::query()->where('status', 'menunggu')->findOrFail($id);
        $this->siswaDalamKelas($kelas, $pengajuan->siswa_id);

        $pengajuan->update([
            'status' => 'ditolak',
            'diverifikasi_oleh' => $waliKelas->id,
            'diverifikasi_pada' => now(),
            'catatan_verifikasi' => $request->validated('catatan_verifikasi'),
        ]);

        return redirect()->route('wali-kelas.pengajuan-izin.index')
            ->with('success', 'Pengajuan telah ditolak.');
    }

    public function koreksiPresensi(KoreksiPresensiRequest $request, int $presensiId): RedirectResponse
    {
        $waliKelas = $request->user();
        $kelas = $this->kelasBinaan($waliKelas);
        $siswaIds = $kelas->siswaAktif()->pluck('users.id');
        $presensi = Presensi::query()
            ->whereKey($presensiId)
            ->whereIn('user_id', $siswaIds)
            ->whereNull('mata_kuliah_id')
            ->firstOrFail();

        DB::transaction(function () use ($request, $waliKelas, $presensi): void {
            $statusSebelumnya = $presensi->status;
            $statusTerbaru = $request->validated('status');

            $presensi->update(['status' => $statusTerbaru]);

            PresensiLog::create([
                'presensi_id' => $presensi->id,
                'diubah_oleh' => $waliKelas->id,
                'status_sebelumnya' => $statusSebelumnya,
                'status_terbaru' => $statusTerbaru,
                'alasan_koreksi' => $request->validated('alasan_koreksi'),
            ]);
        });

        return redirect()->route('wali-kelas.dashboard')
            ->with('success', 'Presensi siswa berhasil dikoreksi.');
    }

    public function rekap(RekapKelasRequest $request): View
    {
        $kelas = $this->kelasBinaan($request->user());
        [$tanggalMulai, $tanggalSelesai] = $this->periodeRekap($request);
        $siswa = $kelas->siswaAktif()->orderBy('name')->get();
        $presensiPerSiswa = $this->presensiHarian($siswa->modelKeys(), $tanggalMulai, $tanggalSelesai)
            ->groupBy('user_id');
        $jumlahHariSekolah = $this->jumlahHariSekolah($tanggalMulai, $tanggalSelesai);

        $rekapSiswa = $siswa->map(function (User $siswa) use ($presensiPerSiswa, $jumlahHariSekolah): object {
            $presensis = $presensiPerSiswa->get($siswa->id, collect());
            $hadir = $presensis->whereIn('status', ['Hadir', 'Terlambat'])->count();
            $izin = $presensis->where('status', 'Izin')->count();
            $sakit = $presensis->where('status', 'Sakit')->count();
            $alpha = max(0, $jumlahHariSekolah - $hadir - $izin - $sakit);

            return (object) compact('siswa', 'hadir', 'izin', 'sakit', 'alpha');
        });

        return view('wali-kelas.rekap', compact(
            'kelas',
            'tanggalMulai',
            'tanggalSelesai',
            'rekapSiswa',
        ));
    }

    private function kelasBinaan(User $waliKelas): Kelas
    {
        return $waliKelas->kelasBinaan()->wherePivot('aktif', true)->firstOrFail();
    }

    private function siswaDalamKelas(Kelas $kelas, int $siswaId): User
    {
        return $kelas->siswaAktif()->whereKey($siswaId)->firstOrFail();
    }

    private function presensiHarian(array $siswaIds, Carbon $tanggalMulai, ?Carbon $tanggalSelesai = null)
    {
        return Presensi::query()
            ->whereIn('user_id', $siswaIds)
            ->whereNull('mata_kuliah_id')
            ->when(
                $tanggalSelesai,
                fn ($query) => $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]),
                fn ($query) => $query->whereDate('tanggal', $tanggalMulai),
            )
            ->get();
    }

    private function periodeRekap(RekapKelasRequest $request): array
    {
        $validated = $request->validated();

        if (isset($validated['tanggal_mulai'], $validated['tanggal_selesai'])) {
            return [
                Carbon::createFromFormat('Y-m-d', $validated['tanggal_mulai'])->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $validated['tanggal_selesai'])->endOfDay(),
            ];
        }

        $bulan = isset($validated['bulan'])
            ? Carbon::createFromFormat('Y-m', $validated['bulan'])
            : now();

        return [$bulan->copy()->startOfMonth(), $bulan->copy()->endOfMonth()];
    }

    private function jumlahHariSekolah(Carbon $tanggalMulai, Carbon $tanggalSelesai): int
    {
        return collect(CarbonPeriod::create($tanggalMulai, $tanggalSelesai))
            ->reject(fn (Carbon $tanggal): bool => $tanggal->isWeekend())
            ->count();
    }
}
