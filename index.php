<?php
$titulo = 'Inicio';
require 'cabecera.php';
require_once 'datos.php';

$mas_prestados = libros_mas_prestados();
?>
<h2>Resumen</h2>

<section class="tarjetas">
    <div class="tarjeta"><span><?= contar('libros') ?></span> libros</div>
    <div class="tarjeta"><span><?= contar('socios') ?></span> socios</div>
    <div class="tarjeta"><span><?= contar_pendientes() ?></span> préstamos pendientes</div>
</section>

<h2>Libros más prestados</h2>
<ol>
    <?php foreach ($mas_prestados as $libro): ?>
        <li><?= e($libro['titulo']) ?> (<?= $libro['veces'] ?> veces)</li>
    <?php endforeach; ?>
</ol>

<?php require 'pie.php'; ?>