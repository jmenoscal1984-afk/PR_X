<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/auth_middleware.php';
require_once '../includes/conexion.php';

function redirectWithError($message) {
    header("Location: register.php?error=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recoger y limpiar datos
    $nombre   = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $correo   = trim(filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';
    $rol      = $_POST['rol'] ?? 'alumno'; // Valor por defecto
    
    // Si es profesor no requiere avatar en el mismo formato, o se asigna por defecto
    $avatar = '👨‍🏫';
    if ($rol === 'alumno') {
        $avatar = trim(filter_input(INPUT_POST, 'avatar', FILTER_SANITIZE_STRING));
    }

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($password)) {
        redirectWithError("Todos los campos son obligatorios.");
    }

    // Validación CSRF
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($csrf_token)) {
        redirectWithError("Token CSRF inválido.");
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        redirectWithError("El formato del correo es inválido.");
    }

    if (strlen($password) < 6) {
        redirectWithError("La contraseña debe tener al menos 6 caracteres.");
    }
    
    if (!in_array($rol, ['alumno', 'profesor'])) {
        redirectWithError("Rol inválido.");
    }

    try {
        // Verificar si el correo ya existe
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo LIMIT 1");
        $stmtCheck->execute([':correo' => $correo]);
        
        if ($stmtCheck->fetch()) {
            redirectWithError("El correo ya está registrado. Intenta iniciar sesión.");
        }

        // 2. Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Insertar usuario en la base de datos (MySQL)
        $sql = "INSERT INTO usuarios (rol, nombre_completo, correo, password, avatar) 
                VALUES (:rol, :nombre, :correo, :password, :avatar)";
        
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([
            ':rol'      => $rol,
            ':nombre'   => $nombre,
            ':correo'   => $correo,
            ':password' => $hashed_password,
            ':avatar'   => $avatar
        ])) {
            session_start();
            $_SESSION['usuario_id'] = $pdo->lastInsertId();
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['usuario_rol'] = $rol;
            header("Location: dashboard.php");
            exit;
        } else {
            redirectWithError("Error al crear la cuenta. Inténtalo más tarde.");
        }

    } catch (PDOException $e) {
        error_log("Fallo de PDO detectado en Registro: " . $e->getMessage());
        redirectWithError("Error de base de datos. Por favor, intenta de nuevo.");
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
