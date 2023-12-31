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
                header('Location: /cita');
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

                if ($usuario) {
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        $_SESSION['rol'] = ($usuario->admin === "1") ? "admin" : "cliente";
    
                        // Redireccionar al rol correspondiente
                        header('Location: ' . ($_SESSION['rol'] === "admin" ? '/admin' : '/cita'));
                        exit();
                    }
                }else {
                    Usuario::setAlerta('error', 'No existe ninguna cuenta registrada con este Email');
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
        session_start();

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                // Validar que el usuario exista y este confrimado
                if ($usuario && $usuario->confirmado === "1") {
                    //Generar Token único
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Guardar nuevo token en BD
                    $usuario->guardar();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Las instrucciones para recuperar tu cuenta han sido enviadas a tu email.');
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no ha sido confirmado.');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas,
        ]);
    }

    public static function recuperar_cuenta(Router $router)
    {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'El Token no es válido.');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;

                $usuario->hashPassword();

                $usuario->token = null;

                $resultado = $usuario->guardar();

                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar_cuenta', [
            'alertas' => $alertas,
            'error' => $error,
        ]);
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

/* public static function login(Router $router)
    {
        session_start();
        if (isset($_SESSION['login'])) { // Uso de 'isset' para saber si existe la variable de [login] en la supervariable de $_SESSION
            if ($_SESSION['login'] === true) {
                debuguear($_SESSION);
                if ($_SESSION['rol'] === "admin") {
                    header('Location: /admin');
                } elseif ($_SESSION['rol'] === "cliente") {
                    header('Location: /cliente');
                }
            }
        }
        
        $alertas = [];

        $auth = new Usuario();

        // Verifica si existe una respuesta 'SUBMIT' desde el formulario de login
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $auth = new Usuario($_POST);

            // Valida que todos los campos del formulario de login hayan sido llenados
            $alertas = $auth->validarLogin();

            // Valida que no haya ninguna alerta
            if (empty($alertas)) {
                //Comprabar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                if ($usuario) {
                    //Comprobar el password sea correcto y la cuenta este confirmada
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario

                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        if ($usuario->admin === "1") {
                            $_SESSION['rol'] = "admin";
                        }else {
                            $_SESSION['rol'] = "cliente";
                        }

                        // Redireccionamiento
                        if ($_SESSION['rol'] === "admin") {
                            header('Location: /admin');
                        } elseif ($_SESSION['rol'] === "cliente") {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth,
        ]);
    } */