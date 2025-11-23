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
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono')->nullable();
            $table->string('foto')->nullable();

            // Credenciales
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Rol y estado
            $table->enum('rol', ['administrador', 'chofer', 'pasajero'])->default('pasajero');
            $table->enum('estado', ['PENDIENTE', 'ACTIVO', 'INACTIVO'])->default('PENDIENTE');

            // Activación y super admin
            $table->string('token_activacion')->nullable();
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
