<?php

use App\Http\Controllers\ProfileController;
use App\Models\Producto;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MovimientoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('productos', ProductoController::class);
    Route::get('stock', [MovimientoController::class, 'index'])->name('stock.index');
    Route::post('entradas', [MovimientoController::class, 'registrarEntrada'])->name('stock.entrada');
    Route::post('salidas', [MovimientoController::class, 'registrarSalida'])->name('stock.salida');
});

require __DIR__.'/auth.php';