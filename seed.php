<?php
// seed.php
// Script de inicialización para insertar los usuarios de prueba en Supabase

require_once 'includes/conexion.php';

$mensaje = "";
$error = false;
$insertados = 0;

try {
    // NOTA DE ARQUITECTURA: 
    // El esquema inicial SQL no contaba con la columna de contraseñas.
    // Agregamos esto de forma automática y segura por si no existe en la base de datos.
    $pdo->exec("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS password_hash VARCHAR(255)");

    // 1. Datos de los usuarios de prueba (Mapeando el rol "estudiante" a "alumno" que es el que soporta la BD)
    $usuarios = [
        [
            'rol' => 'alumno',
            'nombre_completo' => 'Estudiante de Prueba',
            'correo' => 'alumno@prx.edu',
            'password' => '123456'
        ],
        [
            'rol' => 'profesor',
            'nombre_completo' => 'Profesor de Prueba',
            'correo' => 'profesor@prx.edu',
            'password' => '123456'
        ]
    ];

    // 2. Preparar la consulta con ON CONFLICT DO NOTHING para evitar errores si se ejecuta más de una vez
    $sql = "INSERT INTO usuarios (rol, nombre_completo, correo, password_hash) 
            VALUES (:rol, :nombre_completo, :correo, :password_hash)
            ON CONFLICT (correo) DO NOTHING";
            
    $stmt = $pdo->prepare($sql);

    // 3. Hashear e Insertar
    foreach ($usuarios as $u) {
        $hash = password_hash($u['password'], PASSWORD_DEFAULT);
        
        $stmt->execute([
            ':rol' => $u['rol'],
            ':nombre_completo' => $u['nombre_completo'],
            ':correo' => $u['correo'],
            ':password_hash' => $hash
        ]);
        
        // rowCount devuelve cuántas filas fueron afectadas (0 si el ON CONFLICT bloqueó la inserción)
        if ($stmt->rowCount() > 0) {
            $insertados++;
        }
    }

    if ($insertados > 0) {
        $mensaje = "¡Semilla ejecutada con éxito! Se han insertado $insertados usuarios nuevos.";
    } else {
        $mensaje = "Los usuarios de prueba ya existían en la base de datos. No se realizaron cambios.";
    }

} catch (PDOException $e) {
    $error = true;
    $mensaje = "Error en la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seed - PR_X Academy</title>
    <!-- Mismas fuentes del proyecto -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #020617; /* Fondo súper oscuro (Slate 950) */
            color: #F8FAFC;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.05), transparent 60%);
        }
        .seed-card {
            background: rgba(11, 17, 32, 0.95);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9), 0 0 30px rgba(59, 130, 246, 0.15);
            backdrop-filter: blur(16px);
        }
        .seed-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }
        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            margin-bottom: 16px;
            color: #FFFFFF;
        }
        p {
            color: #94A3B8;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .credentials {
            background: #0f172a; /* Slate 900 */
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            margin-bottom: 32px;
        }
        .credentials strong {
            display: block;
            color: #E2E8F0;
            margin-top: 12px;
        }
        .credentials strong:first-child {
            margin-top: 0;
        }
        .credentials code {
            display: block;
            color: #22D3EE; /* Cyan */
            font-family: monospace;
            background: #020617;
            padding: 8px 12px;
            border-radius: 8px;
            margin: 8px 0 16px 0;
            font-size: 0.95rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .btn-home {
            display: inline-block;
            background: linear-gradient(to right, #3B82F6, #2563EB);
            color: #FFF;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
            filter: brightness(1.1);
        }
    </style>
</head>
<body>

<div class="seed-card">
    <div class="seed-icon">
        <?php echo $error ? '⚠️' : '🚀'; ?>
    </div>
    <h1><?php echo $error ? 'Error de Ejecución' : 'Base de Datos Inicializada'; ?></h1>
    <p><?php echo htmlspecialchars($mensaje); ?></p>
    
    <?php if (!$error): ?>
    <div class="credentials">
        <strong>🧑‍🎓 Acceso Estudiante:</strong>
        <code>alumno@prx.edu | Pass: 123456</code>
        
        <strong>👨‍🏫 Acceso Profesor:</strong>
        <code>profesor@prx.edu | Pass: 123456</code>
    </div>
    <?php endif; ?>

    <a href="index.php" class="btn-home">Volver al Inicio</a>
</div>

</body>
</html>
