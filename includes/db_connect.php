<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
define('DB_HOST', 'db.tvmaztgcmxjqkqrlijvhz.supabase.co');
define('DB_PORT', '5432');
define('DB_NAME', 'postgres');
define('DB_USER', 'postgres');
define('DB_PASSWORD', 'B?7SrK44+?^838');
try {
    $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    echo 'Conectado sin SSL con éxito';
} catch (PDOException $e) {
    echo 'Fallo de conexion: ' . $e->getMessage();
}
?>
