<?php

namespace pifp\Models;

use pifp\Models\Comentario;
use pifp\DB\Base;

class Favorito extends Modelo implements \JsonSerializable
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
    protected $Novedades_id;

    /**
     * @var string
     */
    protected $cuerpo;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var array
     */
    protected $usuarios;

    /**
     * @var array
     */
    protected $comentarios;

    /**
     * @var string
     */
    protected $tabla = "favoritos";


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'Usuarios_id' => $this->Usuarios_id,
            'Novedades_id' => $this->Novedades_id,
            'descripcion' => $this->cuerpo,
            'date' => $this->date,
            'usuarios' => $this->usuarios,
            'comentarios' => $this->comentarios
        ];
    }

    /**
     * @var array
     * Relaciones entre las demás tablas
     */
    public $relaciones = [
        [
            "modelo" => Novedad::class,
            "interna" => false
        ],
        [
            "modelo" => Usuario::class,
            "interna" => false
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