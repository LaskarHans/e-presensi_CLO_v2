<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PresensiController::class, 'index'])->name('presensi.index');
Route::post('/', [PresensiController::class, 'store'])->name('presensi.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::resource('mata-kuliah', MataKuliahController::class)->except(['show']);
