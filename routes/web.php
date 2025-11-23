<?php

use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\PublicRideController;
use App\Http\Controllers\ReservationController;

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
});


// Breeze
require __DIR__.'/auth.php';
