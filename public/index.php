<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\APIController;
use Controllers\LoginController;
use Controllers\CitaController;
use Controllers\ServicioController;
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
$router->get('/admin', [AdminController::class, 'index']);

/* CRUD de Servicios */
$router->get('/servicios', [ServicioController::class, 'index']);
$router->get('/servicios/crear', [ServicioController::class, 'crear_servicio']);

/* API de Citas */
$router->get('/api/servicios', [APIController::class, 'index']);
$router->post('/api/citas', [APIController::class, 'guardar_citas']);
$router->post('/api/eliminar', [APIController::class, 'eliminar_citas']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();