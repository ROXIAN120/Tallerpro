<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::firstOrCreate(
    ['email' => 'recepcion@taller.com'],
    ['name' => 'Recepcionista', 'password' => Hash::make('password'), 'rol_id' => 2]
);

User::firstOrCreate(
    ['email' => 'mecanico@taller.com'],
    ['name' => 'Mecánico', 'password' => Hash::make('password'), 'rol_id' => 3]
);

echo "Usuarios creados correctamente.\n";
