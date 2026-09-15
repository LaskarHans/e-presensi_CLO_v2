<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'nomor_induk',
    'nim',
    'prodi',
    'angkatan',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    public function kelasDiikuti(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa', 'siswa_id', 'kelas_id')
            ->withPivot(['aktif', 'mulai_pada', 'selesai_pada'])
            ->withTimestamps();
    }

    public function kelasBinaan(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'wali_kelas_kelas', 'wali_kelas_id', 'kelas_id')
            ->withPivot(['aktif', 'mulai_pada', 'selesai_pada'])
            ->withTimestamps();
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isWaliKelas(): bool
    {
        return $this->role === 'wali_kelas';
    }
}
