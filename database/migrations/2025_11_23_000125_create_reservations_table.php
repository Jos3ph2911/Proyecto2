<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Viaje reservado
            $table->foreignId('ride_id')
                ->constrained('rides')
                ->cascadeOnDelete();

            // Pasajero que reserva
            $table->foreignId('pasajero_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('estado', ['PENDIENTE', 'ACEPTADA', 'RECHAZADA', 'CANCELADA'])
                  ->default('PENDIENTE');

            $table->timestamp('fecha_reserva')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
