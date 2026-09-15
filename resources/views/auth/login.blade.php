<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · E-Hadir</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-950 px-4 text-gray-100 antialiased">
    <main class="mx-auto flex min-h-screen max-w-md items-center">
        <section class="w-full rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-2xl">
            <h1 class="text-2xl font-bold">Masuk ke E-Hadir</h1>
            <p class="mt-2 text-sm text-gray-400">Gunakan NISN atau NIP dan kata sandi Anda.</p>

            <form method="POST" action="{{ route('login.store') }}" class="mt-7 space-y-5">
                @csrf

                <div>
                    <label for="nomor_induk" class="block text-sm font-medium">NISN / NIP</label>
                    <input id="nomor_induk" name="nomor_induk" type="text" value="{{ old('nomor_induk') }}" required autofocus autocomplete="username" class="mt-2 w-full rounded-lg border border-gray-700 bg-gray-950 px-3 py-2.5 outline-none focus:border-blue-500">
                    @error('nomor_induk')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium">Kata sandi</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="mt-2 w-full rounded-lg border border-gray-700 bg-gray-950 px-3 py-2.5 outline-none focus:border-blue-500">
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-300">
                    <input type="checkbox" name="remember" value="1" class="rounded border-gray-600 bg-gray-950">
                    Ingat saya
                </label>

                <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 font-semibold hover:bg-blue-500">Masuk</button>
            </form>
        </section>
    </main>
</body>
</html>
