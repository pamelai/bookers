<?php

namespace pifp\Models;
use pifp\DB\Base;

class Notificacion extends Modelo implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $Usuarios_id_recibe;

    /**
     * @var int
     */
    protected $Usuarios_id_envia;

    /**
     * @var string
     */
    protected $notificacion;

    /**
     * @var int
     */
    protected $leida;

    /**
     * @var int
     */
    protected $Novedades_id;

    /**
     * @var string
     */
    protected $tabla = "notificaciones";

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'paraUser' => $this->Usuarios_id_recibe,
            'deUser' => $this->Usuarios_id_envia,
            'notificacion' => $this->notificacion,
            'leida' => $this->leida,
            'novId' => $this->Novedades_id
        ];
    }

    /**
     * @var array
     * Relaciones entre las demás tablas
     */
    public $relaciones = [
        [
            "modelo" => Usuario::class,
            "interna" => true
        ],
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

}