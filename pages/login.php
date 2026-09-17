<?php
ob_start();
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['usuario_id'])) {
    ob_clean();
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

    $stmt = $pdo->prepare("SELECT id, nombre_completo, rol, password FROM usuarios WHERE correo = :correo LIMIT 1");
    $stmt->execute([':correo' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        
        ob_clean();
        echo json_encode(['success' => true]);
        exit; 
    } else {
        throw new Exception('Credenciales inválidas. Verifica tu correo y contraseña.');
    }
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
?>
