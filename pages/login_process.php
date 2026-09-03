<?php
session_start();
require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim(filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';

    // Validaciones básicas
    if (empty($correo) || empty($password)) {
        header("Location: ../index.php?error=" . urlencode("Correo y contraseña obligatorios."));
        exit;
    }

    try {
        // Buscar el usuario por correo
        $stmt = $pdo->prepare("SELECT id, nombre_completo, rol, avatar, password FROM usuarios WHERE correo = :correo LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if ($user && password_verify($password, $user['password'])) {
            
            // Credenciales correctas, inicializar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre_completo'];
            $_SESSION['user_avatar'] = $user['avatar'];
            $_SESSION['user_role'] = $user['rol']; // Corregido: antes era user_rol
            
            // Redirigir al dashboard unificado
            header("Location: dashboard.php");
            exit;
        } else {
            // Credenciales incorrectas
            // Para depuración temporal: mostrar mensaje en pantalla
            die("Error: Credenciales incorrectas o el usuario no existe.");
            // header("Location: ../index.php?error=" . urlencode("Credenciales incorrectas."));
            // exit;
        }

    } catch (PDOException $e) {
        // Manejo de errores temporal para depuración detallada
        die("<h3>Fallo de PDO detectado en Login:</h3><p>" . htmlspecialchars($e->getMessage()) . "</p><p>Asegúrate de que la tabla 'usuarios' tenga la columna 'password'.</p>");
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>
