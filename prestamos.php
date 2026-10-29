<?php
$titulo = 'Préstamos';
require_once 'datos.php';

// Acciones del formulario (POST) → después se redirige (patrón PRG)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'prestar') {
        $mensaje = prestar_libro((int) $_POST['socio_id'], (int) $_POST['libro_id']);
    } elseif ($accion === 'devolver') {
        $mensaje = devolver_libro((int) $_POST['id']) ? 'Libro devuelto' : 'Ese préstamo ya estaba devuelto';
    } else {
        $mensaje = 'Acción desconocida';
    }

    header('Location: prestamos.php?mensaje=' . urlencode($mensaje));
    exit;
}

$solo_pendientes = isset($_GET['pendientes']);
$prestamos = listar_prestamos($solo_pendientes);
$mensaje = $_GET['mensaje'] ?? '';

require 'cabecera.php';
?>
<h2>Préstamos</h2>

<p id="mensaje" class="mensaje" <?= $mensaje === '' ? 'hidden' : '' ?>><?= e($mensaje) ?></p>

<form method="post" class="barra">
    <input type="hidden" name="accion" value="prestar">
    <select name="socio_id" required>
        <?php foreach (opciones_socios() as $socio): ?>
            <option value="<?= $socio['id'] ?>"><?= e($socio['nombre'] . ' ' . $socio['apellidos']) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="libro_id" required>
        <?php foreach (opciones_libros_disponibles() as $libro): ?>
            <option value="<?= $libro['id'] ?>"><?= e($libro['titulo']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Prestar</button>
</form>

<p>
    <a href="prestamos.php">Todos</a> ·
    <a href="prestamos.php?pendientes">Solo pendientes</a>
</p>

<table>
    <thead>
        <tr><th>Socio</th><th>Libro</th><th>Prestado</th><th>Devuelto</th><th></th></tr>
    </thead>
    <tbody>
        <?php foreach ($prestamos as $p): ?>
            <tr>
                <td><?= e($p['nombre'] . ' ' . $p['apellidos']) ?></td>
                <td><?= e($p['titulo']) ?></td>
                <td><?= date('d/m/Y', strtotime($p['fecha_prestamo'])) ?></td>
                <td class="devuelto">
                    <?= $p['fecha_devolucion'] ? date('d/m/Y', strtotime($p['fecha_devolucion'])) : '—' ?>
                </td>
                <td>
                    <?php if (!$p['fecha_devolucion']): ?>
                        <form method="post" class="form-devolver">
                            <input type="hidden" name="accion" value="devolver">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button type="submit">Devolver</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script src="js/prestamos.js"></script>
<?php require 'pie.php'; ?>
