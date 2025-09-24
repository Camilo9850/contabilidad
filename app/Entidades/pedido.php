<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    public $timestamps = false;

    protected $fillable = [
        'idpedido',
        'fecha',
        'descripcion',
        'total',
        'fk_idsucursal',
        'fk_idcliente',
        'fk_idestado',
    ];

    public function cargarDesdeRequest($request)
    {
        $this->idpedido = $request->input('id') != "0" ? $request->input('id') : null;
        $this->fecha = $request->input('txtFecha');
        $this->descripcion = $request->input('txtDescripcion');
        $this->total = $request->input('txtTotal');
        $this->fk_idsucursal = $request->input('lstSucursal');
        $this->fk_idcliente = $request->input('lstCliente');
        $this->fk_idestado = $request->input('lstEstado');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                A.idpedido,
                A.fecha,
                A.descripcion,
                A.total,
                A.fk_idsucursal,
                A.fk_idcliente,
                A.fk_idestado
                FROM pedidos A ORDER BY A.idpedido DESC";
        return DB::select($sql);
    }

    public function obtenerPorId($idpedido)
    {
        $sql = "SELECT
                idpedido,
                fecha,
                descripcion,
                total,
                fk_idsucursal,
                fk_idcliente,
                fk_idestado
                FROM pedidos WHERE idpedido = ?";
        $lstRetorno = DB::select($sql, [$idpedido]);

        if (count($lstRetorno) > 0) {
            $this->idpedido = $lstRetorno[0]->idpedido;
            $this->fecha = $lstRetorno[0]->fecha;
            $this->descripcion = $lstRetorno[0]->descripcion;
            $this->total = $lstRetorno[0]->total;
            $this->fk_idsucursal = $lstRetorno[0]->fk_idsucursal;
            $this->fk_idcliente = $lstRetorno[0]->fk_idcliente;
            $this->fk_idestado = $lstRetorno[0]->fk_idestado;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE pedidos SET
                fecha=?,
                descripcion=?,
                total=?,
                fk_idsucursal=?,
                fk_idcliente=?,
                fk_idestado=?
                WHERE idpedido=?";
        DB::update($sql, [
            $this->fecha,
            $this->descripcion,
            $this->total,
            $this->fk_idsucursal,
            $this->fk_idcliente,
            $this->fk_idestado,
            $this->idpedido
        ]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM pedidos WHERE idpedido=?";
        DB::delete($sql, [$this->idpedido]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO pedidos (
                fecha,
                descripcion,
                total,
                fk_idsucursal,
                fk_idcliente,
                fk_idestado
            ) VALUES (?, ?, ?, ?, ?, ?)";
        DB::insert($sql, [
            $this->fecha,
            $this->descripcion,
            $this->total,
            $this->fk_idsucursal,
            $this->fk_idcliente,
            $this->fk_idestado
        ]);
        return $this->idpedido = DB::getPdo()->lastInsertId();
    }
}