<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Usando la IP directa (IPv4) resuelta de aws-0-us-east-1.pooler.supabase.com para evitar el fallo de DNS
define('DB_HOST', '52.45.94.125');
define('DB_PORT', '6543');
define('DB_NAME', 'postgres');
// El usuario DEBE llevar el sufijo para que el pooler por IP sepa a qué proyecto enviarlo
define('DB_USER', 'postgres.tvmaztgcmxjqkqrlijvhz');
define('DB_PASSWORD', 'B?7SrK44+?^838');
try {
    // Se ha quitado el sslmode=require para pruebas, si Hostinger lo bloquea, asegúrate de mantenerlo así temporalmente.
    $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    echo 'Conectado con éxito mediante IP Directa (Pooler)';
} catch (PDOException $e) {
    echo 'Fallo de conexion: ' . $e->getMessage();
}
?>
