<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Carbon\Carbon; // Para manejar fechas y horas

class Pedido extends Model
{
    protected $table = 'pedidos';
    
    // La clave primaria en la DB es 'idpedido'
    protected $primaryKey = 'idpedido'; 
    
    // La tabla SÍ tiene las marcas de tiempo
    public $timestamps = true; 

    // Campos que existen en la tabla y que son asignables masivamente
    protected $fillable = [
        'cliente_id',
        'estado',
        'fk_usuario',
        'fecha',
        'total',
    ];

    // Casteo de tipos para manejo de fechas y decimales en PHP
    protected $casts = [
        'fecha' => 'date',
        'hora' => 'string',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    // ---------------------------------------------
    //  RELACIONES
    // ---------------------------------------------

    /**
     * Define la relación con el Cliente al que pertenece el pedido.
     */
    public function cliente()
    {
        // La tabla pedidos tiene fk_cliente que referencia a idcliente en la tabla clientes
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id'); 
    }
    
    // ---------------------------------------------
    //  MÉTODOS
    // ---------------------------------------------

    /**
     * Mapea los campos del Request a las propiedades del modelo.
     */
    public function cargarDesdeRequest(Request $request)
    {
        // El ID se maneja por 'idpedido'
        $this->idpedido = $request->input('id') != "0" ? $request->input('id') : $this->idpedido;
        
        // Mapeo de datos (usando nombres del formulario actualizados)
        $this->cliente_id = $request->input('cliente_id');
        $this->estado = $request->input('estado');
        $this->fecha = $request->input('fecha');
        $this->hora = $request->input('hora');
        $this->subtotal = $request->input('subtotal');
        $this->total = $request->input('total');
        // sigan al milo
        // O un valor por defecto si es necesario
    }
    
    /**
     * Guarda el pedido en la base de datos
     */
    public function guardar()
    {
        return $this->save();
    }
    
    /**
     * Obtiene un registro por su ID.
     */
    public function obtenerPorId($id)
    {
        // Usando Eloquent con la clave primaria correcta:
        return Pedido::where('idpedido', $id)->first();

    }
    

}