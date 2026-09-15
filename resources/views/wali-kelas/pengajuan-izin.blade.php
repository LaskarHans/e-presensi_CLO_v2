<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Izin · E-Hadir</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-950 text-gray-100 antialiased">
    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-10">
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
                <article class="rounded-xl border border-gray-800 bg-gray-900 p-5 sm:p-6">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="font-semibold">{{ $pengajuan->siswa->name }}</h2>
                                <span class="rounded-full bg-amber-500/10 px-2.5 py-1 text-xs font-medium text-amber-400">Menunggu</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-400">{{ $pengajuan->tanggal->translatedFormat('d F Y') }} · {{ ucfirst($pengajuan->jenis) }}</p>
                            <p class="mt-3 leading-6 text-gray-200">{{ $pengajuan->alasan }}</p>
                            @if ($pengajuan->lampiran_path)
                                <a href="{{ asset('storage/'.$pengajuan->lampiran_path) }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-blue-400 hover:text-blue-300">Buka file bukti <span aria-hidden="true">↗</span></a>
                            @else
                                <p class="mt-4 text-sm text-gray-500">Siswa tidak melampirkan file bukti.</p>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-3 sm:min-w-52">
                            <button type="button" onclick="document.getElementById('approve-{{ $pengajuan->id }}').showModal()" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium hover:bg-emerald-500">Setujui</button>
                            <button type="button" onclick="document.getElementById('reject-{{ $pengajuan->id }}').showModal()" class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium hover:bg-red-500">Tolak</button>
                        </div>
                    </div>
                </article>

                <dialog id="approve-{{ $pengajuan->id }}" class="w-[calc(100%-2rem)] max-w-md rounded-xl border border-gray-700 bg-gray-900 p-0 text-gray-100 backdrop:bg-black/70">
                    <form method="POST" action="{{ route('wali-kelas.pengajuan-izin.approve', $pengajuan) }}" class="grid gap-5 p-6">
                        @csrf
                        @method('PATCH')
                        <div><h2 class="text-lg font-semibold">Setujui pengajuan?</h2><p class="mt-1 text-sm text-gray-400">Presensi {{ $pengajuan->siswa->name }} akan diperbarui menjadi {{ ucfirst($pengajuan->jenis) }}.</p></div>
                        <label class="grid gap-2 text-sm">Catatan verifikasi <span class="text-gray-500">(opsional)</span><textarea name="catatan_verifikasi" maxlength="1000" rows="3" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2" placeholder="Contoh: Surat dokter telah diperiksa."></textarea></label>
                        <div class="flex justify-end gap-3"><button type="button" onclick="this.closest('dialog').close()" class="rounded-lg border border-gray-700 px-4 py-2 text-sm hover:bg-gray-800">Batal</button><button class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium hover:bg-emerald-500">Setujui</button></div>
                    </form>
                </dialog>

                <dialog id="reject-{{ $pengajuan->id }}" class="w-[calc(100%-2rem)] max-w-md rounded-xl border border-gray-700 bg-gray-900 p-0 text-gray-100 backdrop:bg-black/70">
                    <form method="POST" action="{{ route('wali-kelas.pengajuan-izin.reject', $pengajuan) }}" class="grid gap-5 p-6">
                        @csrf
                        @method('PATCH')
                        <div><h2 class="text-lg font-semibold">Tolak pengajuan?</h2><p class="mt-1 text-sm text-gray-400">Sertakan alasan agar siswa mengetahui tindak lanjutnya.</p></div>
                        <label class="grid gap-2 text-sm">Alasan penolakan<textarea name="catatan_verifikasi" required maxlength="1000" rows="3" class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2" placeholder="Jelaskan alasan penolakan."></textarea></label>
                        <div class="flex justify-end gap-3"><button type="button" onclick="this.closest('dialog').close()" class="rounded-lg border border-gray-700 px-4 py-2 text-sm hover:bg-gray-800">Batal</button><button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium hover:bg-red-500">Tolak</button></div>
                    </form>
                </dialog>
            @empty
                <p class="rounded-xl border border-gray-800 bg-gray-900 px-6 py-8 text-center text-gray-400">Tidak ada pengajuan yang menunggu verifikasi.</p>
            @endforelse
        </section>
    </main>
</body>
</html>
