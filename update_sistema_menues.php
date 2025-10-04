<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Crea la aplicación Laravel
$app = require_once 'bootstrap/app.php';

// Inicializa el contenedor
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Realiza la actualización
try {
    DB::statement('UPDATE sistema_menues SET id_padre = NULL WHERE TRIM(CAST(id_padre AS CHAR)) = \'\' OR id_padre = \'0\' OR id_padre = 0');
    echo \"Valores actualizados correctamente en sistema_menues\\n\";
} catch (Exception $e) {
    echo \"Error: \" . $e->getMessage() . \"\\n\";
}