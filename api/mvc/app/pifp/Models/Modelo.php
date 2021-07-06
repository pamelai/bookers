<?php

namespace pifp\Models;

use pifp\DB\Base;
use PDO;


/**
 * Class Modelo
 * @package pifp\Models
 */
class Modelo
{
    /**
     * @var string
     */
    protected $tabla = "";

    /**
     * @var string
     */
    protected $alias = null;

    /**
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * @var array
     */
    protected $campos = [];

    /**
     * @param $data
     * @param $campos
     */
    public function datosDelArray($data, $campos)
    {
        foreach ($data as $indice => $valor) {
            if (in_array($indice, $campos)) {
                $this->{$indice} = $valor;
            }

            if ($indice == 'id' && !empty($valor))
                $this->{$indice} = $valor;
        }

    }

    public function buscar($id){
        $db = Base::getConnection();

        $query = "SELECT * FROM " . $this->tabla . " WHERE " . $this->primaryKey . " = ?";

        $stmt = $db->prepare($query);
        $stmt->execute([$id]);

        $modelo = $stmt->fetchObject($this->getClass());

        return $modelo;

    }

    /**
     * @param array $joins [["modelo" => "", "interna" => bool, "tipo" => "LEFT JOIN"] ]
     * @return array
     *
     * Trae la/s novedad/es con todas la relaciones que tienen
     */
    public function traerTodo($joins = [], $where = [], $param = null)
    {
        $db = Base::getConnection();

        $query = "SELECT * FROM " . $this->tabla;

        if(method_exists($this->getClass(),"query")):
            if(!is_null($param))
                $query = $this->query($param);
            else
                $query = $this->query();

        endif;

        if (!empty($where)) {
            foreach($where as $pos => $w):
                $cond = $pos ? "AND" : "WHERE";

                if($w[1] == "null")
                    $query .= " $cond ISNULL($w[0])";
    
                elseif (is_string($w[1]))
                    $query .= " $cond $w[0] = '$w[1]'";
    
                else
                    $query .= " $cond $w[0] = $w[1]";
            endforeach;
        }

        $stmt = $db->prepare($query);
        $stmt->execute();

        $salida = [];

        while ($fila = $stmt->fetchObject($this->getClass())) {

            if (!empty($joins)):

                foreach ($joins as $join):

                    $modelo = new $join["modelo"];
                    $tabla = $modelo->getTabla();
                    $tipo = $join["tipo"] ?? "";


                    $query2 = "SELECT *, " . $this->tabla . "." . $this->primaryKey . " AS '" . $this->tabla . "_id', " . $tabla . "." . $modelo->getPrimaryKey() . " AS '" . $tabla . "_id'";
                    $query2 .= " FROM $tabla";

                    // chequear si la tabla a la que se hace el join es la misma que está haciendo el join
                    $fk = "get". ucfirst($this->tabla) . "Id";
                    if($tabla == $this->tabla && $fila->$fk()):
                        $query2 = "SELECT *, " . $this->getAlias() . "." . $this->primaryKey . " AS '" . $this->getAlias() . "_id', " . $tabla . "." . $modelo->getPrimaryKey() . " AS '" . $tabla . "_id'";
                        $query2 .= " FROM $tabla";

                        $query2 .= " $tipo JOIN " . $this->tabla . " AS " . $this->getAlias() . " ON $tabla." . $this->tabla . "_id = " . $this->getAlias() . "." . $this->primaryKey;

                        $query2 .= " WHERE ". $this->getAlias() . "." . $this->primaryKey . " = " . $fila->$fk();

                        $query2 .= " limit 1";

                    else:

                        $query2 = "SELECT *, " . $this->tabla . "." . $this->primaryKey . " AS '" . $this->tabla . "_id', " . $tabla . "." . $modelo->getPrimaryKey() . " AS '" . $tabla . "_id'";
                        $query2 .= " FROM $tabla";

                        if ($join["interna"]):
                            $query2 .= " $tipo JOIN " . $this->tabla . " ON $tabla.id = " . $this->tabla . "." . $tabla . "_id WHERE " . $this->tabla . "." . $this->primaryKey . " = " . $fila->id;
                        else:
                            $query2 .= " $tipo JOIN " . $this->tabla . " ON $tabla." . $this->tabla . "_id = " . $this->tabla . "." . $this->primaryKey . " WHERE $tabla." . $this->tabla . "_id = " . $fila->id;
                        endif;

                    endif;

                    $stmt2 = $db->prepare($query2);
                    $stmt2->execute();

                    $relaciones = [];

                    while ($rel = $stmt2->fetchObject($join["modelo"])):

                        if($join["modelo"] != $this->tabla):
                            if (property_exists($join["modelo"], "relaciones")):
                                foreach ($rel->relaciones as $subJoin):
                                    $modelo2 = new $subJoin["modelo"];
                                    $tabla2 = $modelo2->getTabla();
                                    $tipo2 = $subJoin["tipo"] ?? "";

                                    $id1 = $tabla . "_id";
                                    $id2 = $tabla2 . "_id";

                                    if (isset($subJoin["intermedia"]) && $subJoin["intermedia"]):


                                        $query3 = "SELECT *, " . $tabla2 . ".id AS " . $tabla2 . "_id," . $this->tabla . ".id AS " . $this->tabla . "_id FROM $tabla2";

                                        if ($subJoin["interna"]):
                                            $query3 .= " $tipo2 JOIN " . $tabla . " ON " . $tabla2 . ".id = " . $tabla . "." . $tabla2 . "_id";
                                            $query3 .= " $tipo2 JOIN " . $this->tabla . " ON " . $tabla . "." . $this->tabla . "_id = " . $this->tabla . ".id";
                                            $query3 .= " WHERE " . $tabla . ".id = " . $rel->$id1;
                                            $query3 .= " AND $tabla." . $this->tabla . "_id = " . $fila->id;
                                        else:
                                            $query3 .= " $tipo2 JOIN " . $tabla . " ON $tabla2." . $tabla . "_id = " . $tabla . ".id";
                                            $query3 .= " $tipo2 JOIN " . $this->tabla . " ON " . $tabla2 . "." . $this->tabla . "_id = " . $this->tabla . ".id";


                                            $query3 .= "  WHERE $tabla2." . $tabla . "_id = " . $rel->$id1;
                                            $query3 .= " AND $tabla2." . $this->tabla . "_id = " . $fila->id;
                                        endif;
                                    else:
                                        $query3 = "SELECT *, " . $tabla2 . ".id AS " . $tabla2 . "_id FROM $tabla2";

                                        if ($subJoin["interna"]):
                                            $query3 .= " $tipo2 JOIN " . $tabla . " ON " . $tabla2 . ".id = " . $tabla . "." . $tabla2 . "_id WHERE " . $tabla . ".id = " . $rel->id;
                                        else:
                                            $query3 .= " $tipo2 JOIN " . $tabla . " ON $tabla2." . $tabla . "_id = " . $tabla . ".id WHERE $tabla2." . $tabla . "_id = " . $rel->id;
                                        endif;

                                    endif;

                                    $stmt3 = $db->prepare($query3);
                                    $stmt3->execute();

                                    $subRelaciones = [];
                                    while ($subRel = $stmt3->fetchObject($subJoin["modelo"])):
                                        $subRelaciones[] = $subRel;
                                    endwhile;

                                    $rel->$tabla2 = $subRelaciones;
                                endforeach;
                            endif;
                        endif;

                        $relaciones[] = $rel;
                    endwhile;
                    $fila->$tabla = $relaciones;
                endforeach;

            endif;
//            echo "1 $query \n";
//            echo "2 $query2 \n";
//            die();
            $salida[] = $fila;
        }

        return $salida;
    }


    /**
     * @param $data
     * @return $this
     * @throws \Exception
     *
     * Crea novedades/usuarios/comentarios/tags/favs/notificaciones
     */
    public function crear($data)
    {
        $this->campos = array_keys($data);

        $SQLCampos = implode(', ', $this->campos);
        $SQLHolders = implode(', ', $this->getQueryHolders($this->campos));

        $db = Base::getConnection();

        $query = "INSERT INTO " . $this->tabla . " (" . $SQLCampos . ")
                  VALUES (" . $SQLHolders . ")";

        $stmt = $db->prepare($query);
        $exito = $stmt->execute($data);

        if ($exito) {
            $data[$this->primaryKey] = $db->lastInsertId();

            $obj = new static;
            $obj->datosDelArray($data, $this->campos);
            return $obj;
        } else {
            throw new \Exception(null);
        }
    }


    /**
     * @param $data
     * @throws \Exception
     *
     * Edita los datos de los usuarios
     */
    public function editar($data)
    {
        $this->campos = array_keys($data);

        $db = Base::getConnection();

        $query = "UPDATE " . $this->tabla . " SET ";
        foreach ($this->campos as $columna) {

            $query .= "$columna = :$columna, ";
        }

        $query = substr($query, 0, strlen($query) - 2);
        $query .= " WHERE id = :id";
        $stmt = $db->prepare($query);
        $update = $stmt->execute($data);

        if (!$stmt->execute($data))
            throw new \Exception(null);

    }


    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     *
     * Elimina la/el novedad/usuario/tag
     */
    public function eliminar($id)
    {
        $base = Base::getConnection();

        $query = "DELETE FROM " . $this->tabla . " WHERE id = ?";
        $statement = $base->prepare($query);
        $delete = $statement->execute([$id]);

        if ($delete)
            return $delete;
        else
            throw new \Exception(false);
    }

    /**
     * @param $data
     * @return array
     *
     * Arma los holders para las querys
     */
    protected function getQueryHolders($data)
    {
        return array_map(function ($item) {
            return ":" . $item;
        }, $data);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return "stdClass";
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
