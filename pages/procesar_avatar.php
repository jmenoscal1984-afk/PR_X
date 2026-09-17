<?php
session_start();
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Evitar que PHP escupa HTML de errores

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    ob_end_clean();
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

include '../includes/db_connect.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido.');
    }

    if (!isset($_FILES['avatar_file']) || $_FILES['avatar_file']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception('No se envió ninguna imagen.');
    }

    $file = $_FILES['avatar_file'];
    
    // 1. Validar tamaño (máximo 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception('La imagen no debe superar los 2MB.');
    }

    // 2. Validar tipo de archivo
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $fileMimeType = mime_content_type($file['tmp_name']);
    
    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        throw new Exception('Formato no válido. Solo JPG, PNG o WEBP.');
    }

    // 3. Generar nombre único y carpeta destino
    $uploadDir = '../uploads/avatares/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid('avatar_', true) . '.' . $extension;
    $destination = $uploadDir . $newFileName;

    // 4. Mover la imagen
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Error al guardar la imagen en el servidor.');
    }

    // 5. Actualizar la base de datos
    // La ruta relativa que guardaremos en la BD (accesible desde las vistas)
    $dbPath = '../uploads/avatares/' . $newFileName;
    
    $stmt = $pdo->prepare("UPDATE usuarios SET avatar = ? WHERE id = ?");
    $stmt->execute([$dbPath, $_SESSION['usuario_id']]);

    // Actualizar la sesión
    $_SESSION['usuario_avatar'] = $dbPath;

    ob_end_clean();
    echo json_encode(['success' => true, 'ruta' => $dbPath, 'message' => 'Avatar actualizado correctamente.']);
    
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
