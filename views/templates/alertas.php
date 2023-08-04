<?php
// $alertas es una estructura de datos que contiene las alertas clasificadas por tipo.
foreach ($alertas as $key => $mensajes): // Recorre cada tipo de alerta y sus mensajes asociados.
?>
    <?php foreach ($mensajes as $mensaje): // Recorre los mensajes de cada tipo de alerta. ?>
        <div class="alerta <?php echo $key; ?>">
            <?php echo $mensaje; // Imprime el mensaje de la alerta. ?>
        </div>
    <?php endforeach; // Fin del bucle de mensajes. ?>
<?php endforeach; // Fin del bucle de tipos de alerta. ?>
