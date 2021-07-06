<?php


namespace pifp\Controllers;

use pifp\Core\App;
use pifp\Core\Request;
use pifp\Core\Route;
use pifp\Core\View;
use pifp\Models\Interes;
use pifp\Models\Notificacion;
use pifp\Models\Tag;

class TagsController extends Controller
{

    public function crear()
    {
        $post = $this->obtenerDatos();

        $tag = new Tag();
        try {
            $tag->crear($post);

            echo json_encode([
                "estado" => 1,
                'mensaje' => 'Se agregó el tag'
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                "mensaje" => 'Hubo un error al agregar el tag',
                "estado" => 0
            ]);
        }
    }

    /**
     * @return json
     * Lista todas las novedades existentes con el tag buscado
     */
    public function busqueda()
    {
        $tag = new Tag();

        $var = explode("/", $_SERVER["REQUEST_URI"]);
        $id = array_pop($var);

        $datos = $tag->traerTodo(null, [["tag", "$id"]]);

        echo json_encode([
            "data" => $datos,
            "estado" => 1
        ]);
        exit;
    }
}