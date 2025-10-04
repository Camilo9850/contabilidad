<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Entidades\Sucursal; // Asegúrate de importar el modelo Sucursal

class Contabilidad extends Model
{
    protected $table = 'contabilidads';
    protected $primaryKey = 'idcontabilidad';
    public $incrementing = true;
    public $timestamps = true; 

    protected $fillable = [
        'fecha_transaccion',
        'tipo_movimiento',
        'monto',
        'descripcion',
        'referencia_id',
        'fk_id_sucursal',
    ];

    protected $casts = [
        'fecha_transaccion' => 'date',
        'monto' => 'decimal:2', // Castea el DECIMAL a un número con 2 decimales
    ];

    /**
     * Define la relación con la Sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'fk_id_sucursal', 'idsucursal');
    }
    
    // Aquí iría el método cargarDesdeRequest, insertar, etc.
}