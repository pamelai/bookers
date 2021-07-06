<?php


namespace pifp\Controllers;


use pifp\Auth\Autenticacion;
use pifp\Core\App;

class Controller
{
    /**
     * Controller constructor.
     * Setea las los puntos necesarios para recivir una petición y sus datos
     */
    public function __construct()
    {
        header('Content-Type: application/json; charset=utf-8');

        if(property_exists($this, "sinVerificar") && !empty($this->sinVerificar)){

            $url = $_SERVER["REQUEST_URI"];

            $url = explode("public", $url)[1];

            if(!in_array($url, $this->sinVerificar)){
                $this->autenticar();
            }

        }else{
            $this->autenticar();
        }

    }

    /**
     * @return json
     * Valida si el usuario está autenticado para realizar ciertas acciones
     */
    private function autenticar(){
        $autenticacion = new Autenticacion();
        $token = $_COOKIE['token'] ?? '';

        if(!$autenticacion->verificarToken($token)) {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Esta acción requiere autenticación.'
            ]);
            exit;
        }
    }

    /**
     * @return false|mixed|string
     * Recupera los datos enviados por un fetch
     */
    protected function obtenerDatos(){
        $datos = file_get_contents('php://input');
        $datos = json_decode($datos, true);

        return $datos;
    }

}