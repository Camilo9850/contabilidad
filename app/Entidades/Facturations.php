<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; // Necesario para la seguridad de la clave

class Facturations extends Model
{
    protected $table = 'facturations';



   protected $guarded = [];
   

    protected $hidden = [
         // Oculta la clave al convertir el modelo a JSON o array por seguridad
    ];

public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    


}


    

// aqui se pondrían las relaciones hasmany, belongsTo... etc
