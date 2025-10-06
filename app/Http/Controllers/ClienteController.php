<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entidades\Cliente as EntidadCliente; // Usar alias para evitar conflictos
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    public function index()
    {
        $titulo = "Clientes";

        $clienteData = null; // Inicializar para la vista
        return view('sistema.cliente-nuevo', compact('titulo',));
    }

    public function guardar(Request $request)
    {

        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'correo' => 'required|string|email',
            'clave' => 'required|string|min:6',
            'apellido' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:50',
            'celular' => 'nullable|string|max:50',
        ]);


        // Retornar mensajes de error si la validación falla
        if ($validator->fails()) {

            return redirect()->back()->with('error', $validator->errors());
        }

        // Crear el nuevo cliente
        $cliente = EntidadCliente::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'clave' => bcrypt($request->clave),
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'celular' => $request->celular,
        ]);

        $mensaje = 'Cliente creado correctamente';

        return redirect()->route('cliente.listar')->with('mensaje', $mensaje);
    }

    public function listar()
    {
        $titulo = "Clientes";
        $clientes = EntidadCliente::all(); // Obtener todos los clientes
        return view('sistema.cliente-listar', compact('titulo', 'clientes'));
    }

    public function cargarGrilla()
    {
        $request = $_REQUEST;
        $entidadCliente = new EntidadCliente();

        // Esto es para el DataTables que carga los datos de forma dinámica
        $clientes = $entidadCliente->all(); // Aquí podrías aplicar filtros

        $data = array();

        // Verificar si los índices existen en el array antes de usarlos
        $inicio = isset($request['start']) ? intval($request['start']) : 0;
        $registros_por_pagina = isset($request['length']) ? intval($request['length']) : -1; // -1 indica que no hay límite

        if (count($clientes) > 0) {
            $cont = 0;
            for ($i = $inicio; $i < count($clientes) && ($registros_por_pagina === -1 || $cont < $registros_por_pagina); $i++) {
                $row = array();
                $row[] = $clientes[$i]->idcliente;
                $row[] = $clientes[$i]->nombre;
                $row[] = $clientes[$i]->apellido;
                $row[] = $clientes[$i]->correo;
                $row[] = $clientes[$i]->telefono;
                $row[] = $clientes[$i]->celular;
                $row[] = $clientes[$i]->direccion;

                // Añadir columna de acciones
                $acciones = '<a href="' . route('cliente.editar', ['idcliente' => $clientes[$i]->id]) . '" class="btn btn-primary btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="' . route('cliente.eliminar', ['idcliente' => $clientes[$i]->id]) . '" style="display: inline-block;" 
                                onsubmit="return confirm(\'¿Estás seguro de que deseas eliminar este cliente?\')">
                                ' . csrf_field() . '
                                ' . method_field('POST') . '
                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>';
                $row[] = $acciones;

                $data[] = $row;
                $cont++;
            }
        }

        $json_data = array(
            "draw" => isset($request['draw']) ? intval($request['draw']) : 0,
            "recordsTotal" => count($clientes),
            "recordsFiltered" => count($clientes),
            "data" => $data
        );

        return response()->json($json_data);
    }

    public function editar($idcliente)
    {
        $titulo = "Clientes";
        $cliente = EntidadCliente::find($idcliente);

        if (!$cliente) {
            return redirect()->route('cliente.listar')->with('error', 'Cliente no encontrado');
        }

        return view('sistema.cliente-nuevo', compact('titulo', 'cliente'));
    }

    public function eliminar(Request $request, $idcliente)
    {
        try {
            $cliente = EntidadCliente::find($idcliente);

            if (!$cliente) {
                return response()->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
            }

            $cliente->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Cliente eliminado correctamente']);
            }

            return redirect()->route('cliente.listar')->with('mensaje', 'Cliente eliminado correctamente');
        } catch (\Exception $e) {
            \Log::error('Error eliminando cliente: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error al eliminar cliente'], 500);
            }

            return redirect()->route('cliente.listar')->with('error', 'Error al eliminar cliente');
        }
    }

    public function actualizar(Request $request, $idcliente)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'correo' => 'required|string|email',
            'clave' => 'nullable|string|min:6', // La clave es opcional al actualizar
            'apellido' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:50',
            'celular' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors());
        }

        $cliente = EntidadCliente::find($idcliente);

        if (!$cliente) {
            return redirect()->route('cliente.listar')->with('error', 'Cliente no encontrado');
        }

        // Actualizar los campos del cliente
        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->correo = $request->correo;
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;
        $cliente->celular = $request->celular;

        // Solo actualizar la clave si se proporciona una nueva
        if ($request->filled('clave')) {
            $cliente->clave = bcrypt($request->clave);
        }

        $cliente->save();

        return redirect()->route('cliente.listar')->with('mensaje', 'Cliente actualizado correctamente');
    }
}
