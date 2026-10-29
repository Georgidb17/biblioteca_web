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

function listar_socios(): array
{
    $sql = 'SELECT s.id, s.nombre, s.apellidos, s.email, s.ciudad,
                   COUNT(p.id) AS total,
                   SUM(CASE WHEN p.id IS NOT NULL AND p.fecha_devolucion IS NULL
                            THEN 1 ELSE 0 END) AS pendientes
            FROM socios s
            LEFT JOIN prestamos p ON p.socio_id = s.id
            GROUP BY s.id, s.nombre, s.apellidos, s.email, s.ciudad
            ORDER BY s.nombre';
    return conectar()->query($sql)->fetchAll();
}


// ---------- Préstamos ----------

function listar_prestamos(bool $solo_pendientes = false): array
{
    $sql = 'SELECT p.id, s.nombre, s.apellidos, l.titulo,
                   p.fecha_prestamo, p.fecha_devolucion
            FROM prestamos p
            JOIN socios s ON p.socio_id = s.id
            JOIN libros l ON p.libro_id = l.id';
    if ($solo_pendientes) {
        $sql .= ' WHERE p.fecha_devolucion IS NULL';
    }
    $sql .= ' ORDER BY p.fecha_prestamo DESC, p.id DESC';
    return conectar()->query($sql)->fetchAll();
}

function libro_prestado(int $libro_id): bool
{
    $sql = 'SELECT COUNT(*) FROM prestamos
            WHERE libro_id = :libro AND fecha_devolucion IS NULL';
    $consulta = conectar()->prepare($sql);
    $consulta->execute([':libro' => $libro_id]);
    return $consulta->fetchColumn() > 0;
}

// Misma regla que el procedimiento prestar_libro de la práctica SQL
function prestar_libro(int $socio_id, int $libro_id): string
{
    if (libro_prestado($libro_id)) {
        return 'El libro ya está prestado';
    }
    $sql = 'INSERT INTO prestamos (socio_id, libro_id, fecha_prestamo)
            VALUES (:socio, :libro, :hoy)';
    conectar()->prepare($sql)->execute([
        ':socio' => $socio_id,
        ':libro' => $libro_id,
        ':hoy'   => date('Y-m-d'),
    ]);
    return 'Préstamo registrado';
}

function devolver_libro(int $prestamo_id): bool
{
    $sql = 'UPDATE prestamos SET fecha_devolucion = :hoy
            WHERE id = :id AND fecha_devolucion IS NULL';
    $consulta = conectar()->prepare($sql);
    $consulta->execute([':hoy' => date('Y-m-d'), ':id' => $prestamo_id]);
    return $consulta->rowCount() === 1;   // true si había algo que devolver
}

// Para los desplegables del formulario de préstamo
function opciones_socios(): array
{
    return conectar()->query('SELECT id, nombre, apellidos FROM socios ORDER BY nombre')->fetchAll();
}

function opciones_libros_disponibles(): array
{
    return array_filter(listar_libros(), fn($libro) => $libro['disponible'] == 1);
}


