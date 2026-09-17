<?php
$base_dir = $base_dir ?? '';
$page_title = $page_title ?? 'EduQuest Bachillerato — Aprende Jugando, Avanza Aprendiendo';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="EduQuest Bachillerato: plataforma educativa gamificada para estudiantes de bachillerato. Matemática, Física, Química, Biología y Lengua con quizzes, rankings y logros.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= $base_dir ?>css/main.css">
  <link rel="stylesheet" href="<?= $base_dir ?>css/components.css">
  <link rel="stylesheet" href="<?= $base_dir ?>css/layout.css">
  <link rel="stylesheet" href="<?= $base_dir ?>css/animations.css">
  <?php if (isset($extra_head)) echo $extra_head; ?>
</head>
<body>
<div x-data="{ openLogin: false, openRegister: false }" class="min-h-screen flex flex-col relative w-full">
