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

function listar_libros(string $texto = ''): array
{
    $sql = 'SELECT l.id, l.titulo, l.autor, l.genero, l.anio, l.precio,
                   CASE WHEN EXISTS (SELECT 1 FROM prestamos p
                                     WHERE p.libro_id = l.id
                                       AND p.fecha_devolucion IS NULL)
                        THEN 0 ELSE 1 END AS disponible
            FROM libros l
            WHERE l.titulo LIKE :t1 OR l.autor LIKE :t2 OR l.genero LIKE :t3
            ORDER BY l.titulo';
    $consulta = conectar()->prepare($sql);
    $buscar = '%' . $texto . '%';
    $consulta->execute([':t1' => $buscar, ':t2' => $buscar, ':t3' => $buscar]);
    return $consulta->fetchAll();
}

function insertar_libro(array $libro): int
{
    $sql = 'INSERT INTO libros (titulo, autor, genero, anio, paginas, precio)
            VALUES (:titulo, :autor, :genero, :anio, :paginas, :precio)';
    conectar()->prepare($sql)->execute($libro);
    return (int) conectar()->lastInsertId();
}

