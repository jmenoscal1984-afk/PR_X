<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    require_once '../includes/db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

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
        ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Correo inválido o datos incompletos.']);
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'El formato del correo es inválido (ej. falta .com).']);
        exit;
    }


    if (strlen($password) < 6) {
        throw new Exception("La contraseña debe tener al menos 6 caracteres.");
    }
    
    if (!in_array($rol, ['alumno', 'profesor'])) {
        throw new Exception("Rol inválido.");
    }

    $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo LIMIT 1");
    $stmtCheck->execute([':correo' => $correo]);
    
    if ($stmtCheck->fetch()) {
        ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Este correo ya está registrado.']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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
        
        $_SESSION['usuario_id'] = $new_user_id;
        $_SESSION['usuario_nombre'] = $nombre;
        $_SESSION['usuario_rol'] = $rol;
        
        $role_en = ($rol === 'profesor') ? 'teacher' : 'student';
        try {
            $stmtProfile = $pdo->prepare("INSERT INTO public.profiles (id, full_name, role) VALUES (?, ?, ?)");
            $stmtProfile->execute([$new_user_id, $nombre, $role_en]);
        } catch (Exception $e) {}

        ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true]);
        exit;
    } else {
        throw new Exception("Error al crear la cuenta. Inténtalo más tarde.");
    }

} catch (Exception $e) {
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
    exit;
}
?>
