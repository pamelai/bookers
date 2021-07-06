<?php

namespace pifp\Models;

use pifp\Models\Comentario;
use pifp\Models\Usuario;
use pifp\DB\Base;

class Novedad extends Modelo implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;
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
     * @var array
     */
    protected $novedades_id;

    /**
     * @var array
     */
    protected $novedades;

    /**
     * @var int
     */
    protected $comentarios_id;

    /**
     * @var bool
     */
    protected $favorito;

    /**
     * @var string
     */
    protected $tabla = "novedades";

    /**
     * @var string
     */
    protected $alias = "nov";


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'descripcion' => $this->cuerpo ?? $this->novedades[0]->cuerpo,
            'date' => $this->date ?? $this->novedades[0]->date,
            'usuarios' => $this->usuarios,
            'comentarios' => $this->comentarios,
            'compartido' => is_null($this->date) ? $this->novedades[0]->usuarios[0]->getUsuario() : 0,
            'favorito'  => $this->favorito
        ];
    }

    /**
     * @var array
     * Relaciones entre las demás tablas
     */
    public $relaciones = [
        [
            "modelo" => Comentario::class,
            "interna" => false,
            "tipo" => "LEFT"
        ],
        [
            "modelo" => Usuario::class,
            "interna" => true
        ],
        [
            "modelo" => Novedad::class,
            "interna" => true
        ]
    ];

    protected function query($arg){
        return <<<QUERY
            SELECT novedades.*, IF(ISNULL(fav.favorito),0,fav.favorito) as favorito
            FROM novedades
            LEFT JOIN (SELECT favoritos.Novedades_id, favoritos.Usuarios_id, favoritos.id as favorito FROM favoritos WHERE Usuarios_id = $arg) as fav ON fav.novedades_id = novedades.id
QUERY;

    }

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
     * @return mixed
     */
    public function getNovedadesId()
    {
        return $this->novedades_id;
    }
}