<?php

namespace App\Models;

use Database\Factories\PengajuanIzinFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanIzin extends Model
{
    /** @use HasFactory<PengajuanIzinFactory> */
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'jenis',
        'alasan',
        'lampiran_path',
        'status',
        'diverifikasi_oleh',
        'diverifikasi_pada',
        'catatan_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'diverifikasi_pada' => 'datetime',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
