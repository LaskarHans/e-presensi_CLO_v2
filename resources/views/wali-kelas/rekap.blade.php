<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Kelas · E-Hadir</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-950 text-gray-100 antialiased">
    <main class="mx-auto max-w-5xl px-6 py-10">
        <a href="{{ route('wali-kelas.dashboard') }}" class="text-sm text-blue-400 hover:text-blue-300">← Kembali ke dasbor</a>
        <div class="mt-4">
            <p class="text-sm text-blue-400">{{ $kelas->tingkat }} · {{ $kelas->nama }}</p>
            <h1 class="text-3xl font-bold">Rekap Kehadiran Kelas</h1>
        </div>

        <form method="GET" action="{{ route('wali-kelas.rekap') }}" class="mt-8 grid gap-4 rounded-xl border border-gray-800 bg-gray-900 p-5 md:grid-cols-4">
            <label class="grid gap-2 text-sm">Bulan
                <input type="month" name="bulan" value="{{ request('bulan', $tanggalMulai->format('Y-m')) }}" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2">
            </label>
            <label class="grid gap-2 text-sm">Tanggal mulai
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2">
            </label>
            <label class="grid gap-2 text-sm">Tanggal selesai
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2">
            </label>
            <div class="flex items-end">
                <button class="w-full rounded-md bg-blue-600 px-4 py-2 font-medium hover:bg-blue-500">Terapkan Filter</button>
            </div>
        </form>

        <p class="mt-5 text-sm text-gray-400">Periode {{ $tanggalMulai->translatedFormat('d F Y') }} sampai {{ $tanggalSelesai->translatedFormat('d F Y') }}</p>
        <section class="mt-4 overflow-hidden rounded-xl border border-gray-800 bg-gray-900">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-900 text-gray-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">Siswa</th>
                            <th class="px-6 py-3 font-medium">Hadir</th>
                            <th class="px-6 py-3 font-medium">Izin</th>
                            <th class="px-6 py-3 font-medium">Sakit</th>
                            <th class="px-6 py-3 font-medium">Alpha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse ($rekapSiswa as $rekap)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $rekap->siswa->name }}</td>
                                <td class="px-6 py-4">{{ $rekap->hadir }}</td>
                                <td class="px-6 py-4">{{ $rekap->izin }}</td>
                                <td class="px-6 py-4">{{ $rekap->sakit }}</td>
                                <td class="px-6 py-4">{{ $rekap->alpha }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada siswa aktif di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
