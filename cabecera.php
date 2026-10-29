<?php require_once __DIR__ . '/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? 'Biblioteca') ?> · Biblioteca municipal</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<header>
    <h1>📚 Biblioteca municipal</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="libros.php">Libros</a>
        <a href="socios.php">Socios</a>
        <a href="prestamos.php">Préstamos</a>
    </nav>
</header>
<main>
