<?php

namespace pifp\Models;

use pifp\DB\Base;

class Usuario extends Modelo implements \JsonSerializable
{

    /**
     * @var string
     */
    protected $tabla = "usuarios";
    /**
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $nombre;
    /**
     * @var string
     */
    protected $apellido;
    /**
     * @var string
     */
    protected $usuario;
    /**
     * @var null/int
     */
    protected $usuarios_id = null;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $imagen;


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
   {
      return [
          // En $this->usuarios_id se guarda el id del usuario cuando se hace el JOIN, si no se hace el JOIN y es un SELECT común a la tabla usuarios, se guarda en $this->id
         'id' => $this->usuarios_id ?? $this->id,
         'nombre' => $this->nombre,
         'apellido' => $this->apellido,
         'usuario' => $this->usuario,
         'email' => $this->email,
         'imagen' => $this->imagen
      ];
   }

    /**
     * @param $id
     * @return bool
     * Busca un usuario por id
     */
    public function usuarioPorId($id)
   {
      $base = base::getConnection();
      $query = "SELECT * FROM usuarios WHERE id = ?";
      $statement = $base->prepare($query);
      $statement->execute([$id]);
      $fila = $statement->fetchObject(self::class);

      if ($fila) {
         return $fila;
      } else {
         return false;
      }
   }

    /**
     * @param $email
     * @return mixed
     * @throws \Exception
     *
     * Busca un usuario por su email
     */
    public function usuarioPorMail($email)
   {
      $base = Base::getConnection();
      $query = "SELECT * FROM usuarios WHERE email =?";
      $statement = $base->prepare($query);
      $statement->execute([$email]);
      $fila = $statement->fetchObject(self::class);

      if ($fila)
         return $fila;
      else
         throw new \Exception(false);
   }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

   /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getTabla(): string
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