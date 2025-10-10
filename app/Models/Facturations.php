<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturations extends Model
{
    use HasFactory;
    
    protected $table = 'facturacions';
    // Usamos la clave primaria predeterminada 'id' en lugar de 'id_factura'
    
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
