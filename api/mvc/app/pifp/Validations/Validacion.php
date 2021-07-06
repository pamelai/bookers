<?php
namespace pifp\Validations;

class Validacion
{
    /**
     * @var array
     */
    protected  $datos = [];
    /**
     * @var array
     */
    protected  $reglas = [];
    /**
     * @var array
     */
    protected  $errores = [];

    /**
     * Validacion constructor.
     * @param $datos
     * @param $reglas
     */
    public function __construct($datos, $reglas)
    {
        $this->datos = $datos;
        $this->reglas = $reglas;

        $this->validar();
    }


    /**
     * Separa los datos a validar de las reglas por las cuales validar
     */
    public function validar()
    {
        foreach ($this->reglas as $campo => $listadoReglas) {
            foreach ($listadoReglas as $regla) {
                $this->aplicarValidacion($campo, $regla);
            }
        }
    }

    /**
     * @param $campo
     * @param $regla
     * Aplica la validación de los datos a por las reglas ya seteadas
     */
    public function aplicarValidacion($campo, $regla)
    {
        $metodoRegla = "_".$regla;

        if(strpos($regla, ":")){
            list($nombre, $dato) = explode(":", $regla);
            $metodoRegla = "_" . $nombre;
            if(!method_exists($this, $metodoRegla)){
                throw new Exception("La regla" . $regla . "no existe");
            }

            $this->{$metodoRegla}($campo,$dato);
        }else{
            $this->{$metodoRegla}($campo);
        }
    }


    /**
     * @return bool
     * Devuelve si las validacion paso o no
     */
    public function valido()
    {
        return count($this->errores) === 0;
    }

    /**
     * @return array
     * Devuelve los errores
     */
    public function obtenerErrores()
    {
        return $this->errores;
    }


    /**
     * @param $campo
     * @param $error
     * Agrupa todos los errores
     */
    public function agregarError($campo, $error)
    {
        if(!isset($this->errores[$campo])){
            $this->errores[$campo] = [];
        }
        $this->errores[$campo][] = $error;
    }

    /**
     * @param $campo
     * @return bool
     * Verifica que el campo requerido no esté vacío
     */
    protected function _required($campo)
    {
        $valor = $this->datos[$campo];
        if(empty($valor)){
            $this->agregarError($campo, "El $campo no puede estar vacío.");
            return false;
        }
        return true;
    }


    /**
     * @param $campo
     * @return bool
     * Verifica que el campo sea un email válido
     */
    protected function _email($campo)
    {
        $valor = $this->datos[$campo];

        if(empty($valor) || strpos($valor,'@') === false){
            $this->agregarError($campo, "El $campo debe de ser un email válido.");
            return false;
        }
        return true;
    }

    /**
     * @param $campo
     * @param $minimo
     * @return bool
     * Verifica que el campo tenga las cantidad de caracteres minimos que debe tener
     */
    protected function _min($campo, $minimo)
    {
        $valor = $this->datos[$campo];

        if(strlen($valor) < $minimo){
            $this->agregarError($campo, "El $campo debe tener al menos $minimo caracteres" );
            return false;
        }
    return true;
    }

    /**
     * @param $campo
     * @return bool
     * Verifica que el campo sea un string
     */
    protected function _string($campo)
    {
        $valor = $this->datos[$campo];

        if(!is_string($valor)){
            $this->agregarError($campo, "El $campo no puede contener sólamente números" );
            return false;
        }
    return true;
    }

    /**
     * @param $campo
     * @return bool
     * Verifica que la imagen sea .jpg
     */
    protected function _jpeg($campo)
   {
      $valor = $this->datos[$campo];

      $imgData = explode(",",$valor);
      $mime = $imgData[0];

      $mime = substr($mime, 5, -7);

      if($mime != 'image/jpeg'){
         $this->agregarError($campo, "La $campo debe de ser en formato .jpg" );
         return false;
      }
      return true;
   }
}

