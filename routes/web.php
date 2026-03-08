<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\AturanController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DecisionNodeController;

/*
|--------------------------------------------------------------------------
| 🌐 HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, '__invoke'])->name('home');

/*
|--------------------------------------------------------------------------
| ⚙️ ADMIN AREA (Resource Routes)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::resource('gejala', GejalaController::class);
    Route::resource('penyakit', PenyakitController::class);
    Route::resource('aturan', AturanController::class);
    Route::resource('decision-nodes', DecisionNodeController::class);
});

/*
|--------------------------------------------------------------------------
| 🩺 DIAGNOSA (USER AREA)
| Menggabungkan Decision Tree dan Certainty Factor
|--------------------------------------------------------------------------
*/
Route::prefix('diagnosa')->name('diagnosa.')->group(function () {
    // 📋 Halaman Riwayat Diagnosa
    Route::get('/', [DiagnosaController::class, 'index'])->name('index');

    // 🧩 Form Awal (Masukkan Nama Hewan)
    Route::get('/create', [DiagnosaController::class, 'create'])->name('create');
    // 🗑️ Hapus Data Diagnosa
    Route::delete('/{diagnosa}', [DiagnosaController::class, 'destroy'])->name('destroy');

    // 🌳 Langkah-Langkah Decision Tree
    Route::get('/step/{node?}', [DiagnosaController::class, 'step'])->name('step');
    Route::post('/step', [DiagnosaController::class, 'processStep'])->name('processStep');

    // 💾 Simpan Hasil Diagnosa (CF Calculation)
    // 💾 Simpan Hasil Diagnosa (CF Calculation)
    Route::post('/store', [DiagnosaController::class, 'store'])->name('store');


    // 🔍 Detail Hasil Diagnosa
    Route::get('/{diagnosa}', [DiagnosaController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| 🔐 ADMIN LOGIN & AUTH
|--------------------------------------------------------------------------
*/
Route::resource('admin', AdminController::class)->middleware('guest');

Route::get('login', [LoginController::class, 'loginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
