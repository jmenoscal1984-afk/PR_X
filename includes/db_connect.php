<?php
// Configuración para XAMPP Local (MySQL)
$db_host = 'localhost';
$db_name = 'eduquest_db';
$db_user = 'root';
$db_pass = '';
$db_type = 'mysql'; // Cambiar a 'pgsql' para Supabase

/* 
// Ejemplo de Configuración para Supabase (PostgreSQL)
$db_host = 'db.xxxxxxxxx.supabase.co';
$db_name = 'postgres';
$db_user = 'postgres';
$db_pass = 'tu_password_de_supabase';
$db_type = 'pgsql'; 
*/

$pdo = null;
try {
    if ($db_type === 'pgsql') {
        // Conexión para Supabase / PostgreSQL
        $dsn = "pgsql:host=$db_host;port=5432;dbname=$db_name;sslmode=require";
        $pdo = new PDO($dsn, $db_user, $db_pass);
    } else {
        // Conexión por defecto para XAMPP / MySQL
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
        $pdo = new PDO($dsn, $db_user, $db_pass);
    }
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Si la DB no existe aún, evitamos que rompa visualmente la página
    error_log("Error de conexión PDO: " . $e->getMessage());
}
?>
