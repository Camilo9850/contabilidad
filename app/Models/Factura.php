<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturacions';
    protected $primaryKey = 'id_factura';
    protected $fillable = [
        'numero_factura',
        'fecha',
        'fk_id_cliente',
        'subtotal',
        'impuesto',
        'total_factura',
        'estado'
    ];

    public $timestamps = true;
    
    /**
     * Relación con el cliente
     */
    public function cliente()
    {
        return $this->belongsTo(\App\Entidades\cliente::class, 'fk_id_cliente', 'idcliente');
    }
}