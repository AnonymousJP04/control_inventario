<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});



// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

Route::middleware('auth')->group(function () {

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified') // 'verified' usualmente se aplica aquí también
        ->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Productos
    Route::resource('productos', ProductoController::class);
    

    // Movimientos
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::post('entradas', [MovimientoController::class, 'registrarEntrada'])->name('movimientos.entrada');
    Route::post('salidas', [MovimientoController::class, 'registrarSalida'])->name('movimientos.salida');

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Ventas
    Route::resource('ventas', VentaController::class);
});

require __DIR__.'/auth.php';
