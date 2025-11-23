<?php

use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\PublicRideController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DriverReservationController;
use App\Http\Controllers\AdminUserController;

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (autenticado + verificado)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =============================
// PERFIL (Breeze)
// =============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =============================
// LISTADO PÚBLICO DE RIDES
// =============================
Route::get('/rides-disponibles', [PublicRideController::class, 'index'])
    ->name('public.rides.index');


// =============================
// RESERVAS PARA PASAJEROS
// =============================

// Crear reserva (botón en la página pública)
Route::post('/rides/{ride}/reservas', [ReservationController::class, 'store'])
    ->middleware(['auth', 'pasajero'])
    ->name('reservations.store');

// Ver mis reservas (pasajero)
Route::middleware(['auth', 'pasajero'])->group(function () {
    Route::get('/mis-reservas', [ReservationController::class, 'indexPassenger'])
        ->name('reservations.passenger.index');

    Route::post('/reservas/{reservation}/cancelar', [ReservationController::class, 'cancel'])
        ->name('reservations.cancel');
});


// =============================
// VEHÍCULOS Y RIDES (SOLO CHOFER)
// =============================
Route::middleware(['auth', 'chofer'])->group(function () {
    Route::resource('vehicles', VehicleController::class)->except(['show']);
    Route::resource('rides', RideController::class)->except(['show']);
    Route::get('/rides/{ride}/reservas', [DriverReservationController::class, 'index'])
    ->name('driver.reservations.index');

    Route::post('/reservas/{reservation}/aceptar', [DriverReservationController::class, 'accept'])
    ->name('driver.reservations.accept');

    Route::post('/reservas/{reservation}/rechazar', [DriverReservationController::class, 'reject'])
    ->name('driver.reservations.reject');

    Route::post('/reservas/{reservation}/cancelar-chofer',
    [DriverReservationController::class, 'cancelByDriver'])
    ->name('driver.reservations.cancelByDriver');

});

// =============================
// PANEL DE ADMINISTRACIÓN (USUARIOS)
// =============================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/admin/usuarios/crear', [AdminUserController::class, 'create'])
        ->name('admin.users.create');

    Route::post('/admin/usuarios', [AdminUserController::class, 'store'])
        ->name('admin.users.store');

    Route::post('/admin/usuarios/{user}/estado', [AdminUserController::class, 'updateStatus'])
        ->name('admin.users.updateStatus');

    Route::delete('/admin/usuarios/{user}', [AdminUserController::class, 'destroy'])
        ->name('admin.users.destroy');
});


// Breeze
require __DIR__.'/auth.php';
