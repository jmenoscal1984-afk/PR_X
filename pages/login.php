<?php
// pages/login.php
header('Content-Type: application/json');
session_start();

// Si ya existe una sesión activa válida, avisamos que ya está autenticado
if (isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => true, 'redirect' => 'pages/dashboard.php']);
    exit;
}

require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        try {
            if (!$pdo) {
                echo json_encode(['success' => false, 'message' => 'Error 500: La conexión a la base de datos es nula.']);
                exit;
            }

            $stmt = $pdo->prepare("SELECT id, nombre_completo, rol, password FROM usuarios WHERE correo = :correo LIMIT 1");
            $stmt->execute([':correo' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['password'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                
                echo json_encode(['success' => true, 'redirect' => 'pages/dashboard.php']);
                exit; 
            } else {
                echo json_encode(['success' => false, 'message' => 'Credenciales inválidas. Verifica tu correo y contraseña.']);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error de base de datos. Intenta nuevamente.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}
?>
