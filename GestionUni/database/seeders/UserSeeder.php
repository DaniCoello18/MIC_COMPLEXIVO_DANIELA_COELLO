<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Importa el modelo User
use Illuminate\Support\Facades\Hash; // Importa Hash

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el Administrador
        User::create([
            'name'     => 'Administrador del Sistema',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('admin123'), // ¡Cambia esto después!
            'role'     => 'admin', // Asegúrate que tu columna se llame 'role'
        ]);

        // Opcional: Crear un usuario normal para pruebas
        User::create([
            'name'     => 'Usuario Prueba',
            'email'    => 'user@user.com',
            'password' => Hash::make('user123'),
            'role'     => 'user',
        ]);
    }
}
