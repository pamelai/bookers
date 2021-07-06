<?php

namespace pifp\Controllers;

use pifp\Core\App;
use pifp\Core\Request;
use pifp\Core\Route;
use pifp\Core\View;
use pifp\Models\Comentario;

class ComentariosController extends Controller
{

    /**
     * @return json
     * Publica el comentario del usuario
     */
    public function publicar()
    {
        $comentario = new Comentario();

        try {
            $coment = $this->obtenerDatos();

            $comentario->crear($coment);

            echo json_encode([
                "estado" => 1,
                "mensaje" => "Has comentado con éxito"
            ]);

        } catch (\Exception $exception) {

            echo json_encode([
                "estado" => 0,
                "mensaje" => "No se ha podido publicar tu comentario. Inténtalo de nuevo más tarde"
            ]);

        }
    }
}