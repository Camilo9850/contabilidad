<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entidades;
use App\Entidades\Categoria;
use App\Entidades\Sistema\Usuario; 
use App\Entidades\Sistema\Patente;



require app_path() . '/start/constants.php';

class ControladorCategoria extends Controller
{

    public function index()
    {

        $titulo = "Categoria";

        return view('sistema.categoria-nuevo', compact('titulo'));
    }
    public function nuevo()
    {

        $titulo = "listado de Categoria";

        return view('sistema.categoria-listar', compact('titulo'));
    }

    public function guardar(Request $request)
    {
        try {
            //Define la entidad servicio
            $titulo = "Modificar categoria";
            $entidad = new Categoria();
            $entidad->cargarDesdeRequest($request);

            //validaciones
            if ($entidad->nombre == "") {
                $msg["ESTADO"] = MSG_ERROR;
                $msg["MSG"] = "Complete todos los datos";
            } else {
                if ($_POST["id"] > 0) {
                    //Es actualizacion
                    $entidad->guardar();

                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                } else {
                    //Es nuevo
                    $entidad->insertar();

                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                }
                $categoria = new Categoria();
                $categoria->fk_idcategoria = $entidad->idcategoria;
                $categoria->eliminarPorCategoria();
                if ($request->input("txtnombre") != null && count($request->input("txtnombre")) > 0) {
                    foreach ($request->input("txtnombre") as $idcategoria) {
                        $categoria->idcategoria = $idcategoria;
                        $categoria->insertar();
                    }
                }
                $_POST["id"] = $entidad->idcategoria;
                return view('sistema.categoria-listar', compact('titulo', 'msg'));
            }
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        $id = $entidad->idcategoria;
        $categoria = new Categoria();
        $categoria->obtenerPorId($idcategoria);

        $entidad = new Categoria();
        $categoria = $entidad->obtenerPorId($idcategoria);

        $categoria = new  Categoria();
        $categoria = $categoria->obtenerPorId($idcategoria);

        return view('sistema.categoria-nuevo', compact('msg', 'categoria', 'titulo', 'array_menu', 'array_menu_grupo')) . '?id=' . $categoria->idcategoria;
    }

    public function cargarGrilla()
    {
        $request = $_REQUEST;

        $entidad = new Categoria();
        $aCategoria = $entidad->obtenerFiltrado($request);

        $data = array();
        $cont = 0;

        $inicio = $request['start'];
        $registros_por_pagina = $request['length'];


        for ($i = $inicio; $i < count($aCategoria) && $cont < $registros_por_pagina; $i++) {
            $row = array();
            $row[] = '<a href="/admin/sistema/categoria/' . $aCategoria[$i]->idcategoria . '">' . $aCategoria[$i]->nombre . '</a>';
            $row[] = '<a href="/admin/categoria/' . $aCategoria[$i]->idcategoria . '" class="fa fa-pencil" title="Editar"></a> '
                . '<a href="#" onclick="eliminarCategoria(' . $aCategoria[$i]->idcategoria . ')" class="fa fa-trash" title="Eliminar"></a>';
            $cont++;
            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => count($aCategoria), //cantidad total de registros sin paginar
            "recordsFiltered" => count($aCategoria), //cantidad total de registros en la paginacion
            "data" => $data,
        );
        return json_encode($json_data);
    }
    public function editar($id)
{
    $titulo = "Modificar Categoría";

    if (Usuario::autenticado() == true) {
        if (!Patente::autorizarOperacion("CATEGORIAMODIFICACION")) {
            $codigo = "CATEGORIAMODIFICACION";
            $mensaje = "No tiene pemisos para la operaci&oacute;n.";
            return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
        } else {
            // Se crea una sola instancia de Categoria
            $categoria = new Categoria();
            $categoria->obtenerPorId($id);

            // Verifica si la categoría fue encontrada en la base de datos
            if ($categoria->idcategoria) {
                // Si la encuentra, devuelve la vista del formulario de edición
                return view('sistema.categoria-nuevo', compact('titulo', 'categoria'));
            } else {
                // Si no existe, muestra un mensaje de error 404
                $titulo = "Error";
                $codigo = "404";
                $mensaje = "Categoría no encontrada.";
                return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
            }
        }
    } else {
        // Redirige al login si el usuario no está autenticado
        return redirect('admin/login');
    }
}
}
