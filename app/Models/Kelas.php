<?php

namespace App\Models;

use Database\Factories\KelasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelas extends Model
{
    /** @use HasFactory<KelasFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'tingkat',
        'tahun_ajaran',
    ];

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelas_siswa', 'kelas_id', 'siswa_id')
            ->withPivot(['aktif', 'mulai_pada', 'selesai_pada'])
            ->withTimestamps();
    }

    public function siswaAktif(): BelongsToMany
    {
        return $this->siswa()->wherePivot('aktif', true);
    }

    public function waliKelas(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wali_kelas_kelas', 'kelas_id', 'wali_kelas_id')
            ->withPivot(['aktif', 'mulai_pada', 'selesai_pada'])
            ->withTimestamps();
    }
}
