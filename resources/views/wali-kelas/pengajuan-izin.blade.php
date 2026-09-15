<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Izin · E-Hadir</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-950 text-gray-100 antialiased">
    <main class="mx-auto max-w-5xl px-6 py-10">
        <a href="{{ route('wali-kelas.dashboard') }}" class="text-sm text-blue-400 hover:text-blue-300">← Kembali ke dasbor</a>
        <div class="mt-4 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm text-blue-400">{{ $kelas->tingkat }} · {{ $kelas->nama }}</p>
                <h1 class="text-3xl font-bold">Verifikasi Izin/Sakit</h1>
            </div>
            <a href="{{ route('wali-kelas.rekap') }}" class="rounded-lg border border-gray-700 px-4 py-2 text-sm hover:bg-gray-900">Rekap Kelas</a>
        </div>

        @if (session('success'))
            <p class="mt-6 rounded-lg border border-emerald-900 bg-emerald-950 px-4 py-3 text-emerald-300">{{ session('success') }}</p>
        @endif

        <section class="mt-8 space-y-4">
            @forelse ($pengajuans as $pengajuan)
                <article class="rounded-xl border border-gray-800 bg-gray-900 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 class="font-semibold">{{ $pengajuan->siswa->name }}</h2>
                            <p class="mt-1 text-sm text-gray-400">{{ $pengajuan->tanggal->translatedFormat('d F Y') }} · {{ ucfirst($pengajuan->jenis) }}</p>
                            <p class="mt-3">{{ $pengajuan->alasan }}</p>
                            @if ($pengajuan->lampiran_path)
                                <p class="mt-3 text-sm text-gray-400">Lampiran: {{ $pengajuan->lampiran_path }}</p>
                            @endif
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <form method="POST" action="{{ route('wali-kelas.pengajuan-izin.approve', $pengajuan) }}" class="grid gap-2">
                                @csrf
                                @method('PATCH')
                                <input name="catatan_verifikasi" maxlength="1000" placeholder="Catatan (opsional)" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2 text-sm">
                                <button class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-medium hover:bg-emerald-500">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('wali-kelas.pengajuan-izin.reject', $pengajuan) }}" class="grid gap-2">
                                @csrf
                                @method('PATCH')
                                <input name="catatan_verifikasi" required maxlength="1000" placeholder="Alasan penolakan" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2 text-sm">
                                <button class="rounded-md bg-red-600 px-3 py-2 text-sm font-medium hover:bg-red-500">Tolak</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <p class="rounded-xl border border-gray-800 bg-gray-900 px-6 py-8 text-center text-gray-400">Tidak ada pengajuan yang menunggu verifikasi.</p>
            @endforelse
        </section>
    </main>
</body>
</html>
