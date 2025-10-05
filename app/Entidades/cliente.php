<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; // Necesario para la seguridad de la clave

class Cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = false; 
    
    // Indica la clave primaria de la tabla
    protected $primaryKey = 'idcliente'; 

    protected $fillable = [
        'idcliente',
        'nombre',
        'correo', 
        'celular', // Mantenemos el nombre de la DB, aunque el form use 'telefono'
        'clave',
    ];

    protected $hidden = [
        'clave', // Oculta la clave al convertir el modelo a JSON o array por seguridad
    ];
    
    // Nota: El formulario usa 'correo' y 'celular', pero el modelo anterior usaba 'email' y 'celular'.
    // He ajustado para que coincida con el formulario: correo -> correo, telefono -> celular.

    public function cargarDesdeRequest(Request $request)
    {
        $this->idcliente = $request->input('id') != "0" ? $request->input('id') : $this->idcliente;
        $this->nombre = $request->input('nombre'); // Usa 'nombre' del form
        $this->correo = $request->input('correo'); // Usa 'correo' del form
        $this->celular = $request->input('celular'); // Usa 'celular' del form
        
        // La clave solo se asigna si se proporciona una nueva (en caso de edición o inserción)
        if ($request->filled('clave')) {
             // ¡SEGURIDAD CRÍTICA! Hashea la clave antes de asignarla
            $this->clave = Hash::make($request->input('clave'));
        }
    }

    /**
     * Obtnener registros de clientes y filtrar para DataTables.
     * @param Request $request
     * @return array
     */
    public function obtenerFiltrado(Request $request)
    {
        // 🚨 VULNERABILIDAD CORREGIDA: Se usa Query Builder para consultas seguras
        $query = DB::table('clientes as A')
            ->select('A.idcliente', 'A.nombre', 'A.correo', 'A.celular', 'A.clave');

        // Realiza el filtrado (search) de forma segura
        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('A.nombre', 'LIKE', "%{$searchValue}%")
                  ->orWhere('A.correo', 'LIKE', "%{$searchValue}%")
                  ->orWhere('A.celular', 'LIKE', "%{$searchValue}%");
            });
        }
        
        // Ordenamiento
        $columns = ['A.idcliente', 'A.nombre', 'A.correo', 'A.celular', 'A.clave'];
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');

        if (isset($orderColumn) && isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        } else {
            $query->orderBy('A.nombre', 'asc');
        }

        return $query->get();
    }

    public function obtenerTodos()
    {
        // Consulta simple y segura
        $sql = "SELECT idcliente, nombre FROM clientes ORDER BY nombre";
        return DB::select($sql);
    }

    public function obtenerPorId($idcliente)
    {
        // 🚨 VULNERABILIDAD CORREGIDA: Se usa consulta preparada con placeholder (?)
        $sql = "SELECT idcliente, nombre, correo, celular, clave FROM clientes WHERE idcliente = ?";
        $lstRetorno = DB::select($sql, [$idcliente]);

        if (count($lstRetorno) > 0) {
            $this->idcliente = $lstRetorno[0]->idcliente;
            $this->nombre = $lstRetorno[0]->nombre;
            $this->correo = $lstRetorno[0]->correo;
            $this->celular = $lstRetorno[0]->celular;
            $this->clave = $lstRetorno[0]->clave;
            return $this;
        }
        return null;
    }

    public function guardar() {
        // 🚨 VULNERABILIDAD CORREGIDA: Se usa consulta preparada
        $sql = "UPDATE clientes SET
            nombre=?,
            correo=?,
            celular=?,
            clave=?
            WHERE idcliente=?";
        
        // Nota: Si la clave no fue hasheada en cargarDesdeRequest, este método fallará.
        DB::update($sql, [
            $this->nombre,
            $this->correo,
            $this->celular,
            $this->clave,
            $this->idcliente
        ]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM clientes WHERE idcliente=?";
        DB::delete($sql, [$this->idcliente]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO clientes (
            nombre, correo, celular, clave
        ) VALUES (?, ?, ?, ?)";
        
        DB::insert($sql, [
            $this->nombre,
            $this->correo,
            $this->celular,
            $this->clave
        ]);
        
        return $this->idcliente = DB::getPdo()->lastInsertId();
    }
}