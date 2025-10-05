<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entidades\Cliente as EntidadCliente; // Usar alias para evitar conflictos
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function index()
    {
        $titulo = "Clientes";
       
        $clienteData = null; // Inicializar para la vista
        return view('sistema.cliente-nuevo', compact('titulo',  ));
    }

  public function guardar(Request $request)
{
  
  // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
             'correo' => 'required|string|email', 
            'clave' => 'required|string|min:6',
             'apellido' => 'required|string|max:50',
            'telefono' => 'required|string|max:50',
            'direccion' => 'required|string|max:50',
            'celular' => 'required|string|max:50',
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
        
        return redirect()->route('cliente.index')->with('mensaje', $mensaje);
 
}

 


    
  
  
}