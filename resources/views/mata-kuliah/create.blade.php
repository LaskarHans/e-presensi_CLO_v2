<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Mata Kuliah · E-Hadir</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen antialiased">

<nav class="sticky top-0 z-30 bg-gray-950/90 backdrop-blur border-b border-gray-800">
    <div class="max-w-3xl mx-auto px-6 h-16 flex items-center gap-3">
        <a href="{{ route('presensi.index') }}" class="flex items-center gap-2 shrink-0">
            <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <span class="font-semibold text-lg">E-Hadir</span>
        </a>
        <span class="text-gray-700">/</span>
        <a href="{{ route('mata-kuliah.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition">Kelola Mata Kuliah</a>
        <span class="text-gray-700">/</span>
        <span class="text-sm text-gray-100">Tambah</span>
    </div>
</nav>

<main class="max-w-3xl mx-auto px-6 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Tambah Mata Kuliah</h1>
        <p class="text-gray-500 mt-1">Isi detail jadwal perkuliahan baru. Perubahan langsung berlaku tanpa perlu ubah kode.</p>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        @include('mata-kuliah._form', [
            'action' => route('mata-kuliah.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Mata Kuliah',
            'showSimpanLagi' => true,
        ])
    </div>
</main>
</body>
</html>
