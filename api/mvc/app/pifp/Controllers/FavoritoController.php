<?php

namespace pifp\Controllers;

use pifp\Core\App;
use pifp\Core\Request;
use pifp\Core\Route;
use pifp\Core\View;
use pifp\Models\Favorito;

class FavoritoController extends Controller
{

    /**
     * @return json
     * Lista todas las novedades existentes
     */
    public function listado()
    {

        $fav = new Favorito();

        $var = explode("/", $_SERVER["REQUEST_URI"]);
        $id = array_pop($var);

        $datos = $fav->traerTodo($fav->relaciones, ["Usuarios_id", intval($id)]);

        echo json_encode([
            "data" => $datos,
            "estado" => 1
        ]);
        exit;
    }


    public function agregarFav()
    {
        $post = $this->obtenerDatos();

        $favorito = new Favorito();
        try {
            $data = $favorito->crear($post);

            echo json_encode([
                "estado" => 1,
                'mensaje' => 'Se agregó a favoritos correctamente',
                'datos' => $data,
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                "mensaje" => 'Hubo un error al agregar como favorito, inténtelo más tarde',
                "estado" => 0
            ]);
        }

    }

    public function eliminarFav()
    {
        $post = $this->obtenerDatos();

        $favorito = new Favorito();
        try {
            $favorito->eliminar($post['id']);

            echo json_encode([
                "estado" => 1,
                'mensaje' => 'Se eliminó correctamente de favoritos'
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                "mensjae" => 'Hubo un error al eliminar el favorito, inténtelo más tarde',
                "estado" => 0
            ]);
        }
    }
}