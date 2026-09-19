<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Hadir · Presensi Kelas</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-950 text-gray-100 min-h-screen antialiased">

    <!-- NAVBAR -->
    <nav class="sticky top-0 z-30 bg-gray-950/90 backdrop-blur border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 shrink-0">
                <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span class="font-semibold text-lg">E-Hadir</span>
            </div>

            <div class="hidden md:flex items-center gap-1 bg-gray-900/60 rounded-lg p-1">
                <button data-tab="dasbor"
                    class="tab-btn relative px-4 py-1.5 rounded-md text-sm font-medium text-gray-400 transition">Dasbor</button>
                <button data-tab="tandai"
                    class="tab-btn relative px-4 py-1.5 rounded-md text-sm font-medium text-gray-400 transition">
                    Tandai Kehadiran
                    @if ($ditandaiHariIni < $kelasHariIni->count())
                        <span class="absolute top-1 right-1.5 w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    @endif
                </button>
                <button data-tab="riwayat"
                    class="tab-btn relative px-4 py-1.5 rounded-md text-sm font-medium text-gray-400 transition">Riwayat
                    Saya</button>
                <button data-tab="izin"
                    class="tab-btn relative px-4 py-1.5 rounded-md text-sm font-medium text-gray-400 transition">Izin/Sakit</button>
                <button data-tab="jadwal"
                    class="tab-btn relative px-4 py-1.5 rounded-md text-sm font-medium text-gray-400 transition">Jadwal</button>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                @if ($user->isWaliKelas())
                    <a href="{{ route('wali-kelas.mata-kuliah.index') }}" title="Kelola Mata Kuliah"
                        class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-gray-100 border border-gray-800 hover:border-gray-700 rounded-lg px-3 py-2 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.559-.94-1.108v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Kelola Mata Kuliah
                    </a>
                @endif
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium leading-tight">{{ $user->name ?? 'Tamu' }}</p>
                    <p class="text-xs text-gray-500 leading-tight">{{ $user->nim ?? '-' }}</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-sm font-semibold">
                    {{ collect(explode(' ', $user->name ?? 'T U'))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="border border-gray-800 hover:border-gray-700 rounded-lg px-3 py-2 text-xs font-medium text-gray-400 hover:text-gray-100 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab mobile -->
        <div class="md:hidden flex overflow-x-auto gap-1 px-4 pb-2 bg-gray-950/90">
            <button data-tab="dasbor"
                class="tab-btn px-3 py-1.5 rounded-md text-xs font-medium text-gray-400 whitespace-nowrap">Dasbor</button>
            <button data-tab="tandai"
                class="tab-btn px-3 py-1.5 rounded-md text-xs font-medium text-gray-400 whitespace-nowrap">Tandai
                Kehadiran</button>
            <button data-tab="riwayat"
                class="tab-btn px-3 py-1.5 rounded-md text-xs font-medium text-gray-400 whitespace-nowrap">Riwayat
                Saya</button>
            <button data-tab="izin"
                class="tab-btn px-3 py-1.5 rounded-md text-xs font-medium text-gray-400 whitespace-nowrap">Izin/Sakit</button>
            <button data-tab="jadwal"
                class="tab-btn px-3 py-1.5 rounded-md text-xs font-medium text-gray-400 whitespace-nowrap">Jadwal</button>
            @if ($user->isWaliKelas())
                <a href="{{ route('wali-kelas.mata-kuliah.index') }}"
                    class="px-3 py-1.5 rounded-md text-xs font-medium text-gray-400 whitespace-nowrap border border-gray-800">Kelola
                    Mata Kuliah</a>
            @endif
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 sm:py-8">

        @if (session('success'))
            <p class="mb-6 rounded-lg border border-emerald-900 bg-emerald-950 px-4 py-3 text-sm text-emerald-300">
                {{ session('success') }}</p>
        @endif

        {{-- =============== DASBOR =============== --}}
        <section id="tab-dasbor" class="tab-section space-y-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500">{{ $today->translatedFormat('l, j F Y') }}</p>
                    <h1 class="text-2xl font-bold mt-1">
                        Selamat
                        {{ now()->hour < 11 ? 'pagi' : (now()->hour < 15 ? 'siang' : (now()->hour < 18 ? 'sore' : 'malam')) }},
                        {{ explode(' ', $user->name ?? 'Pengguna')[0] }}
                    </h1>
                    <p class="text-gray-500 mt-1">{{ $user->prodi ?? '-' }} @if ($user->angkatan)
                            · Angkatan {{ $user->angkatan }}
                        @endif
                    </p>
                </div>
                <button type="button" onclick="switchTab('tandai')"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 transition text-white font-medium px-5 py-2.5 rounded-lg shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tandai Kehadiran Hari Ini
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 lg:col-span-2">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm text-gray-400">Nama tampilan</p>
                            <h2 class="mt-1 text-xl font-semibold text-white">{{ $user->name }}</h2>
                        </div>
                        <button type="button" onclick="document.getElementById('edit-nama').classList.toggle('hidden')"
                            class="rounded-lg border border-gray-700 px-3 py-2 text-xs font-medium text-gray-200 hover:bg-gray-800">
                            Ubah Nama
                        </button>
                    </div>

                    <form id="edit-nama" method="POST" action="{{ route('siswa.nama.update') }}" class="mt-4 hidden">
                        @csrf
                        @method('PATCH')
                        <label class="grid gap-2 text-sm text-gray-300">
                            Nama sesuai keinginanmu
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                maxlength="100" required
                                class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2.5 text-white outline-none focus:border-blue-500"
                                placeholder="Masukkan nama baru">
                        </label>
                        <div class="mt-3 flex justify-end">
                            <button type="submit"
                                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium hover:bg-blue-500">Simpan
                                Nama</button>
                        </div>
                    </form>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <div
                        class="w-9 h-9 rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-blue-400">{{ $tingkatKehadiran }}%</p>
                    <p class="text-sm text-gray-400 mt-1">Tingkat Kehadiran</p>
                    <p class="text-xs text-gray-600">Rata-rata semester</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <div
                        class="w-9 h-9 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-emerald-400">{{ $totalHadir + $totalTerlambat }}</p>
                    <p class="text-sm text-gray-400 mt-1">Kelas Dihadiri</p>
                    <p class="text-xs text-gray-600">dari {{ $totalPertemuan }} total</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <div
                        class="w-9 h-9 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-purple-400">{{ $ditandaiHariIni }}</p>
                    <p class="text-sm text-gray-400 mt-1">Ditandai Hari Ini</p>
                    <p class="text-xs text-gray-600">dari {{ $kelasHariIni->count() }} kelas</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <div class="w-9 h-9 rounded-full bg-red-500/10 text-red-400 flex items-center justify-center mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-red-400">{{ $matkulBerisiko }}</p>
                    <p class="text-sm text-gray-400 mt-1">Matkul Berisiko</p>
                    <p class="text-xs text-gray-600">Di bawah ambang 75%</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h2 class="font-semibold mb-5">Kehadiran per Mata Kuliah</h2>
                    <div class="space-y-5">
                        @forelse($rekapPerMatkul as $r)
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1.5">
                                    <p class="font-medium">{{ $r->nama }} <span
                                            class="text-gray-500 font-mono text-xs">{{ $r->kode }}</span></p>
                                    <span class="text-gray-500">{{ $r->hadir }}/{{ $r->total }}</span>
                                </div>
                                <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $r->persentase >= 90 ? 'bg-emerald-500' : ($r->persentase >= 75 ? 'bg-amber-500' : 'bg-red-500') }}"
                                        style="width: {{ $r->persentase }}%"></div>
                                </div>
                                <p
                                    class="text-right text-xs mt-1 {{ $r->persentase >= 90 ? 'text-emerald-400' : ($r->persentase >= 75 ? 'text-amber-400' : 'text-red-400') }}">
                                    {{ $r->persentase }}%</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada data kehadiran.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h2 class="font-semibold mb-5">Kelas Hari Ini</h2>
                    <div class="space-y-3">
                        @forelse($kelasHariIni as $k)
                            <div class="border-l-2 border-blue-500 bg-gray-800/40 rounded-r-lg px-4 py-3">
                                <p class="font-medium text-sm">{{ $k->nama }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($k->jam_mulai)->format('H.i') }} –
                                    {{ \Carbon\Carbon::parse($k->jam_selesai)->format('H.i') }} · {{ $k->ruang }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada kelas hari ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="font-semibold mb-5">Aktivitas Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-gray-500 uppercase border-b border-gray-800">
                                <th class="pb-3 font-medium">Tanggal</th>
                                <th class="pb-3 font-medium">Mata Kuliah</th>
                                <th class="pb-3 font-medium">Kode</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium">Waktu Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/60">
                            @forelse($aktivitasTerbaru as $a)
                                <tr>
                                    <td class="py-3 text-gray-400">{{ $a->tanggal->translatedFormat('D, j M') }}</td>
                                    <td class="py-3 font-medium">{{ $a->mataKuliah->nama ?? '-' }}</td>
                                    <td class="py-3 text-gray-500 font-mono text-xs">{{ $a->mataKuliah->kode ?? '-' }}
                                    </td>
                                    <td class="py-3">
                                        <span
                                            class="px-2.5 py-1 rounded-md text-xs font-medium {{ $statusColor[$a->status] ?? 'bg-gray-500/10 text-gray-400' }}">●
                                            {{ $a->status }}</span>
                                    </td>
                                    <td class="py-3 text-gray-400 font-mono text-xs">
                                        {{ $a->waktu_masuk ? \Carbon\Carbon::parse($a->waktu_masuk)->format('H.i') : '–' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500">Belum ada aktivitas
                                        presensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- =============== TANDAI KEHADIRAN =============== --}}
        <section id="tab-tandai" class="tab-section hidden space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Tandai Kehadiran</h1>
                    <p class="text-gray-500 mt-1">
                        {{ $today->translatedFormat('l, j F Y') }} ·
                        <span id="progressLabel">{{ $ditandaiHariIni }} dari {{ $kelasHariIni->count() }}
                            ditandai</span>
                    </p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl px-6 py-3 text-right">
                    <p id="liveClock" class="text-2xl font-bold font-mono">{{ now()->format('H.i') }}</p>
                    <p class="text-xs text-gray-500">Waktu sekarang</p>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="font-medium">Progres hari ini</span>
                    <span id="progressFraction"
                        class="text-gray-400">{{ $ditandaiHariIni }}/{{ $kelasHariIni->count() }}</span>
                </div>
                <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                    <div id="progressBar" class="h-full bg-blue-500 rounded-full transition-all"
                        style="width: {{ $kelasHariIni->count() > 0 ? round(($ditandaiHariIni / $kelasHariIni->count()) * 100) : 0 }}%">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Hadir (tepat waktu)</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Terlambat (&gt;15 menit setelah mulai)</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-gray-500"></span> Tidak
                    hadir (tidak menekan tombol)</span>
            </div>

            <div class="space-y-4">
                @forelse($kelasHariIni as $i => $k)
                    @php $presensi = $presensiHariIni->get($k->id); @endphp
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 flex items-center justify-between gap-4 flex-wrap"
                        data-kelas-card="{{ $k->id }}">
                        <div class="flex items-start gap-4">
                            <span
                                class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center text-xs text-gray-400 shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <p class="font-semibold">{{ $k->nama }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $k->kode }}</p>
                                <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-3 flex-wrap">
                                    <span>🕐 {{ \Carbon\Carbon::parse($k->jam_mulai)->format('H.i') }} –
                                        {{ \Carbon\Carbon::parse($k->jam_selesai)->format('H.i') }}</span>
                                    <span>📍 {{ $k->ruang }}</span>
                                    @if ($k->dosen)
                                        <span>{{ $k->dosen }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($presensi)
                            <span
                                class="px-3 py-1.5 rounded-md text-xs font-medium {{ $statusColor[$presensi->status] ?? '' }}">●
                                {{ $presensi->status }}</span>
                        @else
                            <button
                                class="btn-tandai px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 hover:bg-blue-500 transition text-white"
                                data-id="{{ $k->id }}">
                                Tandai Hadir
                            </button>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Tidak ada jadwal kelas hari ini.</p>
                @endforelse
            </div>
        </section>

        {{-- =============== PENGAJUAN IZIN / SAKIT =============== --}}
        <section id="tab-izin" class="tab-section hidden space-y-6">
            <div>
                <h1 class="text-2xl font-bold">Pengajuan Izin/Sakit</h1>
                <p class="mt-1 text-gray-500">Kirim alasan dan file bukti agar wali kelas dapat melakukan verifikasi.
                </p>
            </div>

            @if ($errors->any())
                <div class="rounded-xl border border-red-900 bg-red-950 p-4 text-sm text-red-200">
                    <p class="font-medium">Pengajuan belum dapat dikirim.</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-5">
                <form method="POST" action="{{ route('siswa.pengajuan-izin.store') }}"
                    enctype="multipart/form-data"
                    class="grid gap-5 rounded-xl border border-gray-800 bg-gray-900 p-5 sm:p-6 lg:col-span-3">
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <label class="grid gap-2 text-sm">Tanggal
                            <input type="date" name="tanggal" max="{{ today()->format('Y-m-d') }}"
                                value="{{ old('tanggal', today()->format('Y-m-d')) }}" required
                                class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2.5">
                        </label>
                        <label class="grid gap-2 text-sm">Jenis pengajuan
                            <select name="jenis" required
                                class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2.5">
                                <option value="izin" @selected(old('jenis') === 'izin')>Izin</option>
                                <option value="sakit" @selected(old('jenis') === 'sakit')>Sakit</option>
                            </select>
                        </label>
                    </div>
                    <label class="grid gap-2 text-sm">Alasan
                        <textarea name="alasan" required maxlength="1000" rows="4"
                            class="rounded-md border border-gray-700 bg-gray-950 px-3 py-2.5" placeholder="Jelaskan alasan izin atau sakit.">{{ old('alasan') }}</textarea>
                    </label>
                    <label class="grid gap-2 text-sm">File bukti
                        <input type="file" name="lampiran" required accept=".jpg,.jpeg,.png,.pdf"
                            class="rounded-md border border-dashed border-gray-700 bg-gray-950 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-blue-600 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-white">
                        <span class="text-xs text-gray-500">JPG, JPEG, PNG, atau PDF. Maksimum 2 MB.</span>
                    </label>
                    <button class="rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium hover:bg-blue-500">Kirim
                        Pengajuan</button>
                </form>

                <section class="rounded-xl border border-gray-800 bg-gray-900 p-5 sm:p-6 lg:col-span-2">
                    <h2 class="font-semibold">Status Pengajuan</h2>
                    <div class="mt-4 space-y-4">
                        @forelse ($pengajuanIzins as $pengajuan)
                            <article class="border-b border-gray-800 pb-4 last:border-0 last:pb-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium">{{ ucfirst($pengajuan->jenis) }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $pengajuan->tanggal->translatedFormat('d M Y') }}</p>
                                    </div>
                                    <span
                                        class="rounded-full px-2.5 py-1 text-xs font-medium {{ $pengajuan->status === 'disetujui' ? 'bg-emerald-500/10 text-emerald-400' : ($pengajuan->status === 'ditolak' ? 'bg-red-500/10 text-red-400' : 'bg-amber-500/10 text-amber-400') }}">{{ ucfirst($pengajuan->status) }}</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-400">{{ $pengajuan->alasan }}</p>
                                @if ($pengajuan->catatan_verifikasi)
                                    <p class="mt-2 text-xs text-gray-500">Catatan wali kelas:
                                        {{ $pengajuan->catatan_verifikasi }}</p>
                                @endif
                            </article>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada pengajuan.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </section>

        {{-- =============== RIWAYAT SAYA =============== --}}
        <section id="tab-riwayat" class="tab-section hidden space-y-6">
            <div>
                <h1 class="text-2xl font-bold">Riwayat Saya</h1>
                <p class="text-gray-500 mt-1">Ringkasan dan riwayat kehadiran semester</p>
            </div>

            <div
                class="bg-gray-900 border border-gray-800 rounded-xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-sm text-blue-400 font-medium">Tingkat Kehadiran Keseluruhan</p>
                    <p class="text-4xl font-bold mt-1">{{ $tingkatKehadiran }}<span
                            class="text-xl text-blue-400">%</span></p>
                    <p class="text-sm text-blue-400 mt-1">
                        Status:
                        {{ $tingkatKehadiran >= 90 ? 'Sangat Baik' : ($tingkatKehadiran >= 75 ? 'Baik' : 'Perlu Perhatian') }}
                    </p>
                </div>
                <div class="flex gap-10">
                    <div class="text-center">
                        <p class="text-2xl font-bold">{{ $totalHadir + $totalTerlambat }}</p>
                        <p class="text-xs text-gray-500 mt-1">Hadir</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold">{{ $totalTidakHadir }}</p>
                        <p class="text-xs text-gray-500 mt-1">Tidak Hadir</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold">{{ $totalPertemuan }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Pertemuan</p>
                    </div>
                </div>
            </div>

            <div class="inline-flex bg-gray-900 border border-gray-800 rounded-lg p-1">
                <button data-subtab="permatkul"
                    class="subtab-btn px-4 py-1.5 rounded-md text-sm font-medium text-gray-400 transition">Per Mata
                    Kuliah</button>
                <button data-subtab="lengkap"
                    class="subtab-btn px-4 py-1.5 rounded-md text-sm font-medium text-gray-400 transition">Riwayat
                    Lengkap</button>
            </div>

            <div id="subtab-permatkul"
                class="subtab-panel bg-gray-900 border border-gray-800 rounded-xl overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 uppercase border-b border-gray-800">
                            <th class="p-4 font-medium">Mata Kuliah</th>
                            <th class="p-4 font-medium">Kode</th>
                            <th class="p-4 font-medium">Hadir</th>
                            <th class="p-4 font-medium">Total</th>
                            <th class="p-4 font-medium">Persentase</th>
                            <th class="p-4 font-medium">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @foreach ($rekapPerMatkul as $r)
                            <tr>
                                <td class="p-4 font-medium">{{ $r->nama }}</td>
                                <td class="p-4 text-gray-500 font-mono text-xs">{{ $r->kode }}</td>
                                <td class="p-4">{{ $r->hadir }}</td>
                                <td class="p-4 text-gray-500">{{ $r->total }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                                            <div class="h-full {{ $r->persentase >= 90 ? 'bg-emerald-500' : ($r->persentase >= 75 ? 'bg-amber-500' : 'bg-red-500') }}"
                                                style="width:{{ $r->persentase }}%"></div>
                                        </div>
                                        <span
                                            class="{{ $r->persentase >= 90 ? 'text-emerald-400' : ($r->persentase >= 75 ? 'text-amber-400' : 'text-red-400') }}">{{ $r->persentase }}%</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-xs font-medium {{ $r->keterangan == 'Aman' ? 'bg-emerald-500/10 text-emerald-400' : ($r->keterangan == 'Memuaskan' ? 'bg-amber-500/10 text-amber-400' : 'bg-red-500/10 text-red-400') }}">{{ $r->keterangan }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="subtab-lengkap"
                class="subtab-panel hidden bg-gray-900 border border-gray-800 rounded-xl overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 uppercase border-b border-gray-800">
                            <th class="p-4 font-medium">Tanggal</th>
                            <th class="p-4 font-medium">Mata Kuliah</th>
                            <th class="p-4 font-medium">Kode</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium">Waktu Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @forelse($riwayat->sortByDesc('tanggal') as $item)
                            <tr>
                                <td class="p-4 text-gray-400">{{ $item->tanggal->translatedFormat('D, j M') }}</td>
                                <td class="p-4 font-medium">{{ $item->mataKuliah->nama ?? '-' }}</td>
                                <td class="p-4 text-gray-500 font-mono text-xs">{{ $item->mataKuliah->kode ?? '-' }}
                                </td>
                                <td class="p-4">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-xs font-medium {{ $statusColor[$item->status] ?? '' }}">●
                                        {{ $item->status }}</span>
                                </td>
                                <td class="p-4 text-gray-400 font-mono text-xs">
                                    {{ $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('H.i') : '–' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">Belum ada riwayat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- =============== JADWAL =============== --}}
        <section id="tab-jadwal" class="tab-section hidden space-y-6">
            <div>
                <h1 class="text-2xl font-bold">Jadwal Mingguan</h1>
                <p class="text-gray-500 mt-1">Jadwal perkuliahan semester · {{ $user->prodi ?? '-' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach ($urutanHari as $hari)
                    <div
                        class="bg-gray-900 border {{ $hari === $hariIni ? 'border-blue-500' : 'border-gray-800' }} rounded-xl overflow-hidden">
                        <div
                            class="px-4 py-3 flex items-center justify-between {{ $hari === $hariIni ? 'bg-blue-500/10' : 'bg-gray-800/40' }}">
                            <span
                                class="font-semibold text-sm {{ $hari === $hariIni ? 'text-blue-400' : '' }}">{{ $hari }}</span>
                            @if ($hari === $hariIni)
                                <span class="text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded-full">Hari
                                    Ini</span>
                            @endif
                        </div>
                        <div class="p-3 space-y-3">
                            @forelse($jadwalMingguan->get($hari, collect()) as $k)
                                <div class="border border-gray-800 rounded-lg p-3">
                                    <p class="font-medium text-sm">{{ $k->nama }}</p>
                                    <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $k->kode }}</p>
                                    <p class="text-xs text-gray-500 mt-1.5">
                                        {{ \Carbon\Carbon::parse($k->jam_mulai)->format('H.i') }} –
                                        {{ \Carbon\Carbon::parse($k->jam_selesai)->format('H.i') }}</p>
                                    <p class="text-xs text-gray-500">{{ $k->ruang }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-gray-600 px-1">Tidak ada kelas</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-section').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tab)?.classList.remove('hidden');
            document.querySelectorAll('.tab-btn').forEach(btn => {
                const active = btn.dataset.tab === tab;
                btn.classList.toggle('bg-gray-800', active);
                btn.classList.toggle('text-white', active);
                btn.classList.toggle('text-gray-400', !active);
            });
        }
        document.querySelectorAll('.tab-btn').forEach(btn => btn.addEventListener('click', () => switchTab(btn.dataset
            .tab)));
        switchTab(@js($errors->any() ? 'izin' : session('active_tab', 'dasbor')));

        document.querySelectorAll('.subtab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.subtab-panel').forEach(p => p.classList.add('hidden'));
                document.getElementById('subtab-' + btn.dataset.subtab)?.classList.remove('hidden');
                document.querySelectorAll('.subtab-btn').forEach(b => {
                    const active = b === btn;
                    b.classList.toggle('bg-gray-800', active);
                    b.classList.toggle('text-white', active);
                    b.classList.toggle('text-gray-400', !active);
                });
            });
        });
        document.querySelector('.subtab-btn')?.click();

        setInterval(() => {
            const el = document.getElementById('liveClock');
            if (el) {
                const now = new Date();
                el.textContent = String(now.getHours()).padStart(2, '0') + '.' + String(now.getMinutes()).padStart(
                    2, '0');
            }
        }, 1000);

        document.querySelectorAll('.btn-tandai').forEach(btn => {
            btn.addEventListener('click', async () => {
                btn.disabled = true;
                btn.textContent = 'Menyimpan...';
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                try {
                    const response = await fetch("{{ route('siswa.presensi.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            mata_kuliah_id: btn.dataset.id
                        }),
                    });
                    const result = await response.json();

                    if (result.success) {
                        const badgeClass = result.status === 'Hadir' ?
                            'bg-emerald-500/10 text-emerald-400' :
                            'bg-amber-500/10 text-amber-400';
                        btn.outerHTML =
                            `<span class="px-3 py-1.5 rounded-md text-xs font-medium ${badgeClass}">● ${result.status}</span>`;

                        const fractionEl = document.getElementById('progressFraction');
                        const labelEl = document.getElementById('progressLabel');
                        const barEl = document.getElementById('progressBar');
                        if (fractionEl) {
                            const [done, total] = fractionEl.textContent.split('/').map(Number);
                            const newDone = done + 1;
                            fractionEl.textContent = `${newDone}/${total}`;
                            if (labelEl) labelEl.textContent = `${newDone} dari ${total} ditandai`;
                            if (barEl) barEl.style.width = `${Math.round((newDone / total) * 100)}%`;
                        }
                    } else {
                        alert(result.message);
                        btn.disabled = false;
                        btn.textContent = 'Tandai Hadir';
                    }
                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan koneksi.');
                    btn.disabled = false;
                    btn.textContent = 'Tandai Hadir';
                }
            });
        });
    </script>
</body>

</html>
