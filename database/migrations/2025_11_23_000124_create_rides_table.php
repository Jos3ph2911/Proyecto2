<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();

            // Chofer que ofrece el ride
            $table->foreignId('chofer_id')
                ->constrained('users');

            // Vehículo asociado
            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->cascadeOnDelete();

            $table->string('titulo', 100);
            $table->string('lugar_salida', 100);
            $table->string('lugar_llegada', 100);
            $table->dateTime('fecha_hora');

            $table->decimal('costo_por_espacio', 10, 2);
            $table->integer('espacios_totales');
            $table->integer('espacios_disponibles');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
