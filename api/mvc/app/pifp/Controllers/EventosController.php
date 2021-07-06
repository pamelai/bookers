<?php

namespace pifp\Controllers;

use mysql_xdevapi\Exception;
use pifp\Core\App;
use pifp\Core\Request;
use pifp\Core\Route;
use pifp\Core\View;
use pifp\Models\Evento;

class EventosController extends Controller
{

    /**
     * @return json
     * Lista todas las novedades existentes
     */
    public function listado()
    {

        $evento = new Evento();
        $post = $this->obtenerDatos();

        $var = explode("/", $_SERVER["REQUEST_URI"]);
        $id = array_pop($var);

        $datos = $evento->eventosConAsistencia(intval($id));

        echo json_encode([
            "data" => $datos,
            "estado" => 1
        ]);
        exit;
    }


    /**
     * @return json
     * Define tipo de asistencia a un evento
     */
    public function asistir()
    {
        $evento = new Evento();

        try {

            $ev = $this->obtenerDatos();

            $evento->asistir($ev);

            echo json_encode([
                "estado" => 1,
                "mensaje" => $evento->getEstado($ev["estado"]) . " al evento"
            ]);

        } catch (\Exception $exception) {

            echo json_encode([
                "estado" => 0,
                "mensaje" => "No se ha podido definir su asistencia al evento. Inténtalo de nuevo más tarde"
            ]);

        }
    }

    public function eventosPerfil(){
        $evento = new Evento();

        $var = explode("/", $_SERVER["REQUEST_URI"]);
        $id = array_pop($var);

        $eventos = $evento->eventosPerfil($id);

        echo json_encode([
            "estado" => 1,
            "data" => $eventos
        ]);


    }

    public function eventosProximos(){
        $evento = new Evento();

        $eventos = $evento->eventosProximos();

        echo json_encode([
            "estado" => 1,
            "data" => $eventos
        ]);


    }

}