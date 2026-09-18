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
        Schema::create('kelas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
            $table->foreignId('siswa_id')->constrained('users')->restrictOnDelete();
            $table->boolean('aktif')->default(true);
            $table->date('mulai_pada');
            $table->date('selesai_pada')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'siswa_id']);
        });

        Schema::create('wali_kelas_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
            $table->foreignId('wali_kelas_id')->constrained('users')->restrictOnDelete();
            $table->boolean('aktif')->default(true);
            $table->date('mulai_pada');
            $table->date('selesai_pada')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'wali_kelas_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_kelas_kelas');
        Schema::dropIfExists('kelas_siswa');
    }
};
