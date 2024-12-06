<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class
        ]);

        // Crear usuario administrador por defecto
        \App\Models\User::create([
            'name' => 'liassasel',
            'email' => 'auseche2041@gmail.com',
            'password' => bcrypt('Angel2041'),
            'is_admin' => true,
            'is_active' => true,
            'role_id' => 1, // Admin role
        ]);
    }
}

