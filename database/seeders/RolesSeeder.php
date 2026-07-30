<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Recepcionista', 'descripcion' => 'Registro de clientes y órdenes, cobranza', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Mecanico', 'descripcion' => 'Gestión operativa de órdenes en taller', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        \App\Models\User::where('email', 'admin@taller.com')->update(['rol_id' => 1]);
    }
}
