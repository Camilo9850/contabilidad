<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SucursalController extends Controller
{
    public function index()
    {
        $titulo = "Nueva sucursal";
        $sucursal = null; // Para nueva sucursal
        return view('sistema.sucursal-nuevo', compact('sucursal', 'titulo'));
    }
    
    public function listar()
    {
        $titulo = "Listado de Sucursales";
        return view('sistema.sucursal-listar', compact('titulo'));
    }
    
    public function cargarGrilla()
    {
        $sucursales = DB::table('sucursals')->get();
        
        $data = [];
        foreach ($sucursales as $sucursal) {
            $activoText = $sucursal->activo ? 'Sí' : 'No';
            
            // Crear botones de acción
            $acciones = '<a href="/admin/sucursal/nuevo/' . $sucursal->idsucursal . '" title="Editar" class="btn-accion"><i class="fa-solid fa-edit"></i></a>';
            
            $data[] = [
                $sucursal->nombre,
                $sucursal->direccion,
                $sucursal->telefono,
                $sucursal->email,
                $sucursal->ciudad,
                $sucursal->estado,
                $activoText,
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
    
    public function editar($id)
    {
        $sucursal = DB::table('sucursals')->where('idsucursal', $id)->first();
        
        if (!$sucursal) {
            abort(404, 'Sucursal no encontrada');
        }
        
        // Cargar los datos en la sesión para que estén disponibles en la vista
        session(['sucursal_editar' => $sucursal]);
        
        $titulo = "Editar sucursal";
        
        return view('sistema.sucursal-nuevo', compact('sucursal', 'titulo'));
    }
    
    public function guardar(Request $request, $id = null)
    {
        $nombre = $request->input('txtNombre');
        $direccion = $request->input('txtDireccion');
        $telefono = $request->input('txtTelefono');
        $email = $request->input('txtEmail');
        $ciudad = $request->input('txtCiudad');
        $estado = $request->input('txtEstado');
        $activo = $request->input('lstActivo', 0);
        
        $request->validate([
            'txtNombre' => 'required|string|max:255',
            'txtDireccion' => 'required|string|max:255',
            'txtTelefono' => 'required|string|max:50',
            'txtEmail' => 'required|email|max:100',
            'txtCiudad' => 'required|string|max:100',
            'txtEstado' => 'required|string|max:50',
        ]);
        
        if ($id && $id != 0 && $id != '0') {
            // Actualizar sucursal existente
            DB::table('sucursals')
                ->where('idsucursal', $id)
                ->update([
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'email' => $email,
                    'ciudad' => $ciudad,
                    'estado' => $estado,
                    'activo' => $activo
                ]);
                
            $mensaje = 'Sucursal actualizada correctamente';
        } else {
            // Crear nueva sucursal
            DB::table('sucursals')->insert([
                'nombre' => $nombre,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'email' => $email,
                'ciudad' => $ciudad,
                'estado' => $estado,
                'activo' => $activo
            ]);
            
            $mensaje = 'Sucursal creada correctamente';
        }
        
        return redirect()->route('sucursal.listado')->with('mensaje', $mensaje);
    }
    
    public function eliminar(Request $request)
    {
        if ($request->isMethod('post')) {
            // Procesar la eliminación
            $id = $request->input('id');
            
            if ($id) {
                $deleted = DB::table('sucursals')->where('idsucursal', $id)->delete();
                
                if ($deleted) {
                    return response()->json(['success' => true, 'message' => 'Sucursal eliminada correctamente']);
                } else {
                    return response()->json(['success' => false, 'message' => 'No se pudo eliminar la sucursal']);
                }
            }
        } else {
            // Mostrar confirmación de eliminación - redirigir al listado
            $id = $request->input('id');
            if ($id) {
                $sucursal = DB::table('sucursals')->where('idsucursal', $id)->first();
                
                if ($sucursal) {
                    DB::table('sucursals')->where('idsucursal', $id)->delete();
                    return redirect()->route('sucursal.listado')->with('mensaje', 'Sucursal eliminada correctamente');
                }
            }
            
            return redirect()->route('sucursal.listado')->with('error', 'Sucursal no encontrada');
        }
    }
}