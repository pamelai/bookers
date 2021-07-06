<?php


namespace pifp\Controllers;

use pifp\Core\App;
use pifp\Core\Request;
use pifp\Core\Route;
use pifp\Core\View;
use pifp\Models\Interes;
use pifp\Models\Notificacion;
use pifp\DB\Base;
use PDO;


class NotificacionesController extends Controller
{

    /**
     * @return json
     * Lista todas las novedades existentes
     */
    public function listado()
    {
        $noti = new Notificacion();

        $var = explode("/", $_SERVER["REQUEST_URI"]);
        $id = array_pop($var);

        $datos = $noti->traerTodo($noti->relaciones, [["Usuarios_id_recibe", intval($id)]]);

        echo json_encode([
            "data" => $datos,
            "estado" => 1
        ]);
        exit;
    }

    public function marcarLeida()
    {
        $post = $this->obtenerDatos();

        $noti = new Notificacion;
        try {
            $noti->editar($post);

            echo json_encode([
                "estado" => 1,
                "data" => $post
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                "estado" => 0,
                "mensaje" => "No se pudieron marcar como leídas, intentá más tarde"
            ]);
        }
    }

    public function crear()
    {
        $post = $this->obtenerDatos();

        $noti = new Notificacion();
        try {
            $noti->crear($post);

            echo json_encode([
                "estado" => 1,
                'mensaje' => 'Se notificó al usuario'
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                "mensaje" => 'Hubo un error notificar al usuario',
                "estado" => 0
            ]);
        }
    }

    public function eliminar()
    {
        $post = $this->obtenerDatos();

        $base = Base::getConnection();
        $query = "DELETE FROM notificaciones WHERE Usuarios_id_recibe = ?";
        $statement = $base->prepare($query);
        $delete = $statement->execute([$post['user']]);

        if($delete){
            echo json_encode([
                "estado" => 1,
                'mensaje' => 'Se eliminaron las notificiaciones correctamente'
            ]);

        } else {
            echo json_encode([
                "mensaje" => 'Hubo un error al eliminar las notificaciones',
                "estado" => 0
            ]);
        }
    }
}