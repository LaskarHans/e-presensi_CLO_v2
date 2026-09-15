<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MataKuliahController extends Controller
{
    /**
     * Urutan hari yang dipakai untuk sorting & pilihan dropdown.
     * TODO: batasi akses controller ini ke role admin/dosen setelah sistem login tersedia.
     */
    private array $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index(Request $request): View
    {
        $cari = trim((string) $request->query('cari', ''));
        $hariFilter = $request->query('hari', '');

        $query = MataKuliah::query()->withCount('presensis');

        if ($cari !== '') {
            $query->where(function ($q) use ($cari) {
                $q->where('kode', 'like', "%{$cari}%")
                    ->orWhere('nama', 'like', "%{$cari}%")
                    ->orWhere('dosen', 'like', "%{$cari}%");
            });
        }

        if ($hariFilter !== '' && in_array($hariFilter, $this->urutanHari, true)) {
            $query->where('hari', $hariFilter);
        }

        $urutanCase = "CASE hari
            WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3
            WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6
            WHEN 'Minggu' THEN 7 ELSE 8 END";

        $mataKuliahs = $query->orderByRaw($urutanCase)
            ->orderBy('jam_mulai')
            ->get();

        return view('mata-kuliah.index', [
            'mataKuliahs' => $mataKuliahs,
            'urutanHari' => $this->urutanHari,
            'cari' => $cari,
            'hariFilter' => $hariFilter,
        ]);
    }

    public function create(): View
    {
        return view('mata-kuliah.create', [
            'mataKuliah' => new MataKuliah(),
            'urutanHari' => $this->urutanHari,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request);

        $bentrok = $this->cekBentrok($data);
        if ($bentrok !== []) {
            return back()->withErrors(['bentrok' => $bentrok])->withInput();
        }

        MataKuliah::create($data);

        if ($request->input('aksi') === 'simpan_lagi') {
            return redirect()->route('mata-kuliah.create')
                ->with('success', "Mata kuliah \"{$data['nama']}\" ({$data['hari']}) berhasil ditambahkan. Silakan tambah jadwal berikutnya.");
        }

        return redirect()->route('mata-kuliah.index')
            ->with('success', "Mata kuliah \"{$data['nama']}\" berhasil ditambahkan.");
    }

    public function edit(MataKuliah $mataKuliah): View
    {
        $mataKuliah->loadCount('presensis');

        $jumlahSaudara = MataKuliah::where('kode', $mataKuliah->kode)
            ->where('id', '!=', $mataKuliah->id)
            ->count();

        return view('mata-kuliah.edit', [
            'mataKuliah' => $mataKuliah,
            'urutanHari' => $this->urutanHari,
            'jumlahSaudara' => $jumlahSaudara,
        ]);
    }

    public function update(Request $request, MataKuliah $mataKuliah): RedirectResponse
    {
        $data = $this->validasi($request);

        $bentrok = $this->cekBentrok($data, $mataKuliah->id);
        if ($bentrok !== []) {
            return back()->withErrors(['bentrok' => $bentrok])->withInput();
        }

        $kodeLama = $mataKuliah->kode;
        $mataKuliah->update($data);

        $infoTambahan = '';
        if ($request->boolean('terapkan_semua')) {
            $jumlah = MataKuliah::where('kode', $kodeLama)
                ->where('id', '!=', $mataKuliah->id)
                ->update([
                    'kode' => $data['kode'],
                    'nama' => $data['nama'],
                    'dosen' => $data['dosen'],
                ]);

            if ($jumlah > 0) {
                $infoTambahan = " Kode, nama, dan dosen juga diperbarui di {$jumlah} jadwal lain yang sebelumnya berkode \"{$kodeLama}\" (hari/jam/ruangnya tidak berubah).";
            }
        }

        return redirect()->route('mata-kuliah.index')
            ->with('success', "Perubahan pada \"{$data['nama']}\" berhasil disimpan.".$infoTambahan);
    }

    public function destroy(MataKuliah $mataKuliah): RedirectResponse
    {
        $nama = $mataKuliah->nama;
        $mataKuliah->delete();

        return redirect()->route('mata-kuliah.index')
            ->with('success', "Mata kuliah \"{$nama}\" beserta riwayat presensinya telah dihapus.");
    }

    /**
     * Validasi form tambah/edit mata kuliah.
     *
     * @return array<string, mixed>
     */
    private function validasi(Request $request): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:150'],
            'dosen' => ['nullable', 'string', 'max:100'],
            'ruang' => ['nullable', 'string', 'max:50'],
            'hari' => ['required', Rule::in($this->urutanHari)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ], [
            'kode.required' => 'Kode mata kuliah wajib diisi.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'hari.required' => 'Pilih hari perkuliahan.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);
    }

    /**
     * Cek apakah jadwal baru bentrok ruang/dosen dengan jadwal lain di hari yang sama.
     *
     * @param  array<string, mixed>  $data
     * @return array<int, string>
     */
    private function cekBentrok(array $data, ?int $ignoreId = null): array
    {
        $kandidat = MataKuliah::where('hari', $data['hari'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->get();

        $pesan = [];

        foreach ($kandidat as $lain) {
            $jamLain = substr($lain->jam_mulai, 0, 5).'–'.substr($lain->jam_selesai, 0, 5);

            if (! empty($data['ruang']) && $lain->ruang === $data['ruang']) {
                $pesan[] = "Ruang \"{$data['ruang']}\" sudah dipakai {$lain->nama} pada {$data['hari']} {$jamLain}.";
            }

            if (! empty($data['dosen']) && $lain->dosen === $data['dosen']) {
                $pesan[] = "Dosen \"{$data['dosen']}\" sudah mengajar {$lain->nama} pada {$data['hari']} {$jamLain}.";
            }
        }

        return $pesan;
    }
}
