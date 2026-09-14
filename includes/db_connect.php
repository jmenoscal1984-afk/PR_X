<?php
// Configuración de constantes para la base de datos (fallback a getenv)
define('DB_HOST', getenv('DB_HOST') ?: 'db.tvmaztgcmxjkqrlijvhz.supabase.co');
define('DB_PORT', getenv('DB_PORT') ?: '5432');
define('DB_NAME', getenv('DB_NAME') ?: 'postgres');
define('DB_USER', getenv('DB_USER') ?: 'postgres');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'B?7SrK44+?^?838');

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
    
    // UI Amigable (Espacio Profundo)
    echo '<div style="background-color: #050510; color: #94A3B8; padding: 60px 20px; text-align: center; font-family: \'Inter\', sans-serif; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.1), transparent 50%); margin: 0;">';
    echo '  <div style="background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); padding: 40px; border-radius: 24px; max-width: 500px; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.1);">';
    echo '    <div style="font-size: 3rem; margin-bottom: 20px;">📡</div>';
    echo '    <h2 style="color: #F8FAFC; font-size: 1.8rem; margin-bottom: 12px; font-weight: 700;">Señal Perdida</h2>';
    echo '    <p style="margin-bottom: 24px; line-height: 1.6;">Nuestros satélites no han podido establecer conexión con la base de datos principal. Estamos trabajando para restaurar la señal.</p>';
    echo '    <button onclick="window.location.reload()" style="background: #3B82F6; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: bold; cursor: pointer;">Reintentar Conexión</button>';
    echo '  </div>';
    echo '</div>';
    exit;
}
?>
