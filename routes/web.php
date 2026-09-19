<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\WaliKelasController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', function () {
        return auth()->user()->isWaliKelas()
            ? redirect()->route('wali-kelas.dashboard')
            : redirect()->route('siswa.dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/logout', fn () => redirect()->route('login'));

    Route::middleware('role:siswa')->group(function (): void {
        Route::get('/siswa/dashboard', [PresensiController::class, 'index'])->name('siswa.dashboard');
        Route::patch('/siswa/nama', [PresensiController::class, 'updateNama'])->name('siswa.nama.update');
        Route::post('/siswa/presensi', [PresensiController::class, 'store'])->name('siswa.presensi.store');
        Route::post('/siswa/pengajuan-izin', [PresensiController::class, 'storePengajuanIzin'])
            ->name('siswa.pengajuan-izin.store');
    });

    Route::middleware('role:wali_kelas')->prefix('wali-kelas')->name('wali-kelas.')->group(function (): void {
        Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('dashboard');
        Route::get('/pengajuan-izin', [WaliKelasController::class, 'pengajuanIzin'])->name('pengajuan-izin.index');
        Route::patch('/pengajuan-izin/{id}/setujui', [WaliKelasController::class, 'approveIzin'])
            ->name('pengajuan-izin.approve');
        Route::patch('/pengajuan-izin/{id}/tolak', [WaliKelasController::class, 'rejectIzin'])
            ->name('pengajuan-izin.reject');
        Route::patch('/presensi/{presensiId}/koreksi', [WaliKelasController::class, 'koreksiPresensi'])
            ->name('presensi.koreksi');
        Route::get('/rekap', [WaliKelasController::class, 'rekap'])->name('rekap');
        Route::resource('mata-kuliah', MataKuliahController::class)->except(['show']);
    });
});

Route::fallback(fn () => redirect()->route('login'));
