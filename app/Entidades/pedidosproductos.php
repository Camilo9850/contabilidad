<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
    protected $table = 'pedidosproductos';
    public $timestamps = false;

    protected $fillable = [
        'idpedidoproducto',
        'fk_idpedido',
        'fk_idproducto',
        'cantidad',
        'precio_unitario',
        'total'
    ];

    public function cargarDesdeRequest($request)
    {
        $this->idpedidoproducto = $request->input('id') != "0" ? $request->input('id') : null;
        $this->fk_idpedido = $request->input('lstPedido');
        $this->fk_idproducto = $request->input('lstProducto');
        $this->cantidad = $request->input('txtCantidad');
        $this->precio_unitario = $request->input('txtPrecioUnitario');
        $this->total = $request->input('txtTotal');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                A.idpedidoproducto,
                A.fk_idpedido,
                A.fk_idproducto,
                A.cantidad,
                A.precio_unitario,
                A.total
                FROM pedidosproductos A
                ORDER BY A.idpedidoproducto DESC";
        return DB::select($sql);
    }
    
    public function obtenerPorId($idpedidoproducto)
    {
        $sql = "SELECT
                idpedidoproducto,
                fk_idpedido,
                fk_idproducto,
                cantidad,
                precio_unitario,
                total
                FROM pedidosproductos WHERE idpedidoproducto = ?";
        $lstRetorno = DB::select($sql, [$idpedidoproducto]);

        if (count($lstRetorno) > 0) {
            $this->idpedidoproducto = $lstRetorno[0]->idpedidoproducto;
            $this->fk_idpedido = $lstRetorno[0]->fk_idpedido;
            $this->fk_idproducto = $lstRetorno[0]->fk_idproducto;
            $this->cantidad = $lstRetorno[0]->cantidad;
            $this->precio_unitario = $lstRetorno[0]->precio_unitario;
            $this->total = $lstRetorno[0]->total;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE pedidosproductos SET
                fk_idpedido=?,
                fk_idproducto=?,
                cantidad=?,
                precio_unitario=?,
                total=?
                WHERE idpedidoproducto=?";
        DB::update($sql, [
            $this->fk_idpedido,
            $this->fk_idproducto,
            $this->cantidad,
            $this->precio_unitario,
            $this->total,
            $this->idpedidoproducto
        ]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM pedidosproductos WHERE idpedidoproducto=?";
        DB::delete($sql, [$this->idpedidoproducto]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO pedidosproductos (
                fk_idpedido,
                fk_idproducto,
                cantidad,
                precio_unitario,
                total
            ) VALUES (?, ?, ?, ?, ?)";
        DB::insert($sql, [
            $this->fk_idpedido,
            $this->fk_idproducto,
            $this->cantidad,
            $this->precio_unitario,
            $this->total
        ]);
        return $this->idpedidoproducto = DB::getPdo()->lastInsertId();
    }
}