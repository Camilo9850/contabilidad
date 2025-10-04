public function cargarGrilla()
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