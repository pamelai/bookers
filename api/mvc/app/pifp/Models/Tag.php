<?php


namespace pifp\Models;

use pifp\DB\Base;
use pifp\Models\Novedad;

class Tag extends Modelo implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $Novedades_id;

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var string
     */
    protected $tabla = "tags";


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'Novedades_id' => $this->Novedades_id,
            'tag' => $this->tag
        ];
    }

    /**
     * @var array
     * Relaciones entre las demás tablas
     */
    public $relaciones = [
        [
            "modelo" => Novedad::class,
            "interna" => false,
            "tipo" => "LEFT"
        ],
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