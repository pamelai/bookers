<?php
namespace pifp\DB;

use PDOException;
use PDO;
use Exception;

class Base
{
    /**
     * @var null
     * Nombre de la db
     */
    protected static $base = null;

    /**
     * Base constructor.
     */
    private function __construct()
    {}

    /**
     * @return json
     * Realiza la conexion a la db
     */
    private static function connect()
    {
        $base_host =  "localhost";
        $base_usuario = "root";
        $base_password = "";
        $base_base = "dbprog3";
        $base_dsn = "mysql:host=$base_host;dbname=$base_base;charset=utf8mb4";

        try {
            self::$base = new PDO($base_dsn, $base_usuario, $base_password);
        } catch (PDOException $excepcionPDO) {
            echo json_encode([
                "estado" => 1,
                "mensaje" => "Hubo un error, inténtalo más tarde"
            ]);
            exit();

        } catch (\Exception $excepcion){
            echo json_encode([
                "estado" => 1,
                "mensaje" => "Hubo un error, inténtalo más tarde"
            ]);
            exit();
        }
    }

    /**
     * @return null
     * Pide la conexion de la db
     */
    public static function getConnection()
    {
        if(self::$base == null){
            self::connect();
        }
        return self::$base;
    }
}