<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\LoginController;
use Controllers\CitaController;
use MVC\Router;

$router = new Router();

// Iniciar Sesion
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Recuperar Password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
$router->get('/recuperar-cuenta', [LoginController::class, 'recuperar_cuenta']);
$router->post('/recuperar-cuenta', [LoginController::class, 'recuperar_cuenta']);

//Crear cuentas
$router->get('/crear-cuenta', [LoginController::class, 'crear_cuenta']);
$router->post('/crear-cuenta', [LoginController::class, 'crear_cuenta']);

//Confirmar cuenta
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar_cuenta']);
$router->get('/mensaje', [LoginController::class, 'mensaje']);

/* AREA PRIVADA */
$router->get('/cita', [CitaController::class, 'index']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();