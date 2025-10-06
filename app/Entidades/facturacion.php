<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; // Necesario para la seguridad de la clave

class Facturacion extends Model
{
    protected $table = 'facturacions';

   protected $guarded = [];

    protected $hidden = [
         // Oculta la clave al convertir el modelo a JSON o array por seguridad
    ];

// aqui se pondrían las relaciones hasmany, belongsTo... etc

}