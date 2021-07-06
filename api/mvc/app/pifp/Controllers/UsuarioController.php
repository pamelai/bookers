<?php

namespace pifp\Controllers;
use pifp\Auth\Autenticacion;
use pifp\Models\Usuario;
use pifp\Validations\Validacion;

//require explode('\app', __DIR__)[0] . '/image.intervention/vendor/autoload.php';
// use Intervention\Image\ImageManager as Image;
use Intervention\Image\ImageManagerStatic as Image;

class UsuarioController extends Controller
{


    /**
     * @var array url a excluir de la verificación del token
     */
    protected $sinVerificar = ["/login", '/registrarse'];

    /**
     * @return json
     * Logea al usuario
     */
    public function login()
    {
        $post = $this->obtenerDatos();

        $reglas = [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];

        $validacion = new Validacion($post, $reglas);
        if (!$validacion->valido()) {
            $errores = $validacion->obtenerErrores();

            echo json_encode([
                "estado" => 0,
                "mensaje" => $errores
            ]);

            exit;
        }

        $usuario = new Usuario();

        try {
            $usuario = $usuario->usuarioPorMail($post['email']);

        } catch (\Exception $e) {
            $data = [
                "estado" => 0,
                "mensaje" => "Los datos ingresados son incorrectos"
            ];
            echo json_encode($data);
            exit;
        }


        $aut = new Autenticacion();
        try {
            $aut = $aut->autenticar($usuario, $post["password"]);

        } catch (\Exception $e) {
            $data = [
                "estado" => 0,
                "mensaje" => "Los datos ingresados son incorrectos"
            ];
            echo json_encode($data);
            exit;
        }

        echo json_encode([
            'estado' => 1,
            'mensaje' => "Bienvenido a nuestro sitio",
            'data' => $usuario
        ]);
    }

    /**
     * @return json
     * Cierra la sesión del usuario
     */
    public function logout()
    {

        $aut = new Autenticacion();
        try {

            $aut->cerrarSesion();

        } catch (\Exception $e) {
            echo json_encode([
                "estado" => 0,
                "mensaje" => "Hubo un error al cerrar la sesión, inténtelo más tarde"
            ]);
            exit;
        }

        echo json_encode([
            'estado' => 1,
            'mensaje' => 'Has cerrado sesión con éxito.'
        ]);
    }

    /**
     * @return json
     * Registra al usuario
     */
    public function registro()
    {
        $post = $this->obtenerDatos();

        $reglas = [
            'usuario' => ['required'],
            'password' => ['required', 'min:6'],
            'email' => ['required', 'email']
        ];

        $validacion = new Validacion($post, $reglas);
        if (!$validacion->valido()) {
            $errores = $validacion->obtenerErrores();

            echo json_encode([
                "estado" => 0,
                "mensaje" => $errores
            ]);

            exit;
        }

        $usuario = new Usuario;
        try {
            $fila = $usuario->usuarioPorMail($post['email']);

            if ($fila):
                $errores['email'] = 'Email ya existente';

                $data = [
                    "estado" => 0,
                    "mensaje" => $errores
                ];

                echo json_encode($data);
                exit;

            endif;

        } catch (\Exception $e) {
        }


        $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
        $post['imagen'] = 'imagenes/usuarios/user_img.jpg';

        try {
            $usuario->crear($post);

            echo json_encode([
                'estado' => 1,
                'mensaje' => "Gracias por registrarte. Ya puedes iniciar sesión"
            ]);

        } catch (\Exception $e) {
            $data = [
                "estado" => 0,
                "mensaje" => 'Ocurrió un error a la hora de registrarte. Inténtalo más tarde'
            ];

            echo json_encode($data);
            exit;
        }


    }

    /**
     * @return json
     * Edita los datos del usuario
     */
    public function editar()
    {
        $post = $this->obtenerDatos();

        $reglas = [
            'nombre' => ['string'],
            'apellido' => ['string'],
            'usuario' => ['required', 'string'],
            'email' => ['required', 'email']
        ];

        if (!empty($post['password'])) {
            $reglas['password'] = ['required', 'min:6'];
        }
        if (!empty($post['imagen']) && base64_decode($post['imagen'], true)) {
            $reglas['imagen'] = ['jpeg'];
        }

        $validacion = new Validacion($post, $reglas);
        if (!$validacion->valido()) {
            $errores = $validacion->obtenerErrores();


            echo json_encode([
                "estado" => 0,
                "mensaje" => $errores
            ]);

            exit;
        }

        if (!empty($post['password'])) {
            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
        }


        if (!empty($post['imagen']) && base64_decode($post['imagen'])):
            $imgData = explode(',', $post['imagen']);
            $img64 = $imgData[1];
            $post['imagen'] = $img64;
            $ext = '.jpg';

            $imgDecoded = base64_decode($img64);
            $imgName = str_replace(' ', '_', $post['usuario']);
            $imgName = $imgName . $ext;
            $filepath = explode('\api', __DIR__)[0] . "/imagenes/usuarios/" . $imgName;
            $res = file_put_contents($filepath, $imgDecoded);

            $post['imagen'] = "imagenes/usuarios/" . $imgName;

//            Image::configure(array('driver' => 'imagick'));
//            echo $image = Image::make(explode('\api', __DIR__)[0] . "/imagenes/usuarios/" . $imgName)
            Image::make($filepath)->crop(300, 300)->save($filepath, 60);
        elseif (empty($post['imagen'])):
            $post['imagen'] = 'imagenes/usuarios/user_img.jpg';

        endif;


        $usuario = new Usuario;
        try {


            $usuario->editar($post);

            echo json_encode([
                "estado" => 1,
                "mensaje" => "Se actualizó el usuario correctamente",
                "data" => $post
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                "estado" => 0,
                "mensaje" => "No se pudo actualizar el usuario, inténtelo más tarde"
            ]);
        }

    }

    /**
     * @return json
     * Elimina al usuario y todos los datos relacionados con el mismo
     */
    public function eliminar()
    {
        $post = $this->obtenerDatos();

        $usuario = new Usuario;
        try {
            $usuario->eliminar($post);

            setcookie('token', null, time() - 3600 * 24, "/", null, false, true);

            echo json_encode([
                "estado" => 1,
                "mensaje" => "Su cuenta fue eliminada con éxito"
            ]);

        } catch (\Exception $e) {

            echo json_encode([
                "estado" => 0,
                "mensaje" => "Hubo un error a la hora de eliminar su cuenta. Inténtalo más tarde."
            ]);
        }
    }
}