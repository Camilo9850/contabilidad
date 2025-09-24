<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    public $timestamps = false;

    protected $fillable = [
        'idestado',
        'nombre',
    ];

    protected $hidden = [];

    public function cargarDesdeRequest($request)
    {
        $this->idestado = $request->input('id') != "0" ? $request->input('id') : $this->idestado;
        $this->nombre = $request->input('txtNombre');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                A.idestado,
                A.nombre
                FROM estados A
                ORDER BY A.nombre";
        return DB::select($sql);
    }

    public function obtenerPorId($idestado)
    {
        $sql = "SELECT
                idestado,
                nombre
                FROM estados WHERE idestado = ?";
        $lstRetorno = DB::select($sql, [$idestado]);

        if (count($lstRetorno) > 0) {
            $this->idestado = $lstRetorno[0]->idestado;
            $this->nombre = $lstRetorno[0]->nombre;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE estados SET
                nombre=?
                WHERE idestado=?";
        DB::update($sql, [
            $this->nombre,
            $this->idestado
        ]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM estados WHERE idestado=?";
        DB::delete($sql, [$this->idestado]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO estados (nombre) VALUES (?);";
        DB::insert($sql, [$this->nombre]);
        return $this->idestado = DB::getPdo()->lastInsertId();
    }
}