<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;
    public $host = 'localhost:3000';

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {

        // create a new object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('josue@appsalon.com');
        $mail->addAddress('josue@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu Cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<head>';
        $contenido .= '<style>';
        $contenido .= 'body { font-family: "Poppins", Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8; }';
        $contenido .= '.container { max-width: 600px; margin: 0 auto; padding: 20px; }';
        $contenido .= 'h1 { color: #007bff; margin-bottom: 20px; }';
        $contenido .= 'p { font-size: 16px; line-height: 1.6; margin-bottom: 15px; }';
        $contenido .= '.button {';
        $contenido .= '    display: inline-block;';
        $contenido .= '    background-color: #007bff;';
        $contenido .= '    color: #fff;';
        $contenido .= '    padding: 10px 20px;';
        $contenido .= '    border-radius: 5px;';
        $contenido .= '    text-decoration: none;';
        $contenido .= '    transition: background-color 0.3s, box-shadow 0.3s;'; // Agregamos la transición
        $contenido .= '    cursor: pointer;'; // Cambiamos el cursor
        $contenido .= '}';
        $contenido .= '.button:hover { background-color: #0056b3; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }'; // Cambiamos el sombreado en hover
        $contenido .= '.animated-greeting { animation: bounce 1s infinite; }';
        $contenido .= '@keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }';
        $contenido .= '</style>';
        $contenido .= '</head>';
        $contenido .= '<body>';
        $contenido .= '<div class="container">';
        $contenido .= '<h1 class="animated-greeting">¡Hola ' . $this->nombre . '! ✌️😎</h1>';
        $contenido .= '<p><strong>¡Bienvenido a App Salón!</strong></p>';
        $contenido .= '<p>Has creado tu cuenta en App Salón. Solo debes confirmarla presionando el siguiente botón:</p>';
        $contenido .= '<a class="button" href="'.$_ENV['APP_URL'].'/confirmar-cuenta?token=' . $this->token . '">Confirmar Cuenta</a>';
        $contenido .= '<p>Si tú no solicitaste esta cuenta, puedes ignorar este mensaje.</p>';
        $contenido .= '<p style="font-size: 14px; color: #444; margin-top: 20px;">Si no puedes ver correctamente este correo, puedes acceder al siguiente enlace para confirmar tu cuenta: <a href="http://'.$this->host.'/confirmar-cuenta?token=' . $this->token . '">Confirmar Cuenta</a></p>';
        $contenido .= '</div>';
        $contenido .= '</body>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();

    }

    public function enviarInstrucciones()
    {

        // create a new object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('josue@appsalon.com');
        $mail->addAddress('josue@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu password';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<head>';
        $contenido .= '<style>';
        $contenido .= 'body { font-family: "Poppins", Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8; }';
        $contenido .= '.container { max-width: 600px; margin: 0 auto; padding: 20px; }';
        $contenido .= 'h1 { color: #007bff; margin-bottom: 20px; }';
        $contenido .= 'p { font-size: 16px; line-height: 1.6; margin-bottom: 15px; }';
        $contenido .= '.button {';
        $contenido .= '    display: inline-block;';
        $contenido .= '    background-color: #007bff;';
        $contenido .= '    color: #fff;';
        $contenido .= '    padding: 10px 20px;';
        $contenido .= '    border-radius: 5px;';
        $contenido .= '    text-decoration: none;';
        $contenido .= '    transition: background-color 0.3s, box-shadow 0.3s;';
        $contenido .= '    cursor: pointer;';
        $contenido .= '}';
        $contenido .= '.button:hover { background-color: #0056b3; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }';
        $contenido .= '.animated-greeting { animation: bounce 1s infinite; }';
        $contenido .= '@keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }';
        $contenido .= '</style>';
        $contenido .= '</head>';
        $contenido .= '<body>';
        $contenido .= '<div class="container">';
        $contenido .= '<p><strong>Hola ' . $this->nombre . '! ✌️😎</strong></p>';
        $contenido .= '<p>Has solicitado reestablecer tu contraseña. Sigue el siguiente enlace para hacerlo:</p>';
        $contenido .= '<a class="button" href="'.$_ENV['APP_URL'].'/recuperar-cuenta?token=' . $this->token . '">Reestablecer Contraseña</a>';
        $contenido .= '<p>Si no solicitaste este cambio, puedes ignorar este mensaje.</p>';
        $contenido .= '<p style="font-size: 14px; color: #444; margin-top: 20px;">Si no puedes ver correctamente este correo, puedes acceder al siguiente enlace para recuperar tu cuenta: <a href="http://'.$this->host.'/recuperar-cuenta?token=' . $this->token . '">Recuperar Cuenta</a></p>';
        $contenido .= '</div>';
        $contenido .= '</body>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();
    }
}