<h1 class="nombre-pagina">Recuperar cuenta</h1>
<p class="descripcion-pagina">Introduce una nueva contraseña para tu cuenta</p>

<?php
include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/recuperar-cuenta" class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Tu Password" />
    </div>
    <input type="submit" class="boton" value="Cambiar contraseña">
</form>