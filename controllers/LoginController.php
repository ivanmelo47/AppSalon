<?php
namespace Controllers;

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

        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
}