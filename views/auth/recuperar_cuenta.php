<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Introduce una nueva contraseña para tu cuenta</p>

<?php
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error == false) { ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Tu Nuevo Password" />
    </div>
    <input type="submit" class="boton" value="Cambiar contraseña">
</form>

<?php } ?>

<div class="acciones">
    <a href="/">Ya tienes cuenta? Iniciar Sesión</a>
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crear una</a>
</div>