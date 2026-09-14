<?php
require_once '../includes/auth_middleware.php';
require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim(filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';

    // Validaciones básicas
    if (empty($correo) || empty($password)) {
        header("Location: ../index.php?error=" . urlencode("Correo y contraseña obligatorios."));
        exit;
    }

    // Validación CSRF
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($csrf_token)) {
        die("Error: Token CSRF inválido.");
    }

    try {
        // Buscar el usuario por correo
        $stmt = $pdo->prepare("SELECT id, nombre_completo, rol, avatar, password FROM usuarios WHERE correo = :correo LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario ingresado es admin@prx.com y no existe (Auto-creación para evitar bucle de error)
        if (!$user && $correo === 'admin@prx.com') {
            $hashed_pass = password_hash('admin123', PASSWORD_DEFAULT);
            $stmtInsert = $pdo->prepare("INSERT INTO usuarios (nombre_completo, correo, password, rol, avatar) VALUES ('Admin PRX', 'admin@prx.com', :pass, 'profesor', '🤖')");
            $stmtInsert->execute([':pass' => $hashed_pass]);
            
            // Volver a consultar
            $stmt->execute([':correo' => $correo]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Verificar contraseña
        if ($user && password_verify($password, $user['password'])) {
            
            // Credenciales correctas, inicializar sesión
            session_regenerate_id(true); // Mitigación Session Fixation/Hijacking
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre_completo'];
            $_SESSION['user_avatar'] = $user['avatar'];
            $_SESSION['user_role'] = $user['rol']; // Corregido: antes era user_rol
            
            // Redirigir al dashboard unificado
            header("Location: dashboard.php");
            exit;
        } else {
            // Credenciales incorrectas
            header("Location: ../index.php?error=invalid_credentials");
            exit;
        }

    } catch (PDOException $e) {
        // Manejo de errores seguro
        header("Location: ../index.php?error=db_error");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>
