<?php
namespace pifp\Models;

class Comentario extends Modelo implements \JsonSerializable
{

    /**
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * @var string
     */
    protected $tabla = "comentarios";

    /**
     * @var array
     */
    protected $usuarios;
    /**
     * @var int
     */
    protected $id;
    /**
     * @var array
     */
    protected $comentario;
    /**
     * @var int
     */
    protected $comentarios_id;
    /**
     * @var int
     */
    protected $novedades_id;
    /**
     * @var int
     */
    protected $usuarios_id;

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $comentarios_id ?? $this->id,
            'comentario' => $this->comentario,
            'novedades_id' => $this->novedades_id,
            'usuarios' => $this->usuarios,
        ];
    }

    /**
     * @var array
     * Relaciones que tiene esta tabla con las demas
     */
    public $relaciones = [
        [
            "modelo" => Usuario::class,
            "interna" => true,
            "tipo" => "LEFT",
            // Si es una tabla intermedia hace la relación comparando por ambos id
            "intermedia" => true
        ]
    ];

    /**
     * @return string
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return self::class;
    }
}