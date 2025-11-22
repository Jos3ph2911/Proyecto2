<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@aventones.com'],
            [
                'nombre'           => 'Super',
                'apellido'         => 'Admin',
                'cedula'           => '000000000',
                'fecha_nacimiento' => '1990-01-01',
                'telefono'         => '00000000',
                'password'         => Hash::make('SuperAdmin123!'),
                'rol'              => 'administrador',
                'estado'           => 'ACTIVO',
                'is_super_admin'   => true,
                'token_activacion' => null,
            ]
        );
    }
}
