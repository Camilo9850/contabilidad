<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';
    public $timestamps = false;

    protected $fillable = [
        'idcarrito',
        'fk_idcliente',
    ];

    protected $hidden = [];

    public function cargarDesdeRequest($request)
    {
        $this->idcarrito = $request->input('idcarrito') != "0" ? $request->input('idcarrito') : $this->idcarrito;
        $this->fk_idcliente = $request->input('fk_idcliente');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                  A.idcarrito,
                  A.fk_idcliente
                FROM carritos A
                ORDER BY A.idcarrito";
        return DB::select($sql);
    }

    public function obtenerPorId($idcarrito)
    {
        $sql = "SELECT
                idcarrito,
                fk_idcliente
                FROM carritos WHERE idcarrito = ?";
        $lstRetorno = DB::select($sql, [$idcarrito]);

        if (count($lstRetorno) > 0) {
            $this->idcarrito = $lstRetorno[0]->idcarrito;
            $this->fk_idcliente = $lstRetorno[0]->fk_idcliente;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE carritos SET
            fk_idcliente=?
            WHERE idcarrito=?";
        DB::update($sql, [$this->fk_idcliente, $this->idcarrito]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM carritos WHERE idcarrito=?";
        DB::delete($sql, [$this->idcarrito]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO carritos (fk_idcliente) VALUES (?);";
        DB::insert($sql, [$this->fk_idcliente]);
        return $this->idcarrito = DB::getPdo()->lastInsertId();
    }
}
