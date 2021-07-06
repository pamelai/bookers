<?php

namespace pifp\Models;


use mysql_xdevapi\Exception;
use pifp\DB\Base;

class Evento extends Modelo implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $descripcion;
    /**
     * @var string
     */
    protected $fecha;
    /**
     * @var array
     */
    protected $hora;
    /**
     * @var array
     */
    protected $lugar;

    /**
     * @var array
     */
    protected $nombre;

    /**
     * @var array
     */
    protected $estado;

    /**
     * @var array
     */
    protected $asistentes;

    /**
     * @var string
     */
    protected $tabla = "eventos";


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'fecha' => $this->fecha ?? "A confirmar",
            'hora' => $this->hora ?? "A confirmar",
            'descripcion' => $this->descripcion,
            'lugar' => $this->lugar,
            'estado' => $this->estado,
            'asistentes' => $this->asistentes
        ];
    }

    /**
     * @var array
     * Relaciones entre las demás tablas
     */
    public $relaciones = [
        [
            "modelo" => Usuario::class,
            "interna" => false,
            "intermedia" => true
        ]
    ];

    /**
     * @return string
     */
    public function getClass()
    {
        return self::class;
    }

    /**
     * @return string
     */
    public function getTabla(): string
    {
        return $this->tabla;
    }


    /**
     * @return array
     */
    public function getAsistentes(): Array
    {
        return $this->asistentes;
    }

    public function setAsistentes($asistente)
    {
        $this->asistentes = $asistente;
    }




    public function asistir($datos)
    {
        $db = Base::getConnection();

        /*
          Select id y estado donde coincida usuario_id y even_id
          chequeas con el fetchColumn() > 0 -> chequea cantidad de resultados
            $evento = fetchObj
            Si devuelve algo chequeas el estado == $datos["estado"] ? return false
            Si  estado != $datos["estado"] -> Actualizas ese estado en eventos_usuarios "UPDATE eventos_usuarios bla" donde id = $evento->id
         Sino se hace el insert...
         */

        $qSelect = "SELECT id, estado FROM eventos_usuarios WHERE usuarios_id = ? AND eventos_id = ?";
        $stmtSelect = $db->prepare($qSelect);

        $stmtSelect->execute([$datos["usuarios_id"], $datos["eventos_id"]]);

        if ($stmtSelect->rowCount() > 0):
            $evento = $stmtSelect->fetchObject($this->getClass());

            if ($evento->estado == $datos["estado"])
                return false;

            $qUpdate = "UPDATE eventos_usuarios SET estado = ? WHERE id = ?";

            $stmtUpdate = $db->prepare($qUpdate);

            $result = $stmtUpdate->execute([$datos["estado"], $evento->id]);

        else:

            $query = "INSERT INTO eventos_usuarios (usuarios_id, eventos_id, estado) VALUES (?,?,?)";

            $stmt = $db->prepare($query);
            $result = $stmt->execute([$datos["usuarios_id"], $datos["eventos_id"], $datos["estado"]]);

        endif;

        if (!$result)
            throw new Exception("No se pudo definir asistencia al evento");

        return $result;
    }

    public function getEstado($e)
    {
        $estado = false;

        switch ($e):
            case 1:
                $estado = "Asistirás";
                break;
            case 2:
                $estado = "Tal vez asistas";
                break;
            case 3:
                $estado = "No asistirás";
                break;
        endswitch;

        return $estado;
    }

    public function eventosConAsistencia($id)
    {

        $db = Base::getConnection();

        $query = "SELECT " . $this->tabla . ".*, estado FROM " . $this->tabla . " LEFT JOIN (SELECT eventos_id, estado FROM eventos_usuarios WHERE usuarios_id = ?) as t on " . $this->tabla . "." . $this->primaryKey . " = t.eventos_id ORDER BY 'DESC'";


        $stmt = $db->prepare($query);
        $stmt->execute([$id]);

        $eventos = [];

        while ($evento = $stmt->fetchObject($this->getClass())):
            // asistentes
            $asistentesQuery = "SELECT usuarios.usuario, estado FROM eventos_usuarios JOIN usuarios on eventos_usuarios.usuarios_id = usuarios.id WHERE usuarios.id != ? AND eventos_usuarios.eventos_id = ? ORDER BY RAND() LIMIT 3";

            $stmtAsistentes = $db->prepare($asistentesQuery);

            $stmtAsistentes->execute([$id, $evento->id]);

            if($stmtAsistentes->rowCount() > 0){
                $asistentes = [];
                while($resultadoAsistentes = $stmtAsistentes->fetch(\PDO::FETCH_ASSOC))
                    $asistentes[] = $resultadoAsistentes;

                $evento->setAsistentes($asistentes);
            }

            $eventos[] = $evento;
        endwhile;

        return $eventos;

    }

    public function eventosPerfil($id){
        $db = Base::getConnection();

        $query = "SELECT nombre, fecha, hora, lugar FROM " . $this->tabla . " LEFT JOIN eventos_usuarios on eventos_usuarios.eventos_id = " . $this->tabla . "." . $this->primaryKey . " WHERE usuarios_id = ? AND estado = 1 ORDER BY 'DESC'";

        $stmt = $db->prepare($query);
        $stmt->execute([$id]);


        $eventos = [];

        while($evento = $stmt->fetchObject($this->getClass())){
            $eventos[] = $evento;
        }

        return $eventos;
    }

    public function eventosProximos(){
        $db = Base::getConnection();

        $query = "SELECT nombre, fecha, hora, lugar FROM " . $this->tabla . " WHERE " . $this->tabla . ".fecha BETWEEN NOW() AND DATE_ADD(NOW(),INTERVAL 15 DAY)";

        $stmt = $db->prepare($query);
        $stmt->execute();

        $eventos = [];

        while($evento = $stmt->fetchObject($this->getClass())){
            $eventos[] = $evento;
        }
        return $eventos;

    }
}
