<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    
    // Especificar el nombre de la clave primaria
    protected $primaryKey = 'id';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion', 
        'precio',
        'cantidad',
        'imagen'
    ];

    protected $guarded = [];

    protected $hidden = [
       
    ];


}