<?php
// Script temporal para corregir los datos en la tabla sistema_menues

require_once 'vendor/autoload.php';

// Cargar el entorno de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo \"Conectando a la base de datos...\\n\";

// Buscar registros con id_padre vacío
try {
    // Cambiar todos los valores de id_padre que sean cadenas vacías a NULL
    $affected = DB::update(\"UPDATE sistema_menues SET id_padre = NULL WHERE TRIM(id_padre) = ''\");
    echo \"Corregidos \" . $affected . \" registros con id_padre vacío\\n\";
    
    // Buscar registros con id_padre = 0 y cambiarlos a NULL
    $affected = DB::update(\"UPDATE sistema_menues SET id_padre = NULL WHERE id_padre = 0 AND TRIM(id_padre) != ''\");
    echo \"Corregidos \" . $affected . \" registros con id_padre = 0\\n\";
    
    echo \"Corrección de datos completada.\\n\";
} catch (Exception $e) {
    echo \"Error: \" . $e->getMessage() . \"\\n\";
}