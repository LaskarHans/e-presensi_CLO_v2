<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengajuanIzinRequest;
use App\Http\Requests\UpdateNamaSiswaRequest;
use App\Models\MataKuliah;
use App\Models\PengajuanIzin;
use App\Models\Presensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PresensiController extends Controller
{
    private array $namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    public function index(Request $request): View
    {
        Carbon::setLocale('id');

        $user = $request->user();
        $today = Carbon::today();
        $hariIni = $this->namaHari[$today->dayOfWeek];
        $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $jadwalMingguan = MataKuliah::orderBy('jam_mulai')->get()->groupBy('hari');
        $kelasHariIni = MataKuliah::where('hari', $hariIni)->orderBy('jam_mulai')->get();

        $presensiHariIni = Presensi::where('user_id', $user?->id)
            ->whereDate('tanggal', $today)
            ->get()
            ->keyBy('mata_kuliah_id');

        $riwayat = Presensi::with('mataKuliah')
            ->where('user_id', $user?->id)
            ->get();
        $pengajuanIzins = PengajuanIzin::query()
            ->where('siswa_id', $user?->id)
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $totalPertemuan = $riwayat->count();
        $totalHadir = $riwayat->where('status', 'Hadir')->count();
        $totalTerlambat = $riwayat->where('status', 'Terlambat')->count();
        $totalTidakHadir = $riwayat->whereIn('status', ['Tidak Hadir', 'Izin', 'Sakit'])->count();
        $tingkatKehadiran = $totalPertemuan > 0
            ? (int) round((($totalHadir + $totalTerlambat) / $totalPertemuan) * 100)
            : 0;

        $rekapPerMatkul = $riwayat->groupBy(fn ($item) => $item->mataKuliah?->kode)
            ->filter(fn ($items, $kode) => $kode !== null)
            ->map(function ($items) {
                $matkul = $items->first()->mataKuliah;
                $total = $items->count();
                $hadir = $items->whereIn('status', ['Hadir', 'Terlambat'])->count();
                $persentase = $total > 0 ? (int) round(($hadir / $total) * 100) : 0;

                return (object) [
                    'kode' => $matkul?->kode,
                    'nama' => $matkul?->nama,
                    'hadir' => $hadir,
                    'total' => $total,
                    'persentase' => $persentase,
                    'keterangan' => $persentase >= 90 ? 'Aman' : ($persentase >= 75 ? 'Memuaskan' : 'Berisiko'),
                ];
            })
            ->sortBy('kode')
            ->values();

        $matkulBerisiko = $rekapPerMatkul->where('persentase', '<', 75)->count();
        $aktivitasTerbaru = $riwayat->sortByDesc(fn ($item) => $item->tanggal->format('Y-m-d').' '.$item->waktu_masuk)->take(10);
        $ditandaiHariIni = $presensiHariIni->count();

        $statusColor = [
            'Hadir' => 'bg-emerald-500/10 text-emerald-400',
            'Terlambat' => 'bg-amber-500/10 text-amber-400',
            'Tidak Hadir' => 'bg-red-500/10 text-red-400',
            'Izin' => 'bg-blue-500/10 text-blue-400',
            'Sakit' => 'bg-purple-500/10 text-purple-400',
        ];

        return view('presensi', compact(
            'user', 'today', 'hariIni', 'urutanHari', 'jadwalMingguan', 'kelasHariIni', 'presensiHariIni',
            'rekapPerMatkul', 'aktivitasTerbaru', 'riwayat', 'totalPertemuan', 'totalHadir', 'totalTerlambat',
            'totalTidakHadir', 'tingkatKehadiran', 'matkulBerisiko', 'ditandaiHariIni', 'statusColor', 'pengajuanIzins'
        ));
    }

    public function updateNama(UpdateNamaSiswaRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'name' => trim($request->validated('name')),
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Nama Anda berhasil diperbarui. Wali kelas akan melihat perubahan ini.');
    }

    public function storePengajuanIzin(StorePengajuanIzinRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $lampiranPath = $request->file('lampiran')->store('pengajuan-izin/'.$request->user()->id, 'public');

        PengajuanIzin::create([
            'siswa_id' => $request->user()->id,
            'tanggal' => $validated['tanggal'],
            'jenis' => $validated['jenis'],
            'alasan' => $validated['alasan'],
            'lampiran_path' => $lampiranPath,
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Pengajuan '.$validated['jenis'].' berhasil dikirim dan menunggu verifikasi.')
            ->with('active_tab', 'izin');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
        ]);

        $user = $request->user();
        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);

        $sudahAda = Presensi::where('user_id', $user?->id)
            ->where('mata_kuliah_id', $mataKuliah->id)
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'success' => false,
                'message' => 'Kehadiran untuk kelas ini sudah ditandai hari ini.',
            ], 422);
        }

        $sekarang = now();
        $batasTerlambat = Carbon::parse($mataKuliah->jam_mulai)->addMinutes(15);
        $status = $sekarang->format('H:i:s') <= $batasTerlambat->format('H:i:s') ? 'Hadir' : 'Terlambat';

        Presensi::create([
            'user_id' => $user?->id,
            'mata_kuliah_id' => $mataKuliah->id,
            'tanggal' => today(),
            'status' => $status,
            'waktu_masuk' => $sekarang->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Presensi {$mataKuliah->nama} berhasil dicatat sebagai {$status}!",
            'status' => $status,
            'waktu_masuk' => $sekarang->format('H:i'),
        ]);
    }
}
