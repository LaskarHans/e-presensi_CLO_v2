<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Mata Kuliah · E-Hadir</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen antialiased">

<nav class="sticky top-0 z-30 bg-gray-950/90 backdrop-blur border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center gap-3">
        <a href="{{ route('presensi.index') }}" class="flex items-center gap-2 shrink-0">
            <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <span class="font-semibold text-lg">E-Hadir</span>
        </a>
        <span class="text-gray-700">/</span>
        <span class="text-sm text-gray-100">Kelola Mata Kuliah</span>
        <a href="{{ route('presensi.index') }}" class="ml-auto text-sm text-gray-400 hover:text-gray-200 transition">← Kembali ke Presensi</a>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 py-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Kelola Mata Kuliah</h1>
            <p class="text-gray-500 mt-1">Tambah, ubah, atau hapus jadwal perkuliahan lewat form — tidak perlu edit kode lagi.</p>
        </div>
        <a href="{{ route('mata-kuliah.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 transition text-white font-medium px-5 py-2.5 rounded-lg shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Mata Kuliah
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm rounded-lg p-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('mata-kuliah.index') }}" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari kode, nama, atau dosen..."
               class="flex-1 bg-gray-900 border border-gray-800 rounded-lg px-3.5 py-2.5 text-sm placeholder:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
        <select name="hari" onchange="this.form.submit()"
                class="bg-gray-900 border border-gray-800 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
            <option value="">Semua Hari</option>
            @foreach ($urutanHari as $hari)
                <option value="{{ $hari }}" {{ $hariFilter === $hari ? 'selected' : '' }}>{{ $hari }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-800 hover:bg-gray-700 transition text-sm font-medium px-4 py-2.5 rounded-lg">
            Terapkan
        </button>
        @if ($cari !== '' || $hariFilter !== '')
            <a href="{{ route('mata-kuliah.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition self-center px-2">
                Reset
            </a>
        @endif
    </form>

    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-800">
                        <th class="px-5 py-3 font-medium">Kode</th>
                        <th class="px-5 py-3 font-medium">Mata Kuliah</th>
                        <th class="px-5 py-3 font-medium">Dosen</th>
                        <th class="px-5 py-3 font-medium">Hari</th>
                        <th class="px-5 py-3 font-medium">Jam</th>
                        <th class="px-5 py-3 font-medium">Ruang</th>
                        <th class="px-5 py-3 font-medium">Presensi</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($mataKuliahs as $mk)
                        <tr class="hover:bg-gray-800/40 transition">
                            <td class="px-5 py-3.5 font-mono text-xs text-gray-400">{{ $mk->kode }}</td>
                            <td class="px-5 py-3.5 font-medium">{{ $mk->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-400">{{ $mk->dosen ?: '—' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-1 rounded-md text-xs font-medium bg-blue-500/10 text-blue-400">{{ $mk->hari }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">
                                {{ \Illuminate\Support\Carbon::parse($mk->jam_mulai)->format('H:i') }}–{{ \Illuminate\Support\Carbon::parse($mk->jam_selesai)->format('H:i') }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-400">{{ $mk->ruang ?: '—' }}</td>
                            <td class="px-5 py-3.5 text-gray-400">{{ $mk->presensis_count }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('mata-kuliah.edit', $mk) }}"
                                       class="px-3 py-1.5 rounded-md text-xs font-medium bg-gray-800 hover:bg-gray-700 text-gray-200 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('mata-kuliah.destroy', $mk) }}" method="POST"
                                          onsubmit="return confirm('Hapus {{ addslashes($mk->nama) }} ({{ $mk->hari }} {{ \Illuminate\Support\Carbon::parse($mk->jam_mulai)->format('H:i') }})?{{ $mk->presensis_count > 0 ? ' '.$mk->presensis_count.' catatan presensi terkait juga akan terhapus.' : '' }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-md text-xs font-medium bg-red-500/10 hover:bg-red-500/20 text-red-400 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-gray-500">
                                @if ($cari !== '' || $hariFilter !== '')
                                    Tidak ada mata kuliah yang cocok dengan filter ini.
                                @else
                                    Belum ada mata kuliah. Klik "Tambah Mata Kuliah" untuk membuat jadwal pertama.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-xs text-gray-600">{{ $mataKuliahs->count() }} mata kuliah ditampilkan.</p>

</main>
</body>
</html>
