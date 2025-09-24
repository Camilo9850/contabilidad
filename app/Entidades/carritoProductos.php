<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CarritoProducto extends Model
{
    protected $table = 'carritoproductos';
    public $timestamps = false;

    protected $fillable = [
        'idcarritoproducto',
        'fk_idproducto',
        'fk_idcarrito',
        'cantidad',
    ];

    public function cargarDesdeRequest($request)
    {
        $this->idcarritoproducto = $request->input('id') != "0" ? $request->input('id') : null;
        $this->fk_idproducto = $request->input('fk_idproducto');
        $this->fk_idcarrito = $request->input('fk_idcarrito');
        $this->cantidad = $request->input('cantidad');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                A.idcarritoproducto,
                A.fk_idproducto,
                A.fk_idcarrito,
                A.cantidad
                FROM carritoproductos A
                ORDER BY A.idcarritoproducto DESC";
        return DB::select($sql);
    }
    
    // Obtiene todos los productos de un carrito específico
    public function obtenerPorCarrito($idcarrito)
    {
        $sql = "SELECT
                A.idcarritoproducto,
                A.fk_idproducto,
                A.fk_idcarrito,
                A.cantidad,
                B.nombre AS producto_nombre
                FROM carritoproductos A
                INNER JOIN productos B ON A.fk_idproducto = B.idproducto
                WHERE A.fk_idcarrito = ?";
        return DB::select($sql, [$idcarrito]);
    }

    public function obtenerPorId($idcarritoproducto)
    {
        $sql = "SELECT
                idcarritoproducto,
                fk_idproducto,
                fk_idcarrito,
                cantidad
                FROM carritoproductos WHERE idcarritoproducto = ?";
        $lstRetorno = DB::select($sql, [$idcarritoproducto]);

        if (count($lstRetorno) > 0) {
            $this->idcarritoproducto = $lstRetorno[0]->idcarritoproducto;
            $this->fk_idproducto = $lstRetorno[0]->fk_idproducto;
            $this->fk_idcarrito = $lstRetorno[0]->fk_idcarrito;
            $this->cantidad = $lstRetorno[0]->cantidad;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE carritoproductos SET
                fk_idproducto=?,
                fk_idcarrito=?,
                cantidad=?
                WHERE idcarritoproducto=?";
        DB::update($sql, [
            $this->fk_idproducto,
            $this->fk_idcarrito,
            $this->cantidad,
            $this->idcarritoproducto,
        ]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM carritoproductos WHERE idcarritoproducto=?";
        DB::delete($sql, [$this->idcarritoproducto]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO carritoproductos (
                fk_idproducto,
                fk_idcarrito,
                cantidad
            ) VALUES (?, ?, ?)";
        DB::insert($sql, [
            $this->fk_idproducto,
            $this->fk_idcarrito,
            $this->cantidad,
        ]);
        return $this->idcarritoproducto = DB::getPdo()->lastInsertId();
    }
}