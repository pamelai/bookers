<?php

namespace pifp\Controllers;

use pifp\Core\App;
use pifp\Core\Request;
use pifp\Core\Route;
use pifp\Core\View;
use pifp\Models\Interes;

class InteresesController extends Controller
{

    /**
     * @return json
     * Lista todas las novedades existentes
     */
    public function listado()
    {

        $interes = new Interes();

        $var = explode("/", $_SERVER["REQUEST_URI"]);
        $id = array_pop($var);

        if (intval($id)):
            $datos = $interes->traerTodo($interes->relaciones, [["Usuarios_id", intval($id)]]);
        else:
            $datos = $interes->traerTodo($interes->relaciones);
        endif;


        echo json_encode([
            "data" => $datos,
            "estado" => 1
        ]);
        exit;
    }


    public function eliminar()
    {
        $post = $this->obtenerDatos();

        $interes = new Interes();
        try {
            $interes->eliminar($post['id']);

            echo json_encode([
                "estado" => 1,
                'mensaje' => 'Los intereses de actalizaron correctamente'
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                "mensaje" => 'Hubo un error al atualizar los intereses, inténtelo más tarde',
                "estado" => 0
            ]);
        }
    }

    public function crear()
    {
        $post = $this->obtenerDatos();

        $interes = new Interes();
        try {
            $interes->crear($post);

            echo json_encode([
                "estado" => 1,
                'mensaje' => 'Los intereses de actalizaron correctamente'
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                "mensaje" => 'Hubo un error al atualizar los intereses, inténtelo más tarde',
                "estado" => 0
            ]);
        }
    }
}