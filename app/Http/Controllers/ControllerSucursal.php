<?php

namespace App\Http\Controllers;

use App\Entidades\Sucursal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
require app_path() . '/start/constants.php';

class ControllerSucursal extends Controller
{
    // Método para mostrar la lista de sucursales
    public function index()
    {
        $titulo = "Listado de Sucursales";
        $sucursalesList = Sucursal::all();
        return view('sistema.sucursal-listar', compact('titulo', 'sucursalesList'));
    }

    // Método para mostrar el formulario de nueva sucursal
    public function nuevo()
    {
        $titulo = "Nueva sucursal";
        $sucursal = new Sucursal();
        return view('sistema.sucursal-nuevo', compact('titulo', 'sucursal'));
    }

    // Método para mostrar el formulario de edición de una sucursal
    public function editar($id)
    {
        $titulo = "Editar sucursal";
        $sucursal = Sucursal::find($id);
        if (!$sucursal) {
            return redirect()->route('sucursal.index')->with('error', 'Sucursal no encontrada');
        }
        return view('sistema.sucursal-nuevo', compact('titulo', 'sucursal'));
    }

    // Método para guardar (crear o actualizar) una sucursal
    public function guardar(Request $request)
    {
        try {
            // Verificar si es una actualización o creación
            if ($request->input('id') > 0) {
                // Actualización
                $sucursal = Sucursal::find($request->input('id'));
                if (!$sucursal) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = MSG_NOEXIST;
                    return back()->with('msg', $msg)->withInput();
                }
            } else {
                // Nuevo registro
                $sucursal = new Sucursal();
            }

            // Cargar datos desde request
            $sucursal->cargarDesdeRequest($request);
            
            // Validar campos requeridos
            if (empty($request->input('txtNombre'))) {
                $msg["ESTADO"] = MSG_ERROR;
                $msg["MSG"] = CAMPOOBLIGATORIO;
                return back()->with('msg', $msg)->withInput();
            }
            
            // Guardar
            $sucursal->save();

            if ($request->input('id') > 0) {
                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = "Sucursal actualizada correctamente";
            } else {
                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = "Sucursal creada correctamente";
            }

            return redirect()->route('sucursal.index')->with('msg', $msg);
        } catch (\Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "Error al guardar la sucursal: " . $e->getMessage();
            return back()->with('msg', $msg)->withInput();
        }
    }

    // Método para eliminar una sucursal
    public function eliminar($id)
    {
        try {
            $sucursal = Sucursal::find($id);
            
            if ($sucursal) {
                $sucursal->delete();
                return response()->json(['err' => '0', 'msg' => 'Registro eliminado exitosamente.']);
            } else {
                return response()->json(['err' => '1', 'msg' => 'Sucursal no encontrada.']);
            }
        } catch (\Exception $e) {
            return response()->json(['err' => '1', 'msg' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }
}


