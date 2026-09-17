<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/auth_middleware.php';
require_once '../includes/conexion.php';

function redirectWithError($message) {
    header("Location: login.php?error=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim(filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';

    // Validaciones básicas
    if (empty($correo) || empty($password)) {
        redirectWithError("Correo y contraseña son obligatorios.");
    }

    // Validación CSRF
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($csrf_token)) {
        redirectWithError("Token CSRF inválido o expirado. Por favor, recarga la página e intenta de nuevo.");
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
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre_completo'];
            $_SESSION['user_full_name'] = $user['nombre_completo']; // Para compatibilidad
            $_SESSION['user_avatar'] = $user['avatar'];
            
            // Consultar la tabla public.profiles para obtener el rol exacto (student o teacher)
            try {
                $stmtProfile = $pdo->prepare("SELECT role FROM public.profiles WHERE id = :id LIMIT 1");
                $stmtProfile->execute([':id' => $user['id']]);
                $profile = $stmtProfile->fetch(PDO::FETCH_ASSOC);
                
                $role = $profile ? $profile['role'] : (($user['rol'] === 'profesor') ? 'teacher' : 'student');
                $_SESSION['user_role'] = $role;
            } catch (PDOException $e) {
                // Fallback y log de error
                error_log("Error PDO consultando public.profiles en login: " . $e->getMessage());
                $role = ($user['rol'] === 'profesor') ? 'teacher' : 'student';
                $_SESSION['user_role'] = $role;
            }
            
            // Redirección estricta limpia a los paneles
            if ($role === 'teacher') {
                header("Location: teacher_view.php");
            } else {
                header("Location: student_view.php");
            }
            exit();
        } else {
            // Credenciales incorrectas
            redirectWithError("Credenciales inválidas. Verifica tu correo y contraseña.");
        }

    } catch (PDOException $e) {
        // Manejo de errores seguro
        error_log("Error de base de datos en login: " . $e->getMessage());
        redirectWithError("Ocurrió un error en el servidor. Inténtalo más tarde.");
    }
} else {
    // Si no es POST, redirigir al login
    header("Location: login.php");
    exit();
}
?>
