<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entidades\Pedido;
use App\Entidades\Cliente as EntidadCliente;
use App\Entidades\Sucursal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PedidosController extends Controller
{
    public function index()
    {
        $titulo = "Nuevo pedido";
        $pedido = new Pedido(); // Objeto vacío para la vista

        $clientes = EntidadCliente::all();
        $sucursales = Sucursal::all();

        return view('sistema.pedido-nuevo', compact('titulo', 'pedido', 'clientes', 'sucursales'));
    }

    public function listar()
    {
        $titulo = "Listado de pedidos";
       $pedidos = Pedido::with('cliente')->get();


        return view('sistema.pedido-listar', compact('titulo', 'pedidos'));
    }

    public function guardar(Request $request)
    {
        
        try {
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer|exists:clientes,id',
                'estado' => 'required|integer|in:1,2,3,4',
                'hora' => 'required|date_format:H:i',
                'fecha' => 'required|date',
                'total' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $pedido = new Pedido();
            $pedido->cliente_id = $request->cliente_id;
            $pedido->estado = $request->estado;
            $pedido->hora = $request->hora;
            $pedido->fecha = $request->fecha;
            $pedido->total = $request->total;
            
            // Si se requiere fk_usuario, se puede obtener del usuario autenticado o establecer un valor por defecto
            // $pedido->fk_usuario = auth()->user()->id ?? 1; // Descomentar y ajustar según sea necesario
            
            $pedido->save();

            return redirect()->route('pedido.listar')->with('msg', [
                'MSG' => 'Pedido creado correctamente.',
                'ESTADO' => 'success'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear pedido', ['error' => $e->getMessage()]);
            return redirect()->back()->with('msg', [
                'MSG' => 'Error al crear el pedido: ' . $e->getMessage(),
                'ESTADO' => 'error'
            ])->withInput();
        }
    }

    public function actualizar(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer|exists:clientes,id',
                'estado' => 'required|integer|in:1,2,3,4',
                'fecha' => 'required|date',
                'hora' => 'nullable|date_format:H:i',
                'subtotal' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $pedido = Pedido::find($id);

            if (!$pedido) {
                return redirect()->back()->with('msg', [
                    'MSG' => 'Pedido no encontrado.',
                    'ESTADO' => 'error'
                ])->withInput();
            }

            $pedido->fk_cliente = $request->cliente_id;
            $pedido->fk_idestadopedido = $request->estado;
            $pedido->fecha = $request->fecha;
            $pedido->hora = $request->hora;
            $pedido->subtotal = $request->subtotal;
            $pedido->total = $request->total;
            
            $pedido->save();

            return redirect()->route('pedido.listar')->with('msg', [
                'MSG' => 'Pedido actualizado correctamente.',
                'ESTADO' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar pedido', ['error' => $e->getMessage()]);
            $msg = ['MSG' => 'Error al actualizar el pedido: ' . $e->getMessage(), 'ESTADO' => 'error'];
            return redirect()->back()->with('msg', $msg)->withInput();
        }
    }

    public function editar($id)
    {
        $titulo = "Editar pedido";
        $clientes = EntidadCliente::all();
        $sucursales = Sucursal::all();

        $pedido = Pedido::leftJoin('clientes', 'pedidos.fk_cliente', '=', 'clientes.id')
            ->select(
                'pedidos.idpedido',
                'pedidos.fk_cliente',
                'pedidos.fk_idestadopedido',
                'pedidos.fecha',
                'pedidos.hora',
                'pedidos.subtotal',
                'pedidos.total',
                'clientes.nombre as cliente_nombre'
            )
            ->where('pedidos.idpedido', $id)
            ->first();

        if (empty($pedido)) {
            return redirect()->route('pedido.listar')->with('msg', [
                'MSG' => 'Pedido no encontrado.',
                'ESTADO' => 'error'
            ]);
        }

        return view('sistema.pedido-nuevo', compact('titulo', 'pedido', 'clientes', 'sucursales'));
    }

    public function eliminar(Request $request, $id)
    {
        try {
            $pedido = Pedido::find($id);

            if (!$pedido) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
                }
                return redirect()->route('pedido.listar')->with('mensaje', 'Pedido no encontrado');
            }

            $pedido->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Pedido eliminado correctamente']);
            }

            return redirect()->route('pedido.listar')->with('mensaje', 'Pedido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error eliminando pedido: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error al eliminar pedido'], 500);
            }

            return redirect()->route('pedido.listar')->with('error', 'Error al eliminar pedido');
        }
    }
}
