<?php
// pages/dashboard.php
$current_page = 'dashboard.php';
$page_title = 'Panel de Gestión - PRX Academy';

require_once '../includes/tailwind_header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='../index.php';</script>";
    exit;
}
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
