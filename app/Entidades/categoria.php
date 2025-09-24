<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    public $timestamps = false;

    protected $fillable = [
        'idcategoria',
        'nombre',
    ];

    protected $hidden = [];

    public function cargarDesdeRequest($request)
    {
        $this->idcategoria = $request->input('id') != "0" ? $request->input('id') : $this->idcategoria;
        $this->nombre = $request->input('txtNombre');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                A.idcategoria,
                A.nombre
                FROM categorias A
                ORDER BY A.nombre";
        return DB::select($sql);
    }

    public function obtenerPorId($idcategoria)
    {
        $sql = "SELECT
                idcategoria,
                nombre
                FROM categorias WHERE idcategoria = ?";
        $lstRetorno = DB::select($sql, [$idcategoria]);

        if (count($lstRetorno) > 0) {
            $this->idcategoria = $lstRetorno[0]->idcategoria;
            $this->nombre = $lstRetorno[0]->nombre;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE categorias SET
                nombre=?
                WHERE idcategoria=?";
        DB::update($sql, [
            $this->nombre,
            $this->idcategoria
        ]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM categorias WHERE idcategoria=?";
        DB::delete($sql, [$this->idcategoria]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO categorias (nombre) VALUES (?);";
        DB::insert($sql, [$this->nombre]);
        return $this->idcategoria = DB::getPdo()->lastInsertId();
    }


    public function eliminarPorCategoria()
    {
        $sql = "DELETE FROM categorias WHERE idcategoria = ?";
        DB::delete($sql, [$this->idcategoria]);
    }
    public function obtenerFiltrado($filtro)
{
    $query = DB::table('categorias')
        ->select('idcategoria', 'nombre');
        
    if (!empty($filtro['txtNombre'])) {
        $query->where('nombre', 'LIKE', '%' . $filtro['txtNombre'] . '%');
    }
    
    return $query->orderBy('nombre', 'ASC')->get();
}
}
