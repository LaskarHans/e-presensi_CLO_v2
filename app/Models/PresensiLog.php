<?php

namespace App\Models;

use Database\Factories\PresensiLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiLog extends Model
{
    /** @use HasFactory<PresensiLogFactory> */
    use HasFactory;

    protected $fillable = [
        'presensi_id',
        'diubah_oleh',
        'status_sebelumnya',
        'status_terbaru',
        'alasan_koreksi',
    ];

    public function presensi(): BelongsTo
    {
        return $this->belongsTo(Presensi::class);
    }

    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
