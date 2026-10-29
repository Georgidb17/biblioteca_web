<?php
$titulo = 'Socios';
require 'cabecera.php';
require_once 'datos.php';

$socios = listar_socios();
?>
<h2>Socios</h2>

<table>
    <thead>
        <tr><th>Nombre</th><th>Email</th><th>Ciudad</th><th>Préstamos</th><th>Sin devolver</th></tr>
    </thead>
    <tbody>
        <?php foreach ($socios as $socio): ?>
            <tr>
                <td><?= e($socio['nombre'] . ' ' . $socio['apellidos']) ?></td>
                <td><?= e($socio['email']) ?></td>
                <td><?= e($socio['ciudad']) ?></td>
                <td><?= $socio['total'] ?></td>
                <td><?= $socio['pendientes'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require 'pie.php'; ?>