<?php
    
namespace Controllers;
use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {

    public static function index(){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar_citas(){

        // Almacena la cita y devuelve el Id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];

        // Alamacena los Servicios con Id de la cita
        $idServicios = explode(",", $_POST['servicios']);
        foreach ($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar_citas(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];

            $cita = Cita::find($id);

            $cita->eliminar();

            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }

}