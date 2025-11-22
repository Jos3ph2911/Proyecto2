<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fecha_nacimiento',
        'telefono',
        'foto',
        'email',
        'password',
        'rol',
        'estado',
        'token_activacion',
        'is_super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'token_activacion',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_nacimiento'  => 'date',
        'is_super_admin'    => 'boolean',
    ];

    // Helpers
    public function esAdministrador(): bool
    {
        return $this->rol === 'administrador';
    }

    public function esChofer(): bool
    {
        return $this->rol === 'chofer';
    }

    public function esPasajero(): bool
    {
        return $this->rol === 'pasajero';
    }

    public function esSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }
}
