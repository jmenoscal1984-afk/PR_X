<?php
require_once 'includes/db_connect.php';

try {
    $password_clara = "123456";
    $password_hash = password_hash($password_clara, PASSWORD_DEFAULT);

    // Usuario 1: Estudiante
    $stmt1 = $pdo->prepare("INSERT INTO usuarios (rol, nombre_completo, correo, password, avatar) VALUES (:rol, :nombre, :correo, :pass, :avatar) ON DUPLICATE KEY UPDATE password = :pass_update");
    $stmt1->execute([
        ':rol' => 'alumno',
        ':nombre' => 'Estudiante de Prueba',
        ':correo' => 'estudiante@prueba.com',
        ':pass' => $password_hash,
        ':avatar' => '👨‍🎓',
        ':pass_update' => $password_hash
    ]);

    // Usuario 2: Profesor
    $stmt2 = $pdo->prepare("INSERT INTO usuarios (rol, nombre_completo, correo, password, avatar) VALUES (:rol, :nombre, :correo, :pass, :avatar) ON DUPLICATE KEY UPDATE password = :pass_update");
    $stmt2->execute([
        ':rol' => 'profesor',
        ':nombre' => 'Profesor de Prueba',
        ':correo' => 'profesor@prueba.com',
        ':pass' => $password_hash,
        ':avatar' => '👨‍🏫',
        ':pass_update' => $password_hash
    ]);

    echo "<h1>¡Usuarios de prueba creados exitosamente!</h1>";
    echo "<p>Ya puedes iniciar sesión en el formulario con las siguientes credenciales:</p>";
    echo "<ul>";
    echo "<li><strong>Estudiante:</strong> estudiante@prueba.com (Clave: 123456)</li>";
    echo "<li><strong>Profesor:</strong> profesor@prueba.com (Clave: 123456)</li>";
    echo "</ul>";

} catch (PDOException $e) {
    echo "<h1>Error al crear usuarios</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
