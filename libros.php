<?php
$titulo = 'Libros';
require 'cabecera.php';
require_once 'datos.php';

$texto = trim($_GET['q'] ?? '');
$libros = listar_libros($texto);
?>
<h2>Libros</h2>

<form method="get" class="barra">
    <input type="search" name="q" id="buscar" placeholder="Título, autor o género"
           value="<?= e($texto) ?>">
    <button type="submit">Buscar</button>
    <a class="boton" href="libro_nuevo.php">+ Nuevo libro</a>
</form>

<p id="contador"><?= count($libros) ?> libros</p>

<table>
    <thead>
        <tr><th>Título</th><th>Autor</th><th>Género</th><th>Año</th><th>Precio</th><th>Estado</th></tr>
    </thead>
    <tbody id="tabla-libros">
        <?php foreach ($libros as $libro): ?>
            <tr>
                <td><?= e($libro['titulo']) ?></td>
                <td><?= e($libro['autor']) ?></td>
                <td><?= e($libro['genero']) ?></td>
                <td><?= $libro['anio'] ?></td>
                <td><?= number_format((float) $libro['precio'], 2, ',', '.') ?> €</td>
                <td>
                    <?php if ($libro['disponible']): ?>
                        <span class="etiqueta ok">Disponible</span>
                    <?php else: ?>
                        <span class="etiqueta no">Prestado</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script src="js/libros.js"></script>
<?php require 'pie.php'; ?>