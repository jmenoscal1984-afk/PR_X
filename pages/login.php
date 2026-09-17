<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['usuario_id'])) {
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true]);
    exit;
}

try {
    require_once '../includes/db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido.');
    }

    $email = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        throw new Exception('Por favor, completa todos los campos.');
    }

    if (!$pdo) {
        throw new Exception('Error 500: La conexión a la base de datos es nula.');
    }

    $stmt = $pdo->prepare("SELECT id, nombre_completo, rol, password, avatar FROM usuarios WHERE correo = :correo LIMIT 1");
    $stmt->execute([':correo' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        $_SESSION['usuario_avatar'] = $usuario['avatar'];
        
        ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true]);
        exit; 
    } else {
        throw new Exception('Credenciales inválidas. Verifica tu correo y contraseña.');
    }
} catch (Exception $e) {
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
    exit;
}
?>
