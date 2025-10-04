<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sucursal extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'sucursals';

    // Clave primaria de la tabla
    protected $primaryKey = 'idsucursal';

    // Indica que la clave primaria es autoincremental
    public $incrementing = true;

    // Indica que la tabla tiene las marcas de tiempo (created_at, updated_at)
    public $timestamps = true;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'ciudad',
        'estado',
        'activo',
    ];

    // Casteo de tipos de datos para asegurar el formato correcto
    protected $casts = [
        // TINYINT(1) se castea a booleano en PHP
        'activo' => 'boolean',
    ];

    /**
     * Mapea los campos del formulario (Request) a las propiedades del modelo.
     */
    public function cargarDesdeRequest($request)
    {
        $this->idsucursal = $request->input('id') != "0" ? $request->input('id') : $this->idsucursal;
        $this->nombre = $request->input('txtNombre');
        $this->direccion = $request->input('txtDireccion');
        $this->telefono = $request->input('txtTelefono');
        $this->email = $request->input('txtEmail');
        $this->ciudad = $request->input('txtCiudad');
        $this->estado = $request->input('txtEstado');
        $this->activo = $request->input('lstActivo');
    }

    // --- Métodos CRUD Básicos ---




    public function guardar()
    {
        $sql = "UPDATE sucursals SET
                nombre = ?,
                direccion = ?,
                telefono = ?,
                email = ?,
                ciudad = ?,
                estado = ?,
                activo = ?,
                updated_at = NOW()  // Actualiza la marca de tiempo manualmente
            WHERE idsucursal = ?";

        // Ejecuta la consulta preparada con los valores de las propiedades del objeto
        DB::update($sql, [
            $this->nombre,
            $this->direccion,
            $this->telefono,
            $this->email,
            $this->ciudad,
            $this->estado,
            $this->activo,
            $this->idsucursal
        ]);
    }
    public function insertar()
    {
        $sql = "INSERT INTO sucursals (
                nombre, direccion, telefono, email, ciudad, estado, activo, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        DB::insert($sql, [
            $this->nombre,
            $this->direccion,
            $this->telefono,
            $this->email,
            $this->ciudad,
            $this->estado,
            $this->activo
        ]);

        // Obtener el ID autoincremental insertado
        $this->idsucursal = DB::getPdo()->lastInsertId();
        return $this->idsucursal;
    }
    public function obtenerTodos()
{
    $sql = "SELECT 
                idsucursal, 
                nombre, 
                direccion, 
                telefono, 
                email, 
                ciudad, 
                estado, 
                activo 
            FROM sucursals 
            ORDER BY nombre ASC"; // Ordenamos alfabéticamente
            
    $lstRetorno = DB::select($sql);

    return $lstRetorno;
}
}
