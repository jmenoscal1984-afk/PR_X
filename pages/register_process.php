<?php
require_once '../includes/auth_middleware.php';
require_once '../includes/conexion.php';

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
        die("Error: Todos los campos son obligatorios.");
    }

    // Validación CSRF
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($csrf_token)) {
        die("Error: Token CSRF inválido.");
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Error: El formato del correo es inválido.");
    }

    if (strlen($password) < 6) {
        die("Error: La contraseña debe tener al menos 6 caracteres.");
    }
    
    if (!in_array($rol, ['alumno', 'profesor'])) {
        die("Error: Rol inválido.");
    }

    try {
        // Verificar si el correo ya existe
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo LIMIT 1");
        $stmtCheck->execute([':correo' => $correo]);
        
        if ($stmtCheck->fetch()) {
            die("Error: El correo ya está registrado. Intenta iniciar sesión.");
        }

        // 2. Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Insertar usuario en la base de datos (Supabase - PostgreSQL)
        $sql = "INSERT INTO usuarios (rol, nombre_completo, correo, password, avatar) 
                VALUES (:rol, :nombre, :correo, :password, :avatar) RETURNING id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':rol'      => $rol,
            ':nombre'   => $nombre,
            ':correo'   => $correo,
            ':password' => $hashed_password,
            ':avatar'   => $avatar
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && isset($user['id'])) {
            // Sincronizar con public.profiles
            $role_en = ($rol === 'profesor') ? 'teacher' : 'student';
            try {
                $stmtProfile = $pdo->prepare("INSERT INTO public.profiles (id, full_name, role) VALUES (?, ?, ?)");
                $stmtProfile->execute([$user['id'], $nombre, $role_en]);
            } catch (PDOException $e) {
                error_log("Aviso: No se pudo insertar en public.profiles - " . $e->getMessage());
            }

            // Registro exitoso, inicializar sesión
            session_regenerate_id(true); // Prevenir fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_avatar'] = $avatar;
            $_SESSION['user_role'] = $role_en;
            
            // Redirección al dashboard unificado
            header("Location: dashboard.php");
            exit;
        } else {
            die("Error al crear la cuenta. Inténtalo más tarde.");
        }

    } catch (PDOException $e) {
        // Manejo de errores temporal para depuración detallada
        die("<h3>Fallo de PDO detectado en Registro:</h3><p>" . htmlspecialchars($e->getMessage()) . "</p><p>Verifica la estructura de tu tabla 'usuarios' (¿Falta la columna 'password'?).</p>");
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>
