<?php
$titulo = 'Nuevo libro';
require_once 'datos.php';

$generos = ['Novela', 'Infantil', 'Fantasía', 'Ensayo', 'Informática'];
$errores = [];
$libro = ['titulo' => '', 'autor' => '', 'genero' => '', 'anio' => '', 'paginas' => '', 'precio' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recoger los datos del formulario
    foreach ($libro as $campo => $valor) {
        $libro[$campo] = trim($_POST[$campo] ?? '');
    }

    // 2. Validar
    if ($libro['titulo'] === '') {
        $errores[] = 'El título es obligatorio.';
    }
    if ($libro['autor'] === '') {
        $errores[] = 'El autor es obligatorio.';
    }
    if (!in_array($libro['genero'], $generos)) {
        $errores[] = 'Elige un género de la lista.';
    }
    if ($libro['anio'] !== '' && !ctype_digit($libro['anio'])) {
        $errores[] = 'El año debe ser un número.';
    }
    if ($libro['paginas'] !== '' && !ctype_digit($libro['paginas'])) {
        $errores[] = 'Las páginas deben ser un número.';
    }
    if ($libro['precio'] !== '' && !is_numeric($libro['precio'])) {
        $errores[] = 'El precio debe ser un número (usa punto para los decimales).';
    }

    // 3. Si todo está bien, guardar y volver al listado
    if (empty($errores)) {
        insertar_libro([
            ':titulo'  => $libro['titulo'],
            ':autor'   => $libro['autor'],
            ':genero'  => $libro['genero'],
            ':anio'    => $libro['anio'] === '' ? null : (int) $libro['anio'],
            ':paginas' => $libro['paginas'] === '' ? null : (int) $libro['paginas'],
            ':precio'  => $libro['precio'] === '' ? null : $libro['precio'],
        ]);
        header('Location: libros.php?q=' . urlencode($libro['titulo']));
        exit;
    }
}

require 'cabecera.php';
?>
<h2>Nuevo libro</h2>

<div id="errores" class="errores" <?= empty($errores) ? 'hidden' : '' ?>>
    <ul>
        <?php foreach ($errores as $error): ?>
            <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<form method="post" id="form-libro" class="formulario" novalidate>
    <label>Título <input type="text" name="titulo" value="<?= e($libro['titulo']) ?>"></label>
    <label>Autor <input type="text" name="autor" value="<?= e($libro['autor']) ?>"></label>
    <label>Género
        <select name="genero">
            <option value="">— Elige —</option>
            <?php foreach ($generos as $genero): ?>
                <option <?= $libro['genero'] === $genero ? 'selected' : '' ?>><?= $genero ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Año <input type="text" name="anio" value="<?= e($libro['anio']) ?>"></label>
    <label>Páginas <input type="text" name="paginas" value="<?= e($libro['paginas']) ?>"></label>
    <label>Precio (€) <input type="text" name="precio" value="<?= e($libro['precio']) ?>"></label>
    <button type="submit">Guardar</button>
    <a href="libros.php">Cancelar</a>
</form>

<script src="js/libro_nuevo.js"></script>
<?php require 'pie.php'; ?>