<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        // Crear un usuario administrador por defecto
        User::firstOrCreate(
            ['email' => 'admin@tallerpro.com'],
            [
                'name' => 'Fernando Administrador',
                'password' => Hash::make('TallerSecure2026!'),
                'estado' => true,
                'rol_id' => 1,
            ]
        );

        $this->call(SystemSeeder::class);
        $this->call(CatalogDataSeeder::class);
    }
}
