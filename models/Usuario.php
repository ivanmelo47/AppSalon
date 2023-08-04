<?php
namespace Model;

class Usuario extends ActiveRecord
{
    //Base de datos de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? null;
        $this->confirmado = $args['confirmado'] ?? null;
        $this->token = $args['token'] ?? '';
    }

    //Mensaje de validacion para la creacion de una cuenta
    public function validarNuevaCuenta()
    {
        if (!($this->nombre)) {
            self::$alertas['error'][] = 'El Nombre del cliente es obligatorio';
        }
        if (!($this->apellido)) {
            self::$alertas['error'][] = 'El Apellido del cliente es obligatorio';
        }
        if (!($this->telefono)) {
            self::$alertas['error'][] = 'El Telefono del cliente es obligatorio';
        }

        if (!($this->email)) {
            self::$alertas['error'][] = 'El Email del cliente es obligatorio';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El formato del correo electrónico no es válido';
        } else {
            $pattern = '/^[^@]+@[^@]+\.[a-z]{2,}$/i'; // Expresión regular para validar el correo electrónico.
            if (!preg_match($pattern, $this->email)) {
                self::$alertas['error'][] = 'El formato del correo electrónico no es válido';
            }
        }

        if (!($this->password)) {
            self::$alertas['error'][] = 'El Password del cliente es obligatorio';
        } elseif (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }
}