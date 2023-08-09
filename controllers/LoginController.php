<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        // Iniciar la sesión solo una vez
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Si el usuario ya está autenticado, redirigir a la página correspondiente
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            if ($_SESSION['rol'] === "admin") {
                header('Location: /admin');
            } elseif ($_SESSION['rol'] === "cliente") {
                header('Location: /cliente');
            }
            exit();
        }

        $alertas = [];
        $auth = new Usuario();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->comprobarPasswordAndVerificado($auth->password)) {
                    // Autenticar el usuario
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;
                    $_SESSION['rol'] = ($usuario->admin === "1") ? "admin" : "cliente";

                    // Redireccionar al rol correspondiente
                    header('Location: ' . ($_SESSION['rol'] === "admin" ? '/admin' : '/cita'));
                    exit();
                } else {
                    Usuario::setAlerta('error', 'Credenciales incorrectas o cuenta no verificada.');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth,
        ]);
    }
    public static function logout()
    {
        echo "Cerrar sesión";
    }

    public static function olvide(Router $router)
    {

        $hola = 'Hola Papus';
        $router->render(
            'auth/olvide-password',
            compact(
                'hola'
            )
        );
    }

    public static function recuperar()
    {
        echo "Recuperar";
    }

    public static function crear_cuenta(Router $router)
    {

        $usuario = new Usuario;
        // Alertas vacías
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alertas este vacío
            if (empty($alertas)) {
                // Verificar que e usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    //Generar Token único
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }

        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    public static function confirmar_cuenta(Router $router)
    {
        $alertas = [];

        $token = s($_GET['token']);

        // Busca/confirma el token en la BD
        $usuario = Usuario::where('token', $token);

        // Verifica si el token fue encontrado o no
        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'El Token no es valido..');
        } else {
            // Modificar usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();

            Usuario::setAlerta('exito', 'La cuenta ha sido confirmado con exito!');
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();

        //Renderizar la vista
        $router->render('auth/confirmar_cuenta', [
            'alertas' => $alertas
        ]);
    }
}