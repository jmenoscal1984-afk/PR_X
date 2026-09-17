<?php
// pages/login.php
session_start();

// Si ya existe una sesión activa válida, redirige a dashboard.php
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
}

require_once '../includes/db_connect.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        try {
            if (!$pdo) {
                die("Error 500: La conexión a la base de datos (PDO) es nula.");
            }

            $stmt = $pdo->prepare("SELECT id, nombre_completo, rol, password FROM usuarios WHERE correo = :correo LIMIT 1");
            $stmt->execute([':correo' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['password'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                header("Location: dashboard.php");
                exit; 
            } else {
                $error_message = "Credenciales inválidas. Verifica tu correo y contraseña.";
            }
        } catch (PDOException $e) {
            $error_message = "Error de base de datos. Intenta nuevamente.";
        }
    } else {
        $error_message = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión — EduQuest</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
  <div class="bg-gray-800 p-8 rounded-xl shadow-lg w-full max-w-md">
    <div class="text-center mb-6">
      <h1 class="text-2xl font-bold">Iniciar Sesión</h1>
      <p class="text-gray-400 text-sm mt-2">Bienvenido de nuevo a la plataforma</p>
    </div>

    <?php if (!empty($error_message)): ?>
      <div class="bg-red-500/10 border border-red-500 text-red-400 p-3 rounded mb-4 text-sm text-center">
        <?= htmlspecialchars($error_message) ?>
      </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="space-y-4">
      <div>
        <label for="correo" class="block text-sm font-medium text-gray-400 mb-1">Correo Electrónico</label>
        <input type="email" id="correo" name="correo" required 
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-blue-500 text-white" 
               placeholder="tu@correo.com">
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-400 mb-1">Contraseña</label>
        <input type="password" id="password" name="password" required 
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-blue-500 text-white" 
               placeholder="Tu contraseña">
      </div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
        Entrar
      </button>
    </form>
    
    <div class="mt-6 text-center text-sm text-gray-400">
      ¿No tienes cuenta? <a href="register.php" class="text-blue-400 hover:underline">Regístrate gratis</a>
    </div>
  </div>
</body>
</html>
