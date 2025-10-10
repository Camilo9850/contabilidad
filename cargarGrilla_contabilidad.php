<?php
// This file appears to contain a function that should be part of a controller
// The same function already exists in app/Http/Controllers/ContabilidadController.php
// This standalone file has been fixed with proper PHP tags

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Function to load grid data for contabilidad
function cargarGrilla()
{
    $contabilidad = DB::table('contabilidads')->get();
    
    $data = [];
    foreach ($contabilidad as $aContabilidad) {
        // Crear botones de acción - usando la ruta correcta para editar contabilidad
        $acciones = '<a href="/admin/contabilidad/editar/' . $aContabilidad->idcontabilidad . '" title="Editar" class="btn-accion"><i class="fa-solid fa-edit"></i></a>';
        
        $data[] = [
            $aContabilidad->fecha_transaccion,
            $aContabilidad->tipo_movimiento,
            $aContabilidad->monto,
            $aContabilidad->fk_id_sucursal,
            $aContabilidad->referencia_id,
            $aContabilidad->descripcion,
            $acciones
        ];
    }
    
    return response()->json([
        "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal" => count($data),
        "recordsFiltered" => count($data),
        "data" => $data
    ]);
}

// If this file is called directly, execute the function
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    // This would only execute if the function returns a response object
    // Better to include this in a controller instead of using this file directly
    echo "This file contains the cargarGrilla function. It should be part of a controller instead.";
}