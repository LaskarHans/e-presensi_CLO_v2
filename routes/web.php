<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\MataKuliahController;

Route::get('/', [PresensiController::class, 'index'])->name('presensi.index');
Route::post('/', [PresensiController::class, 'store'])->name('presensi.store');

Route::resource('mata-kuliah', MataKuliahController::class)->except(['show']);
