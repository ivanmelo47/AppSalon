<?php if (isset($alertas['error'][$campo])): ?>
    <div class="alerta error">
        <?php echo $alertas['error'][$campo]; ?><br>
    </div>
<?php endif; ?>