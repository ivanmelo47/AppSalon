<?php
namespace Controllers;

use MVC\Router;

Class LoginController {
    public static function login(Router $router){
        
        $router->render('auth/login');
    }

    public static function logout(){
        echo "Cerrar sesión";
    }

    public static function olvide(){
        echo "Olvide";
    }

    public static function recuperar(){
        echo "Recuperar";
    }

    public static function crear_cuenta(Router $router){

        $hola = 'Hola Papus';
        $router->render('auth/crear-cuenta', compact('hola'));
    }
}