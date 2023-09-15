<?php

namespace Model;

class Servicio extends ActiveRecord
{
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    /* Alertas */
    public function validar()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Servicio es Obligatorio';
        }
        if (!$this->precio) {
            self::$alertas['error'][] = 'El Precio del Servicio es Obligatorio';
        } else if (!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'El Precio no es Valido';
        }


        /* if (!$this->nombre) {
            self::$alertas['error']['nombre'] = 'El Nombre del Servicio es Obligatorio';
        }
        if (!$this->precio) {
            self::$alertas['error']['precio'] = 'El Precio del Servicio es Obligatorio';
        } */


        return self::$alertas;
    }
}