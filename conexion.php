<?php
require_once __DIR__ . '/config.php';

// Devuelve siempre la misma conexión PDO (se crea la primera vez)
function conectar(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=biblioteca;charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USUARIO, DB_CLAVE, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // los errores lanzan excepciones
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // filas como arrays asociativos
        ]);
    }

    return $pdo;
}

// Escapa un texto antes de mostrarlo en HTML (evita XSS)
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}
