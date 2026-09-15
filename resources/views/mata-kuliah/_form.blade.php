@php
    $jamMulaiVal = old('jam_mulai', $mataKuliah->jam_mulai ? \Illuminate\Support\Carbon::parse($mataKuliah->jam_mulai)->format('H:i') : '');
    $jamSelesaiVal = old('jam_selesai', $mataKuliah->jam_selesai ? \Illuminate\Support\Carbon::parse($mataKuliah->jam_selesai)->format('H:i') : '');
    $inputClass = 'w-full bg-gray-950 border border-gray-800 rounded-lg px-3.5 py-2.5 text-sm text-gray-100 placeholder:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600';
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') === 'PUT')
        @method('PUT')
    @endif

    @if ($errors->has('bentrok'))
        <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
            <p class="text-sm font-medium text-red-400 mb-1.5">Jadwal bentrok, belum bisa disimpan:</p>
            <ul class="text-sm text-red-300 list-disc list-inside space-y-0.5">
                @foreach ($errors->get('bentrok') as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="kode" class="block text-sm font-medium text-gray-300 mb-1.5">Kode Mata Kuliah</label>
            <input type="text" name="kode" id="kode" value="{{ old('kode', $mataKuliah->kode) }}"
                   class="{{ $inputClass }}" placeholder="cth. IF301">
            @error('kode') <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-300 mb-1.5">Nama Mata Kuliah</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $mataKuliah->nama) }}"
                   class="{{ $inputClass }}" placeholder="cth. Struktur Data &amp; Algoritma">
            @error('nama') <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="dosen" class="block text-sm font-medium text-gray-300 mb-1.5">Dosen Pengampu</label>
            <input type="text" name="dosen" id="dosen" value="{{ old('dosen', $mataKuliah->dosen) }}"
                   class="{{ $inputClass }}" placeholder="cth. Dr. Marcus Reid">
            @error('dosen') <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="ruang" class="block text-sm font-medium text-gray-300 mb-1.5">Ruang</label>
            <input type="text" name="ruang" id="ruang" value="{{ old('ruang', $mataKuliah->ruang) }}"
                   class="{{ $inputClass }}" placeholder="cth. Lab 4B">
            @error('ruang') <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
            <label for="hari" class="block text-sm font-medium text-gray-300 mb-1.5">Hari</label>
            <select name="hari" id="hari" class="{{ $inputClass }}">
                <option value="" disabled {{ old('hari', $mataKuliah->hari) ? '' : 'selected' }}>Pilih hari</option>
                @foreach ($urutanHari as $hari)
                    <option value="{{ $hari }}" {{ old('hari', $mataKuliah->hari) === $hari ? 'selected' : '' }}>{{ $hari }}</option>
                @endforeach
            </select>
            @error('hari') <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="jam_mulai" class="block text-sm font-medium text-gray-300 mb-1.5">Jam Mulai</label>
            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ $jamMulaiVal }}" class="{{ $inputClass }}">
            @error('jam_mulai') <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="jam_selesai" class="block text-sm font-medium text-gray-300 mb-1.5">Jam Selesai</label>
            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ $jamSelesaiVal }}" class="{{ $inputClass }}">
            @error('jam_selesai') <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p> @enderror
        </div>
    </div>

    @if (($jumlahSaudara ?? 0) > 0)
        <label class="flex items-start gap-2.5 text-sm bg-gray-950 border border-gray-800 rounded-lg p-3.5 cursor-pointer hover:border-gray-700 transition">
            <input type="checkbox" name="terapkan_semua" value="1" {{ old('terapkan_semua') ? 'checked' : '' }}
                   class="mt-0.5 rounded border-gray-700 bg-gray-900 text-blue-600 focus:ring-2 focus:ring-blue-600">
            <span class="text-gray-300">
                Terapkan <strong class="text-gray-100">Kode, Nama &amp; Dosen</strong> ini ke <strong class="text-gray-100">{{ $jumlahSaudara }}</strong> jadwal lain yang kodenya sama ({{ $mataKuliah->kode }}).
                <span class="block text-xs text-gray-500 mt-0.5">Hari, jam, dan ruang di jadwal lain tidak ikut berubah. Aktifkan ini kalau mata kuliah tersebut tampil di lebih dari satu hari dan kamu ingin ubah namanya di semua slot sekaligus.</span>
            </span>
        </label>
    @endif

    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-800">
        <a href="{{ route('mata-kuliah.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:text-gray-200 transition">
            Batal
        </a>
        @if($showSimpanLagi ?? false)
            <button type="submit" name="aksi" value="simpan_lagi"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium bg-gray-800 hover:bg-gray-700 text-gray-200 transition">
                Simpan &amp; Tambah Lagi
            </button>
        @endif
        <button type="submit" name="aksi" value="simpan"
                class="px-5 py-2.5 rounded-lg text-sm font-medium bg-blue-600 hover:bg-blue-500 text-white transition">
            {{ $submitLabel ?? 'Simpan' }}
        </button>
    </div>
</form>
