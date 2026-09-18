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
        Schema::create('presensi_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presensi_id')->constrained('presensis')->restrictOnDelete();
            $table->foreignId('diubah_oleh')->constrained('users')->restrictOnDelete();
            $table->string('status_sebelumnya');
            $table->string('status_terbaru');
            $table->text('alasan_koreksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_logs');
    }
};
