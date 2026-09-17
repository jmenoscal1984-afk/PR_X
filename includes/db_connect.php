<?php
// Función simple para cargar .env de forma segura
$env_path = __DIR__ . '/../.env';
if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Configuración de constantes para la base de datos (fallback a getenv)
define('DB_HOST', getenv('DB_HOST') ?: 'db.tvmaztgcmxjqkqrlijvhz.supabase.co');
define('DB_PORT', getenv('DB_PORT') ?: '5432');
define('DB_NAME', getenv('DB_NAME') ?: 'postgres');
define('DB_USER', getenv('DB_USER') ?: 'postgres');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'B?7SrK44+?^838');

$pdo = null;
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";sslmode=require";
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // Loguear internamente en app_error.log (sin exponer al usuario)
    error_log("[" . date('Y-m-d H:i:s') . "] Error crítico de BD: " . $e->getMessage() . "\n", 3, __DIR__ . '/../app_error.log');
    
    // Detectar si estamos en un entorno local para depuración
    $is_local = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false;

    if ($is_local) {
        $errorCode = $e->getCode();
        $errorMessage = htmlspecialchars($e->getMessage());
        
        echo '<div style="background-color: #1e1b4b; color: #f8fafc; padding: 40px; font-family: sans-serif; border: 2px solid #ef4444; border-radius: 12px; max-width: 800px; margin: 40px auto;">';
        echo '<h2 style="color: #ef4444;">⚠️ Error de Conexión en Desarrollo Local</h2>';
        echo '<p><strong>Código SQLSTATE:</strong> ' . $errorCode . '</p>';
        echo '<p><strong>Excepción:</strong> ' . $errorMessage . '</p>';
        
        if ($errorCode === '08006' || strpos($errorMessage, 'Unknown host') !== false || strpos($errorMessage, 'could not translate host name') !== false) {
            echo '<div style="background: rgba(255, 255, 255, 0.1); padding: 15px; border-radius: 8px; margin-top: 20px;">';
            echo '<h3 style="margin-top: 0; color: #fbbf24;">🔍 Diagnóstico de Host / DNS (Supabase):</h3>';
            echo '<ul style="line-height: 1.6;">';
            echo '<li><strong>Proyecto Pausado:</strong> Si usas la capa gratuita de Supabase y llevas días sin usarlo, el proyecto se pausa automáticamente y el host deja de existir en el DNS. Entra a tu <a href="https://app.supabase.com" style="color: #60a5fa;" target="_blank">Dashboard de Supabase</a> y presiona "Restore" o "Unpause".</li>';
            echo '<li><strong>Falta el archivo .env:</strong> No tienes un archivo <code>.env</code> creado, por lo que el sistema está usando la cadena de conexión de respaldo, la cual podría tener un error tipográfico o estar desactualizada. Copia <code>.env.example</code> a <code>.env</code> y configura tus credenciales reales.</li>';
            echo '<li><strong>Fallo de IPv6:</strong> Tu proveedor de internet podría no estar resolviendo correctamente IPv6. Intenta usar el pooler de conexión (Session mode) de IPv4 que Supabase provee en sus ajustes de base de datos.</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<p>Verifica que tu archivo <code>.env</code> tenga las credenciales correctas y que la base de datos esté activa.</p>';
        }
        
        echo '</div>';
    } else {
        // UI Amigable (Espacio Profundo) para Producción
        echo '<div style="background-color: #050510; color: #94A3B8; padding: 60px 20px; text-align: center; font-family: \'Inter\', sans-serif; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.1), transparent 50%); margin: 0;">';
        echo '  <div style="background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); padding: 40px; border-radius: 24px; max-width: 500px; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.1);">';
        echo '    <div style="font-size: 3rem; margin-bottom: 20px;">📡</div>';
        echo '    <h2 style="color: #F8FAFC; font-size: 1.8rem; margin-bottom: 12px; font-weight: 700;">Señal Perdida</h2>';
        echo '    <p style="margin-bottom: 24px; line-height: 1.6;">Nuestros satélites no han podido establecer conexión con la base de datos principal. Estamos trabajando para restaurar la señal.</p>';
        echo '    <button onclick="window.location.reload()" style="background: #3B82F6; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: bold; cursor: pointer;">Reintentar Conexión</button>';
        echo '  </div>';
        echo '</div>';
    }
    exit;
}
?>
