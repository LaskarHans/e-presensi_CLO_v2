<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'dosen',
        'ruang',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
