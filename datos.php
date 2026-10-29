<?php
// Todas las consultas a la base de datos están aquí.
// Las páginas y la API llaman a estas funciones.
require_once __DIR__ . '/conexion.php';

// ---------- Portada ----------

function contar(string $tabla): int
{
    // El nombre de tabla no viene del usuario: lo escribimos nosotros
    return (int) conectar()->query("SELECT COUNT(*) FROM $tabla")->fetchColumn();
}

function contar_pendientes(): int
{
    $sql = 'SELECT COUNT(*) FROM prestamos WHERE fecha_devolucion IS NULL';
    return (int) conectar()->query($sql)->fetchColumn();
}

function libros_mas_prestados(): array
{
    $sql = 'SELECT l.titulo, COUNT(*) AS veces
            FROM prestamos p
            JOIN libros l ON p.libro_id = l.id
            GROUP BY l.id, l.titulo
            HAVING COUNT(*) > 1
            ORDER BY veces DESC, l.titulo';
    return conectar()->query($sql)->fetchAll();
}
