<?php
// setup_db.php
// IMPORTANTE: Este script debe ser borrado o protegido una vez que las tablas hayan sido creadas.

require_once 'includes/conexion.php';

try {
    // Leemos el contenido de nuestro archivo SQL
    $sql = file_get_contents('database.sql');
    
    if ($sql === false) {
        die("Error: No se pudo leer el archivo database.sql");
    }

    // Ejecutamos las consultas en la base de datos
    $pdo->exec($sql);
    
    echo "<div style='background-color: #12141c; color: #10b981; padding: 40px; text-align: center; font-family: sans-serif; border-radius: 12px; max-width: 600px; margin: 40px auto; border: 1px solid #10b981;'>
            <h2 style='margin-bottom: 16px;'>¡Éxito!</h2>
            <p>Todas las tablas de Supabase fueron creadas correctamente desde XAMPP.</p>
            <p style='color: #ef4444; margin-top: 20px; font-weight: bold;'>⚠️ Por favor, elimina este archivo (setup_db.php) por razones de seguridad.</p>
          </div>";
          
} catch (PDOException $e) {
    echo "<div style='background-color: #12141c; color: #ef4444; padding: 40px; text-align: center; font-family: sans-serif; border-radius: 12px; max-width: 600px; margin: 40px auto; border: 1px solid #ef4444;'>
            <h2 style='margin-bottom: 16px;'>Error al crear las tablas</h2>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
          </div>";
}
?>
