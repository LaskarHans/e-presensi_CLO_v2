<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasbor Wali Kelas · E-Hadir</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-950 text-gray-100 antialiased">
    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-10">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-blue-400">Wali Kelas</p>
                <h1 class="text-3xl font-bold">Dasbor Kehadiran Kelas</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-lg border border-gray-700 px-4 py-2 text-sm hover:bg-gray-900">Keluar</button>
            </form>
        </div>

        <div class="mt-8 flex flex-wrap gap-3 text-sm">
            <a href="{{ route('wali-kelas.pengajuan-izin.index') }}" class="rounded-lg bg-blue-600 px-4 py-2 font-medium hover:bg-blue-500">Verifikasi Izin/Sakit</a>
            <a href="{{ route('wali-kelas.rekap') }}" class="rounded-lg border border-gray-700 px-4 py-2 font-medium hover:bg-gray-900">Rekap Kelas</a>
        </div>

        <section class="mt-8">
            <p class="text-sm text-gray-400">{{ $kelas->tingkat }} · {{ $kelas->nama }} · {{ now()->translatedFormat('l, d F Y') }}</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($ringkasan as $status => $jumlah)
                    <article class="rounded-xl border border-gray-800 bg-gray-900 p-5">
                        <p class="text-sm text-gray-400">{{ $status }}</p>
                        <p class="mt-1 text-3xl font-bold">{{ $jumlah }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mt-8 overflow-hidden rounded-xl border border-gray-800 bg-gray-900">
            <div class="border-b border-gray-800 px-6 py-4">
                <h2 class="font-semibold">Kehadiran Siswa Hari Ini</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-900 text-gray-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">Siswa</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Koreksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse ($daftarSiswa as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium">{{ $item->siswa->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $item->siswa->nomor_induk }}</p>
                                </td>
                                <td class="px-6 py-4">{{ $item->status }}</td>
                                <td class="px-6 py-4">
                                    @if ($item->presensi)
                                        <button type="button" onclick="document.getElementById('koreksi-{{ $item->presensi->id }}').showModal()" class="text-sm font-medium text-blue-400 hover:text-blue-300">Koreksi status</button>
                                    @else
                                        <span class="text-gray-500">Belum ada catatan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400">Belum ada siswa aktif di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @foreach ($daftarSiswa as $item)
            @if ($item->presensi)
                <dialog id="koreksi-{{ $item->presensi->id }}" class="w-[calc(100%-2rem)] max-w-md rounded-xl border border-gray-700 bg-gray-900 p-0 text-gray-100 backdrop:bg-black/70">
                    <form method="POST" action="{{ route('wali-kelas.presensi.koreksi', $item->presensi) }}" class="grid gap-5 p-6">
                        @csrf
                        @method('PATCH')
                        <div>
                            <h2 class="text-lg font-semibold">Koreksi presensi</h2>
                            <p class="mt-1 text-sm text-gray-400">{{ $item->siswa->name }} · status saat ini: {{ $item->status }}</p>
                        </div>
                        <label class="grid gap-2 text-sm">Status terbaru
                            <select name="status" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2">
                                @foreach (['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha'] as $status)
                                    <option value="{{ $status }}" @selected($item->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="grid gap-2 text-sm">Alasan koreksi
                            <textarea name="alasan_koreksi" required maxlength="1000" rows="3" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2" placeholder="Wajib diisi untuk jejak audit."></textarea>
                        </label>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="this.closest('dialog').close()" class="rounded-lg border border-gray-700 px-4 py-2 text-sm hover:bg-gray-800">Batal</button>
                            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium hover:bg-blue-500">Simpan koreksi</button>
                        </div>
                    </form>
                </dialog>
            @endif
        @endforeach
    </main>
</body>
</html>
