<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;

    protected $fillable = [
        'idproducto',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'fk_idcategoria'
    ];

    public function cargarDesdeRequest($request)
    {
        $this->idproducto = $request->input('id') != "0" ? $request->input('id') : null;
        $this->nombre = $request->input('txtNombre');
        $this->descripcion = $request->input('txtDescripcion');
        $this->precio = $request->input('txtPrecio');
        $this->imagen = $request->input('txtImagen');
        $this->fk_idcategoria = $request->input('lstCategoria');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                A.idproducto,
                A.nombre,
                A.descripcion,
                A.precio,
                A.imagen,
                A.fk_idcategoria
                FROM productos A
                ORDER BY A.nombre ASC";
        return DB::select($sql);
    }
    
    public function obtenerPorId($idproducto)
    {
        $sql = "SELECT
                idproducto,
                nombre,
                descripcion,
                precio,
                imagen,
                fk_idcategoria
                FROM productos WHERE idproducto = ?";
        $lstRetorno = DB::select($sql, [$idproducto]);

        if (count($lstRetorno) > 0) {
            $this->idproducto = $lstRetorno[0]->idproducto;
            $this->nombre = $lstRetorno[0]->nombre;
            $this->descripcion = $lstRetorno[0]->descripcion;
            $this->precio = $lstRetorno[0]->precio;
            $this->imagen = $lstRetorno[0]->imagen;
            $this->fk_idcategoria = $lstRetorno[0]->fk_idcategoria;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE productos SET
                nombre=?,
                descripcion=?,
                precio=?,
                imagen=?,
                fk_idcategoria=?
                WHERE idproducto=?";
        DB::update($sql, [
            $this->nombre,
            $this->descripcion,
            $this->precio,
            $this->imagen,
            $this->fk_idcategoria,
            $this->idproducto
        ]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM productos WHERE idproducto=?";
        DB::delete($sql, [$this->idproducto]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO productos (
                nombre,
                descripcion,
                precio,
                imagen,
                fk_idcategoria
            ) VALUES (?, ?, ?, ?, ?)";
        DB::insert($sql, [
            $this->nombre,
            $this->descripcion,
            $this->precio,
            $this->imagen,
            $this->fk_idcategoria
        ]);
        return $this->idproducto = DB::getPdo()->lastInsertId();
    }
}