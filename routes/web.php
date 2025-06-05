<?php

use App\Http\Controllers\Base\AnggotaController;
use App\Http\Controllers\Base\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Trans\KembaliController;
use App\Http\Controllers\Trans\PinjamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect(route('dashboard'));
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/chart', [DashboardController::class, 'chartJson'])->name('chart.json');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'anggota', 'middleware' => ['auth']], function(){
    Route::get('/', [AnggotaController::class, 'show'])->name('anggota.show');
    Route::get('/add', [AnggotaController::class, 'add'])->name('anggota.add');
    Route::get('/update/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::post('/delete', [AnggotaController::class, 'delete'])->name('anggota.delete');
    Route::post('/save', [AnggotaController::class, 'save'])->name('anggota.save');
});

Route::group(['prefix' => 'buku', 'middleware' => ['auth']], function(){
    Route::get('/', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/add', [BukuController::class, 'add'])->name('buku.add');
    Route::get('/update/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::post('/delete', [BukuController::class, 'delete'])->name('buku.delete');
    Route::post('/save', [BukuController::class, 'save'])->name('buku.save');
});

Route::group(['prefix' => 'pinjam', 'middleware' => ['auth']], function(){
    Route::get('/', [PinjamController::class, 'show'])->name('pinjam.show');
    Route::get('/add', [PinjamController::class, 'add'])->name('pinjam.add');
    Route::get('/update/{id}', [PinjamController::class, 'update'])->name('pinjam.update');
    Route::post('/delete', [PinjamController::class, 'delete'])->name('pinjam.delete');
    Route::post('/save', [PinjamController::class, 'save'])->name('pinjam.save');
});

Route::group(['prefix' => 'kembali', 'middleware' => ['auth']], function(){
    Route::get('/', [KembaliController::class, 'show'])->name('kembali.show');
    Route::get('/add', [KembaliController::class, 'add'])->name('kembali.add');
    Route::post('/save', [KembaliController::class, 'save'])->name('kembali.save');
    Route::get('/json-cek-pinjaman/{anggotaId}', [KembaliController::class, 'jsonCekPinjaman'])->name('kembali.json.cek.pinjaman');
});

require __DIR__.'/auth.php';
