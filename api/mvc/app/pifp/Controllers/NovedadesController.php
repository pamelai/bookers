<?php

namespace pifp\Controllers;

use mysql_xdevapi\Exception;
use pifp\Core\App;
use pifp\Core\Request;
use pifp\Core\Route;
use pifp\Core\View;
use pifp\Models\Novedad;

class NovedadesController extends Controller
{

    /**
     * @return json
     * Lista todas las novedades existentes
     */
    public function listado()
    {

        $novedad = new Novedad();
        $post = $this->obtenerDatos();

        $var = explode("/", $_SERVER["REQUEST_URI"]);

        $vars = [];

        foreach ($var as $val):
            if (!intval($val)):
                continue;
            endif;

            $vars[] = $val;
        endforeach;

        $usr = $vars[0];

        if (count($vars) > 1):
            $datos = $novedad->traerTodo($novedad->relaciones, [["novedades.usuarios_id", $usr]], intval($usr));
        else:
            $datos = $novedad->traerTodo($novedad->relaciones, null, intval($usr));

        endif;


        echo json_encode([
            "data" => $datos,
            "estado" => 1
        ]);
        exit;
    }


    /**
     * @return json
     * Publica la novedad
     */
    public function publicar()
    {
        $novedad = new Novedad();

        try {

            $nov = $this->obtenerDatos();
            $nov['date'] = date("Y-m-d H:i:s");

            $tags = explode('#', $nov['cuerpo']);

            $nov = $novedad->crear($nov);

            echo json_encode([
                "estado" => 1,
                "mensaje" => "Has publicado con éxito",
                "data" => $nov

            ]);

        } catch (\Exception $exception) {

            echo json_encode([
                "estado" => 0,
                "mensaje" => "No se ha podido publicar tu novedad. Inténtalo de nuevo más tarde"
            ]);

        }
    }


    /**
     * @return json
     *
     * Elimina la novedad
     * @throws \Exception
     */
    public function eliminarNov()
    {
        $post = $this->obtenerDatos();

        $novedad = new Novedad();
        try {
            $datos = $novedad->eliminar($post["id"]);

            echo json_encode([
                "data" => $datos,
                "estado" => 1,
                'mensaje' => 'La novedad se eliminó con éxito'
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                "mensaje" => 'Hubo un error al eliminar la novedad',
                "estado" => 0
            ]);
        }
    }

    public function compartir()
    {
        $post = $this->obtenerDatos();
        $compartida = new Novedad();
        $novedad = new Novedad();
        try {
            $compartida = $compartida->buscar($post["Novedades_id"]);

            if (!$compartida) {
                throw new Exception("No se encontró la novedad a compartir");
                exit;
            }

            $data = [
                "usuarios_id" => $post["Usuarios_id"],
                "novedades_id" => $post["Novedades_id"]
            ];

            $compartir = $novedad->crear($data);

            if (!$compartir) {
                throw new Exception("No se pudo compartir la publicación");
                exit;
            }

            echo json_encode([
                "estado" => 1,
                'mensaje' => 'La novedad se compartió con éxito'
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                "mensaje" => $e->getMessage(),
                "estado" => 0
            ]);
        };

    }
}
