<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

Class LoginController {
    public static function login(Router $router){
        
        $router->render('auth/login');
    }

    public static function logout(){
        echo "Cerrar sesión";
    }

    public static function olvide(Router $router){

        $hola = 'Hola Papus';
        $router->render('auth/olvide-password', compact(
            'hola'));
    }

    public static function recuperar(){
        echo "Recuperar";
    }

    public static function crear_cuenta(Router $router){

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
                    $alertas =  Usuario::getAlertas();
                }else {
                    // Hashear el password
                    $usuario->hashPassword();

                    //Generar Token único
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();
                    
                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado){
                        echo "Guardar";
                    }
                }
            }

        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function confirmar_cuenta(){

    }
}