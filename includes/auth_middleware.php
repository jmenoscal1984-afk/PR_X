<?php
/**
 * Middleware Centralizado de Autenticación y Seguridad
 * Este archivo debe ser incluido (require_once) al principio de todas 
 * las vistas protegidas y procesadores de formularios.
 */

// 1. Iniciar sesión de forma segura si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    // Configuraciones de seguridad para la sesión (debe llamarse antes de session_start)
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_httponly', 1);
    // ini_set('session.cookie_secure', 1); // Descomentar en producción con HTTPS
    
    session_start();
}

// 2. Control de Inactividad (Timeout)
$timeout_duration = 1800; // 30 minutos (30 * 60 = 1800 segundos)

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // La sesión ha expirado
    session_unset();
    session_destroy();
    header("Location: ../index.php?error=" . urlencode("Tu sesión ha expirado por inactividad."));
    exit;
}
$_SESSION['last_activity'] = time(); // Actualizar tiempo de última actividad

// 3. Verificación de Autenticación (Rutas Protegidas)
// Asumimos que si $require_auth está definida y es false, la página es pública.
// Por defecto, si este archivo se incluye, se asume que la página requiere autenticación.
$require_auth = isset($require_auth) ? $require_auth : true;

if ($require_auth) {
    if (!isset($_SESSION['usuario_id'])) {
        // Usuario no autenticado, redirigir al login
        header("Location: ../index.php?error=" . urlencode("Debes iniciar sesión para acceder."));
        exit;
    }
}

// 4. Verificación de Rol de Profesor
// Si $require_teacher está definido como true, verificamos el rol
$require_teacher = isset($require_teacher) ? $require_teacher : false;

if ($require_teacher) {
    if (!isset($_SESSION['usuario_rol']) || ($_SESSION['usuario_rol'] !== 'teacher' && $_SESSION['usuario_rol'] !== 'profesor')) {
        header("Location: student_view.php?error=" . urlencode("Acceso denegado. Esta sección es solo para profesores."));
        exit;
    }
}

// 5. Sistema de CSRF (Cross-Site Request Forgery)
/**
 * Genera un token CSRF y lo guarda en la sesión.
 * @return string El token generado.
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida un token CSRF recibido (generalmente por POST).
 * @param string $token El token a validar.
 * @return bool True si es válido, False si no.
 */
function validate_csrf_token($token) {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}

// Función auxiliar para escapar strings y prevenir XSS
function escape($html) {
    return htmlspecialchars($html ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
?>
