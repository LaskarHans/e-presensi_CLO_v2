<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal');
            $table->enum('jenis', ['izin', 'sakit']);
            $table->text('alasan');
            $table->string('lampiran_path')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dibatalkan'])
                ->default('menunggu');
            $table->foreignId('diverifikasi_oleh')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();

            $table->index(['siswa_id', 'status', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izins');
    }
};
