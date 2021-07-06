<?php

namespace pifp\Models;

use pifp\Models\Comentario;
use pifp\DB\Base;

class Interes extends Modelo implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $Usuarios_id;

    /**
     * @var int
     */
    protected $interes;

    /**
     * @var string
     */
    protected $tabla = "intereses";


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'Usuarios_id' => $this->Usuarios_id,
            'interes' => $this->interes
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