<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrando sesión...</title>
</head>
<body>
    <script>
        // Limpiar localStorage (sesión del frontend JS)
        localStorage.removeItem('eq_session');
        // Redirigir al inicio
        window.location.href = '../index.php';
    </script>
</body>
</html>
