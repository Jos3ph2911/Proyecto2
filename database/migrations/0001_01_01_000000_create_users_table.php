<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Datos personales
            $table->string('nombre');
            $table->string('apellido');
            $table->string('cedula')->unique();
            $table->date('fecha_nacimiento');
            $table->string('telefono')->nullable();
            $table->string('foto')->nullable(); // ruta en storage/app/public

            // Correo y autenticación
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Rol y estado
            $table->enum('rol', ['administrador', 'chofer', 'pasajero'])->default('pasajero');
            $table->enum('estado', ['PENDIENTE', 'ACTIVO', 'INACTIVO'])->default('PENDIENTE');

            // Activación por correo
            $table->string('token_activacion')->nullable();

            // Super admin
            $table->boolean('is_super_admin')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
