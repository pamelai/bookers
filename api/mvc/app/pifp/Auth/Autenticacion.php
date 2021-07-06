<?php

namespace pifp\Auth;

use Lcobucci\JWT\Builder;
use pifp\Models\Usuario;


class Autenticacion
{

    /**
     * @const string
     * Token
     */
    const JWT_KEY = 'Hih3j8nUAq66sTcOhZFQAQycoT96mH';

    /**
     * @var int
     * Id de Token
     */
    private $id;

    /**
     * @param $id
     * @return \Lcobucci\JWT\Token
     *
     * Genera el token
     */
    public function generarToken($id)
    {
        $encriptador = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        $builder = new Builder();
        $token = $builder->setIssuer('pifp')
            ->set('id', $id)
            ->sign($encriptador, self::JWT_KEY)
            ->getToken();

        return $token;
    }

    /**
     * @param $token
     * @return array|bool
     *
     * Verifica la exitencia del token y si coincide con el actual
     */
    public function verificarToken($token)
    {
        if (empty($token)) {
            return false;
        }

        $parser = new \Lcobucci\JWT\Parser();
        $tokenParsed = $parser->parse($token);
        $encriptador = new \Lcobucci\JWT\Signer\Hmac\Sha256();

        if ($tokenParsed->verify($encriptador, self::JWT_KEY)) {
            return [
                'id' => $tokenParsed->getClaim('id')
            ];
        } else
            return false;
    }

    /**
     * @param Usuario $usuario
     * @param $password
     * @return bool
     *
     * Autentica al usuario
     */
    public function autenticar(Usuario $usuario, $password)
    {

        if (password_verify($password, $usuario->getPassword())) {
            $token = self::generarToken($usuario->getId());
            $this->id = $usuario->getId();

            setcookie('token', (string)$token, time() + 3600 * 24, "/", null, false, true);
            return true;
        };

        throw new \Exception(false);
    }

    /**
     * @return bool
     *
     * Determina si el usuario esta autenticado o no
     */
    public function usuarioAutenticado()
    {
        return self::verificarToken($_COOKIE['token']);
    }

    /**
     * @return bool|null
     *
     * Devuelve el usuario que esté autenticado actualmente
     */
    public function getUsuarioAutenticado()
    {
        if ($this->usuarioAutenticado()) {
            $usuario = new Usuario;
            return $usuario->usuarioPorId($this->id);
        }
        return null;
    }


    /**
     * Cierra la sesion
     */
    public function cerrarSesion()
    {
        if ($this->usuarioAutenticado()) {
            setcookie('token', null, time() - 3600 * 24, "/", null, false, true);
            return true;
        }

        throw new \Exception(false);
    }
}

