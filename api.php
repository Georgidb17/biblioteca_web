<?php
// API JSON: responde con datos, no con HTML.
//   GET  api.php?accion=libros&q=texto
//   POST api.php  accion=prestar   socio_id, libro_id
//   POST api.php  accion=devolver  id
require_once 'datos.php';

header('Content-Type: application/json; charset=utf-8');

function responder(array $datos, int $codigo = 200): void
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

try {
    if ($accion === 'libros') {
        $libros = listar_libros(trim($_GET['q'] ?? ''));
        // Convertimos los números para que JavaScript los reciba como números
        foreach ($libros as &$libro) {
            $libro['id'] = (int) $libro['id'];
            $libro['anio'] = $libro['anio'] === null ? null : (int) $libro['anio'];
            $libro['precio'] = (float) $libro['precio'];
            $libro['disponible'] = (bool) $libro['disponible'];
        }
        responder(['ok' => true, 'libros' => array_values($libros)]);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        responder(['ok' => false, 'error' => 'Acción no válida'], 400);
    }

    if ($accion === 'prestar') {
        $mensaje = prestar_libro((int) ($_POST['socio_id'] ?? 0), (int) ($_POST['libro_id'] ?? 0));
        responder(['ok' => $mensaje === 'Préstamo registrado', 'mensaje' => $mensaje]);
    }

    if ($accion === 'devolver') {
        $ok = devolver_libro((int) ($_POST['id'] ?? 0));
        responder([
            'ok' => $ok,
            'mensaje' => $ok ? 'Libro devuelto' : 'Ese préstamo ya estaba devuelto',
            'fecha' => date('d/m/Y'),
        ]);
    }

    responder(['ok' => false, 'error' => 'Acción no válida'], 400);
} catch (PDOException $ex) {
    responder(['ok' => false, 'error' => 'Error de base de datos'], 500);
}
