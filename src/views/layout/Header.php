<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda</title>
</head>
<body>
<header>
    <nav class="navPrincipal">
        <ul style="display: flex; gap: 15px">
            <li><a href="<?=BASE_URL?>">Inicio</a></li>
            <li><a href="<?=BASE_URL?>registro">Registro</a></li>
            <li><a href="<?=BASE_URL?>login">Login</a></li>
            <li><a href="<?=BASE_URL?>cierra-sesion">Cerrar sesion</a></li>
        </ul>
    </nav>
    <?php
    if (isset($error)): ?>
    <p class="error"><?=$error?></p>
    <?php elseif (isset($exito)): ?>
    <p class="exito"><?=$exito?></p>
    <?php endif; ?>
</header>
<main>