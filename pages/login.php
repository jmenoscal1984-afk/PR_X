<?php
// pages/login.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        try {
            // Verificar si $pdo existe y es válido
            if (!$pdo) {
                die("Error 500: La conexión a la base de datos (PDO) es nula.");
            }

            // Consultar la tabla "usuarios" con la columna 'password' correcta de MySQL
            $stmt = $pdo->prepare("SELECT id, nombre_completo, rol, password FROM usuarios WHERE correo = :correo LIMIT 1");
            $stmt->execute([':correo' => $email]);
            $usuario = $stmt->fetch();

            // Verificamos si el usuario existe y si la contraseña es correcta
            if ($usuario && password_verify($password, $usuario['password'])) {
                
                // Regenerar ID de sesión para mayor seguridad
                session_regenerate_id(true);

                // Guardamos la información en la sesión
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['nombre_completo'];
                $_SESSION['user_role'] = $usuario['rol'];

                // Redirigir al dashboard unificado
                header("Location: dashboard.php");
                exit; 
            } else {
                // Credenciales inválidas: redirigir a index.php con error
                header("Location: ../index.php?error=invalid_credentials");
                exit;
            }
        } catch (PDOException $e) {
            // Error de conexión u otro problema
            header("Location: ../index.php?error=db_error");
            exit;
        }
    } else {
        // Faltan datos
        header("Location: ../index.php?error=missing_data");
        exit;
    }
} else {
    // Si se accede sin POST, redirigir al index
    header("Location: ../index.php");
    exit;
}
