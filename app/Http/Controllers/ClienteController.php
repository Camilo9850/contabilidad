<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entidades\Cliente as EntidadCliente; // Usar alias para evitar conflictos
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $titulo = "Clientes";
        $cliente = new EntidadCliente();
        $clientes = $cliente->obtenerTodos(); // Obtener todos los clientes usando la entidad
        return view('sistema.cliente-nuevo', compact('titulo', 'clientes'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:50',
            'dni' => 'nullable|string|max:50',
            'celular' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:50',
            'clave' => 'nullable|string|max:150',
        ]);

        $cliente = new EntidadCliente();
        $cliente->nombre = $request->input('nombre');
        $cliente->apellido = $request->input('apellido');
        $cliente->telefono = $request->input('telefono');
        $cliente->direccion = $request->input('direccion');
        $cliente->dni = $request->input('dni');
        $cliente->celular = $request->input('celular');
        $cliente->correo = $request->input('correo');
        
        // Encriptar la clave si se proporciona
        $clave = $request->input('clave');
        if (!empty($clave)) {
            $cliente->clave = Hash::make($clave);
        }
        
        $cliente->insertar();

        $mensaje = 'Cliente creado correctamente';
        
        return redirect()->route('cliente.index')->with('mensaje', $mensaje);
    }

    public function actualizar(Request $request, $idcliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:50',
            'dni' => 'nullable|string|max:50',
            'celular' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:50',
            'clave' => 'nullable|string|max:150',
        ]);

        $cliente = new EntidadCliente();
        $cliente->idcliente = $idcliente;
        $cliente->nombre = $request->input('nombre');
        $cliente->apellido = $request->input('apellido');
        $cliente->telefono = $request->input('telefono');
        $cliente->direccion = $request->input('direccion');
        $cliente->dni = $request->input('dni');
        $cliente->celular = $request->input('celular');
        $cliente->correo = $request->input('correo');
        
        $clave = $request->input('clave');
        if (!empty($clave)) {
            $cliente->clave = Hash::make($clave);
        }
        
        $cliente->guardar();

        $mensaje = 'Cliente actualizado correctamente';
        
        return redirect()->route('cliente.index')->with('mensaje', $mensaje);
    }

    public function eliminar(Request $request)
    {
        $idcliente = $request->input('id');
        
        $cliente = new EntidadCliente();
        $cliente->idcliente = $idcliente;
        $cliente->obtenerPorId($idcliente);
        $cliente->eliminar();

        $mensaje = 'Cliente eliminado correctamente';
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $mensaje
            ]);
        }
        
        return redirect()->route('cliente.index')->with('mensaje', $mensaje);
    }
    
    public function cargarGrilla(Request $request)
    {
        $cliente = new EntidadCliente();
        $clientes = $cliente->obtenerFiltrado($request);
        
        return response()->json([
            'data' => $clientes
        ]);
    }
    
    public function editar($idcliente)
    {
        $titulo = "Editar Cliente";
        $cliente = new EntidadCliente();
        $clienteData = $cliente->obtenerPorId($idcliente);
        
        $clientes = [$clienteData]; // Para mantener la misma estructura que el index
        
        return view('sistema.cliente-nuevo', compact('titulo', 'clienteData', 'clientes'));
    }
}