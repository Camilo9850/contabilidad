<?php
// archivo temporal para crear un cliente de prueba

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Inicializar aplicación
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Crear un cliente de prueba
$cliente = [
    'nombre' => 'Cliente de Prueba',
    'telefono' => '123456789',
    'correo' => 'cliente@prueba.com',
    'clave' => password_hash('clave123', PASSWORD_DEFAULT)
];

$result = DB::table('clientes')->insert($cliente);

if ($result) {
    echo "Cliente de prueba creado exitosamente\n";
    
    // Verificar que se haya insertado
    $nuevoCliente = DB::table('clientes')->where('correo', 'cliente@prueba.com')->first();
    echo "Cliente insertado con ID: " . $nuevoCliente->idcliente . "\n";
    echo "Nombre: " . $nuevoCliente->nombre . "\n";
    echo "Teléfono: " . $nuevoCliente->telefono . "\n";
    echo "Correo: " . $nuevoCliente->correo . "\n";
} else {
    echo "Error al crear el cliente de prueba\n";
}