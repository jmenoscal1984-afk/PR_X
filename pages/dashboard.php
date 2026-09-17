<?php
// pages/dashboard.php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$current_page = 'dashboard.php';
$page_title = 'Panel de Gestión - PRX Academy';

require_once '../includes/tailwind_header.php';

?>

<?php 
if ($userRole === 'profesor') {
    include 'teacher_view.php';
} else {
    include 'student_view.php';
}
?>

<?php
require_once '../includes/tailwind_footer.php';
?>
