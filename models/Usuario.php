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
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    //Mensaje de validacion para la creacion de una cuenta
    public function validarNuevaCuenta()
    {
        if (!($this->nombre)) {
            self::$alertas['error'][] = 'El Nombre del cliente es obligatorio';
        }else {
            $pattern = '/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/u';
            if (!preg_match($pattern, $this->nombre)) {
                self::$alertas['error'][] = 'El Nombre del cliente tiene un formato no valido';
            } 
        }

        if (!($this->apellido)) {
            self::$alertas['error'][] = 'El Apellido del cliente es obligatorio';
        }else {
            $pattern = '/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/u';
            if (!preg_match($pattern, $this->apellido)) {
                self::$alertas['error'][] = 'El Apellido del cliente tiene un formato no valido';
            } 
        }

        if (!($this->telefono)) {
            self::$alertas['error'][] = 'El Teléfono del cliente es obligatorio';
        } elseif (!preg_match('/^\(\d{3}\)\s?\d{3}\s?\d{4,}$/', preg_replace('/\s/', '', $this->telefono))) {
            self::$alertas['error'][] = 'El Teléfono del cliente no tiene un formato válido.';
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

    // Revisa si el usuario ya esta registrado
    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' limit 1";
        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

}