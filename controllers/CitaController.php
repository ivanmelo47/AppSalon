<?php

namespace Controllers;

use MVC\Router;

class CitaController
{
    public static function index(Router $router){
        // Iniciar la sesión solo una vez
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre']
        ]);
    }
}