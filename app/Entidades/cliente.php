<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = false;

    protected $fillable = [
        'idcliente',
        'nombre',
        'email',
        'celular',
        'clave',
    ];

    protected $hidden = [];
    public function cargarDesdeRequest($request)
    {
        $this->idcliente = $request->input('id') != "0" ? $request->input('id') : $this->idcliente;
        $this->nombre = $request->input('txtNombre');
        $this->email = $request->input('txtEmail');
        $this->celular = $request->input('txtCelular');
        $this->clave = $request->input('txtClave');
    }

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'A.nombre',
            1 => 'B.nombre',
            2 => 'A.email',
            3 => 'A.celular',
            4 => 'A.clave',
        );
        $sql = "SELECT DISTINCT
                    A.idcliente,
                    A.nombre,
                    A.email,
                    A.celular,
                    A.clave
                    FROM clientes A
                WHERE 1=1
                ";
                //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( A.nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR A.email LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR A.celular LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR A.clave LIKE '%" . $request['search']['value'] . "%' )";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }
      public function obtenerTodos()
    {
        $sql = "SELECT
                  A.idcliente,
                  A.nombre
                FROM clientes A ORDER BY A.nombre";
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }
      public function obtenerPorId($idcliente)
    {
        $sql = "SELECT
                idcliente,
                nombre,
                email,
                celular,
                clave
                FROM clientes WHERE idcliente = $idcliente";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->idcliente = $lstRetorno[0]->idcliente;
            $this->nombre = $lstRetorno[0]->nombre;
            $this->email = $lstRetorno[0]->email;
            $this->celular = $lstRetorno[0]->celular;
            $this->clave = $lstRetorno[0]->clave;
            return $this;
        }
        return null;
    }
      public function guardar() {
        $sql = "UPDATE clientes SET
            nombre='$this->nombre',
            email='$this->email',
            celular='$this->celular',
            clave='$this->clave'
            WHERE idcliente=?";
        $affected = DB::update($sql, [$this->idcliente]);
    }
    public function eliminar()
    {
        $sql = "DELETE FROM clientes WHERE
            idcliente=?";
        $affected = DB::delete($sql, [$this->idcliente]);
    }
        public function insertar()
    {
        $sql = "INSERT INTO clientes (
                nombre,
                email,
                celular,
                clave
            ) VALUES (?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->nombre,
            $this->email,
            $this->celular,
            $this->clave
        ]);
        return $this->idcliente = DB::getPdo()->lastInsertId();
    }

       
    }

