<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@aventones.com'],
            [
                'nombre'           => 'Super',
                'apellido'         => 'Admin',
                'cedula'           => '000000000',
                'fecha_nacimiento' => '1990-01-01',
                'telefono'         => '',
                'foto'             => null,
                'password'         => Hash::make('SuperAdmin123'),
                'rol'              => 'administrador',
                'estado'           => 'ACTIVO',
                'token_activacion' => null,
                'is_super_admin'   => true,
            ]
        );
    }
}
