<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Entidades\Sucursal;
use App\Entidades\Contabilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContabilidadController extends Controller
{
    public function index()
    {
        $titulo = "Nueva entrada de contabilidad";
        $sucursales = Sucursal::all();
        $contabilidad = null; // Para nueva contabilidad
        
        return view('sistema.contabilidad-nuevo', compact('titulo', 'sucursales', 'contabilidad'));
    }
    
    public function listar()
    {
        $titulo = "Listado de Contabilidad";
        return view('sistema.contabilidad-listar', compact('titulo'));
    }
    
    public function editar($id)
    {
        $contabilidad = DB::table('contabilidads')
            ->leftJoin('sucursals', 'contabilidads.fk_id_sucursal', '=', 'sucursals.idsucursal')
            ->select('contabilidads.*', 'sucursals.nombre as nombre_sucursal')
            ->where('idcontabilidad', $id)
            ->first();
        
        if (!$contabilidad) {
            abort(404, 'Registro de contabilidad no encontrado');
        }
        
        $titulo = "Editar entrada de contabilidad";
        $sucursales = Sucursal::all();
        
        return view('sistema.contabilidad-nuevo', compact('titulo', 'sucursales', 'contabilidad'));
    }

    public function guardar(Request $request)
    {
  
        $request->validate([
            'txtFecha' => 'required|date',
            'lstTipoMovimiento' => 'required|in:INGRESO,EGRESO,TRANSFERENCIA',
            'txtMonto' => 'required|numeric|min:0.01',
            'lstSucursal' => 'nullable|exists:sucursals,idsucursal',
            'txtReferenciaId' => 'nullable|integer',
            'txtDescripcion' => 'nullable|string|max:500',
        ]);
        
        $id = $request->input('id');
        $fecha = $request->input('txtFecha');
        $tipoMovimiento = $request->input('lstTipoMovimiento');
        $monto = $request->input('txtMonto');
        $idSucursal = $request->input('lstSucursal', null);
        $referenciaId = $request->input('txtReferenciaId', null);
        $descripcion = $request->input('txtDescripcion', '');
        
        if ($id && $id != 0 && $id != '0') {
            // Actualizar registro existente
            DB::table('contabilidads')
                ->where('idcontabilidad', $id)
                ->update([
                    'fecha_transaccion' => $fecha,
                    'tipo_movimiento' => $tipoMovimiento,
                    'monto' => $monto,
                    'fk_id_sucursal' => $idSucursal,
                    'referencia_id' => $referenciaId,
                    'descripcion' => $descripcion,
                    'updated_at' => now()
                ]);
                
            $mensaje = 'Registro de contabilidad actualizado correctamente';
        } else {
            // Crear nuevo registro
            DB::table('contabilidads')->insert([
                'fecha_transaccion' => $fecha,
                'tipo_movimiento' => $tipoMovimiento,
                'monto' => $monto,
                'fk_id_sucursal' => $idSucursal,
                'referencia_id' => $referenciaId,
                'descripcion' => $descripcion,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $mensaje = 'Registro de contabilidad creado correctamente';
        }
        
        return redirect()->route('contabilidad.listado')->with('mensaje', $mensaje);
    }
    
    public function eliminar(Request $request)
    {
        $id = $request->input('id');
        
        if ($id) {
            $deleted = DB::table('contabilidads')->where('idcontabilidad', $id)->delete();
            
            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Registro de contabilidad eliminado correctamente']);
            } else {
                return response()->json(['success' => false, 'message' => 'No se pudo eliminar el registro de contabilidad']);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'ID no proporcionado']);
    }
    
    public function cargarGrilla()
    {
        $contabilidad = DB::table('contabilidads')
            ->leftJoin('sucursals', 'contabilidads.fk_id_sucursal', '=', 'sucursals.idsucursal')
            ->select('contabilidads.*', 'sucursals.nombre as nombre_sucursal')
            ->get();
        
        $data = [];
        foreach ($contabilidad as $aContabilidad) {
            // Crear botones de acción
            $acciones = '<a href="/admin/contabilidad/editar/' . $aContabilidad->idcontabilidad . '" title="Editar" class="btn-accion"><i class="fa-solid fa-edit"></i></a>';
            
            $nombreSucursal = $aContabilidad->nombre_sucursal ? $aContabilidad->nombre_sucursal : 'Sin sucursal';
            
            $data[] = [
                $aContabilidad->fecha_transaccion,
                $aContabilidad->tipo_movimiento,
                $aContabilidad->monto,
                $nombreSucursal, // Mostrar el nombre de la sucursal en lugar del ID
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
}