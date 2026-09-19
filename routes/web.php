<?php
 
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TentangController;
use Illuminate\Support\Facades\Route;
 
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');