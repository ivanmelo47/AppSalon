<div class="campo">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" placeholder="Nombre Servicio" name="nombre"
        value="<?php echo $servicio->nombre; ?>" />
</div>
<?php
    //$campo = 'nombre';
    //include __DIR__ . '/../templates/alertas_2.php';
?>

<div class="campo">
    <label for="precio">Precio</label>
    <input type="number" id="precio" placeholder="Precio Servicio" name="precio"
        value="<?php echo $servicio->precio; ?>" />
</div>
<?php
    //$campo = 'precio';
    //include __DIR__ . '/../templates/alertas_2.php';
?>