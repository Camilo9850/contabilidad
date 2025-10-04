<?php
// archivo temporal para verificar conexion a base de datos

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Inicializar aplicación
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar si la tabla clientes existe
if (Schema::hasTable('clientes')) {
    echo "La tabla 'clientes' existe\n";
    
    // Contar registros
    $count = DB::table('clientes')->count();
    echo "Número de registros en la tabla clientes: " . $count . "\n";
    
    // Intentar obtener algunos registros
    $clientes = DB::table('clientes')->limit(5)->get();
    if ($clientes->count() > 0) {
        echo "Ejemplo de registros:\n";
        foreach ($clientes as $cliente) {
            echo "- ID: " . $cliente->idcliente . ", Nombre: " . $cliente->nombre . ", Correo: " . $cliente->correo . "\n";
        }
    } else {
        echo "La tabla está vacía\n";
    }
} else {
    echo "La tabla 'clientes' NO existe\n";
}

echo "Conexión a base de datos: OK\n";