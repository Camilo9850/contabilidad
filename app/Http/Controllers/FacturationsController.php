<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Facturations as Factura;
use App\Entidades\cliente as EntidadCliente;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;



class FacturationsController extends Controller
{
    public function index()
    {
        $titulo = "facturacion";
        
        // Obtener todos los clientes para mostrar en el select
        $clientes = EntidadCliente::all();
        
        // Crear una nueva instancia vacía de Factura para que la vista funcione correctamente
        $factura = new factura([
            'subtotal' => 0,
            'impuesto' => 0,
            'total_factura' => 0
        ]);
        $facturacion = $factura; // Para mantener compatibilidad con el JavaScript en la vista
        
        return view('sistema.facturacion-nuevo', compact('titulo', 'clientes', 'factura', 'facturacion'));
    }
    
    public function listar()
    {
        $titulo = "Listado de Facturación";
        $facturas = Factura::all();
        return view('sistema.facturacion-listar', compact('titulo','facturas'));
    }

  public function guardar(Request $request)
    {

       
        // ANTES - Registro de depuración inicial
        Log::info('=== INICIO PROCESO GUARDAR FACTURA ===');
        //info('Datos recibidos en request:', $request->all());
        //info('ID de factura recibido:', ['id' => $request->id]);
        
        // Validar los datos del formulario (sin los campos calculados)
        $rules = [
            'fecha' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'subtotal' => 'required|numeric|min:0',
            'estado' => 'required|in:PENDIENTE,PAGADA,ANULADA'
        ];

        

        //info('Reglas de validación aplicadas:', $rules);

        $validator = Validator::make($request->all(), $rules);

        // DURANTE - Validación
        if ($validator->fails()) {
            Log::error('=== VALIDACION FALLIDA ===');
            Log::error('Errores de validación:', $validator->errors()->toArray());
            Log::error('Datos recibidos:', $request->all());
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Log::info('=== VALIDACION EXITOSA ===');
        
        // Calcular impuesto y total factura
        Log::info('Calculando impuestos y totales...');
        $subtotal = floatval($request->subtotal);
        $porcentaje_impuesto = floatval($request->porcentaje_impuesto ?? 0);
        
        Log::info('Valores iniciales:', [
            'subtotal' => $subtotal,
            'porcentaje_impuesto' => $porcentaje_impuesto
        ]);
        
        // Si hay un porcentaje de impuesto, calcular los valores basados en el porcentaje
        if ($porcentaje_impuesto > 0) {
            $impuesto = ($subtotal * $porcentaje_impuesto) / 100;
            $total_factura = $subtotal + $impuesto;
            Log::info('Cálculo por porcentaje aplicado:', [
                'impuesto_calculado' => $impuesto,
                'total_calculado' => $total_factura
            ]);
        } else {
            // Si no se proporciona porcentaje, usar los valores directos del formulario
            $impuesto = floatval($request->impuesto ?? 0);
            $total_factura = floatval($request->total_factura ?? 0);
            
            // Si no se proporciona total_factura, calcularlo
            if ($total_factura == 0) {
                $total_factura = $subtotal + $impuesto;
                Log::info('Total calculado a partir de subtotal e impuesto');
            }
            
            Log::info('Valores usados del formulario:', [
                'impuesto_form' => $impuesto,
                'total_form' => $total_factura
            ]);
        }

        Log::info('Valores finales calculados:', [
            'subtotal_final' => $subtotal,
            'impuesto_final' => $impuesto,
            'total_final' => $total_factura
        ]);

        // Verificar si estamos actualizando o creando
        $operacion = $request->id > 0 ? 'ACTUALIZAR' : 'CREAR';
        Log::info("Operación a realizar: {$operacion}", [
            'es_actualizacion' => $request->id > 0,
            'id_factura' => $request->id
        ]);
        
        if ($request->id > 0) {
            // Actualizar factura existente
            $factura = Factura::find($request->id);
            if (!$factura) {
            Log::warning('Intento de actualizar factura inexistente', ['id' => $request->id]);
                return redirect()->route('facturacion.listar')
                    ->with('msg', ['MSG' => 'Factura no encontrada', 'ESTADO' => 'danger']);
            }
            
            
            $factura->update([
                'fecha' => $request->fecha,
                'cliente_id' => $request->cliente_id,
                'subtotal' => $subtotal,
                'impuesto' => $impuesto,
                'total_factura' => $total_factura,
                'estado' => $request->estado
            ]);
            
            $mensaje = 'Factura actualizada correctamente';
            Log::info('Factura actualizada exitosamente');
        } else {
            // Crear nueva factura
            Log::info('Creando nueva factura en DB:', [
                'datos_crear' => [
                    'numero_factura' => $request->numero_factura,
                    'fecha' => $request->fecha,
                    'cliente_id' => $request->cliente_id,
                    'subtotal' => $subtotal,
                    'impuesto' => $impuesto,
                    'total_factura' => $total_factura,
                    'estado' => $request->estado
                ]
            ]);
            /// aqui va a fallar siempre por el numero de factura
            $factura = Factura::create([
                'numero_factura' => Str::uuid(),
                'fecha' => $request->fecha,
                'cliente_id' => $request->cliente_id,
                'subtotal' => $subtotal,
                'impuesto' => $impuesto,
                'total_factura' => $total_factura,
                'estado' => $request->estado
            ]);
            
            $mensaje = 'Factura creada correctamente';
            Log::info('Factura creada exitosamente', ['id_creado' => $factura->id]);
        }

        // DESPUÉS - Finalización exitosa
        Log::info('=== PROCESO GUARDAR COMPLETADO ===', [
            'mensaje' => $mensaje,
            'factura' => $factura->id ?? 'no_disponible'
        ]);

        return redirect()->route('facturacion.listar')
            ->with('msg', ['MSG' => $mensaje, 'ESTADO' => 'success']);
    }

    public function editar($id)
    {
        $titulo = "Editar facturacion";
        $factura = Factura::find($id); // Removemos 'with(cliente)' para evitar errores
        $clientes = EntidadCliente::all();
        
        if (!$factura) {
            return redirect()->route('facturacion.listar')
                ->with('msg', ['MSG' => 'Factura no encontrada', 'ESTADO' => 'danger']);
        }
        
        // También pasamos $facturacion para mantener compatibilidad con el JavaScript en la vista
        $facturacion = $factura;
      
        
        return view('sistema.facturacion-nuevo', compact('titulo', 'factura', 'facturacion', 'clientes'));
    }

    public function eliminar(Request $request)
    {
        $id = $request->id;
        $factura = Factura::find($id);

        if (!$factura) {
            return response()->json(['success' => false, 'message' => 'Factura no encontrada']);
        }

        try {
            $factura->delete();
            return response()->json(['success' => true, 'message' => 'Factura eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar la factura: ' . $e->getMessage()]);
        }
    }
}