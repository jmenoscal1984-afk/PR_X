<?php
// pages/register_process.php
header('Content-Type: application/json');
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/auth_middleware.php';
require_once '../includes/db_connect.php';

function sendJsonResponse($success, $message = '') {
    ob_clean(); // Limpiar cualquier output indeseado antes del JSON
    $response = ['success' => $success];
    if (!empty($message)) {
        $response['message'] = $message;
    }
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recoger y limpiar datos
    $nombre   = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $correo   = trim(filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';
    $rol      = $_POST['rol'] ?? 'alumno'; 
    
    $avatar = '👨‍🏫';
    if ($rol === 'alumno') {
        $avatar = trim(filter_input(INPUT_POST, 'avatar', FILTER_SANITIZE_STRING));
        if (empty($avatar)) $avatar = '👨‍🎓';
    }

    if (empty($nombre) || empty($correo) || empty($password)) {
        sendJsonResponse(false, "Todos los campos son obligatorios.");
    }

    // Validación CSRF - Omitimos el redirect, devolvemos JSON
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($csrf_token)) {
        sendJsonResponse(false, "Token de seguridad inválido. Recarga la página.");
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse(false, "El formato del correo es inválido.");
    }

    if (strlen($password) < 6) {
        sendJsonResponse(false, "La contraseña debe tener al menos 6 caracteres.");
    }
    
    if (!in_array($rol, ['alumno', 'profesor'])) {
        sendJsonResponse(false, "Rol inválido.");
    }

    try {
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo LIMIT 1");
        $stmtCheck->execute([':correo' => $correo]);
        
        if ($stmtCheck->fetch()) {
            sendJsonResponse(false, "El correo ya está registrado. Intenta iniciar sesión.");
        }

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
            $new_user_id = $pdo->lastInsertId();
            
            // Inicializar sesión MySQL
            session_start();
            $_SESSION['usuario_id'] = $new_user_id;
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['usuario_rol'] = $rol;
            
            // Sincronizar con public.profiles legacy si existiera el intento (opcional)
            $role_en = ($rol === 'profesor') ? 'teacher' : 'student';
            try {
                $stmtProfile = $pdo->prepare("INSERT INTO public.profiles (id, full_name, role) VALUES (?, ?, ?)");
                $stmtProfile->execute([$new_user_id, $nombre, $role_en]);
            } catch (Exception $e) {}

            sendJsonResponse(true);
        } else {
            sendJsonResponse(false, "Error al crear la cuenta. Inténtalo más tarde.");
        }

    } catch (PDOException $e) {
        error_log("Fallo de PDO detectado en Registro: " . $e->getMessage());
        sendJsonResponse(false, "Error de base de datos. Por favor, intenta de nuevo.");
    }
} else {
    sendJsonResponse(false, "Método no permitido.");
}
?>
