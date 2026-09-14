<?php
$current_page = $current_page ?? 'dashboard.php';
$base_dir = $base_dir ?? '../';
?>
<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <a href="<?= $base_dir ?>pages/dashboard.php" class="sidebar-logo">
    <div class="sidebar-logo-icon">🎓</div>
    <div class="sidebar-logo-text">
      <span class="sidebar-logo-name">EduQuest</span>
      <span class="sidebar-logo-sub">Bachillerato</span>
    </div>
  </a>

  <a href="<?= $base_dir ?>pages/profile.php" class="sidebar-user">
    <div class="sidebar-user-avatar" id="sb-user-avatar">🧑‍💻</div>
    <div>
      <div class="sidebar-user-name" id="sb-user-name">Cargando...</div>
      <div class="sidebar-user-level" id="sb-user-level">Nivel 1</div>
    </div>
    <div class="sidebar-user-xp" id="sb-user-xp">0 XP</div>
  </a>

  <nav class="sidebar-nav">
    <div class="sidebar-section-label">Principal</div>
    <a href="<?= $base_dir ?>pages/dashboard.php" class="nav-item <?= $current_page == 'dashboard.php' ? 'active' : '' ?>" data-page="dashboard.php">
      <span class="nav-item-icon"><i class="fas fa-home"></i></span> Dashboard
    </a>
    <a href="<?= $base_dir ?>pages/profile.php" class="nav-item <?= $current_page == 'profile.php' ? 'active' : '' ?>" data-page="profile.php">
      <span class="nav-item-icon"><i class="fas fa-user"></i></span> Mi Perfil
    </a>

    <div class="sidebar-section-label">Aprender</div>
    <a href="<?= $base_dir ?>pages/subjects.php" class="nav-item <?= $current_page == 'subjects.php' ? 'active' : '' ?>" data-page="subjects.php">
      <span class="nav-item-icon"><i class="fas fa-book"></i></span> Materias
    </a>
    <a href="<?= $base_dir ?>pages/games.php" class="nav-item <?= $current_page == 'games.php' ? 'active' : '' ?>" data-page="games.php">
      <span class="nav-item-icon"><i class="fas fa-gamepad"></i></span> Juegos Demo
    </a>
    <a href="<?= $base_dir ?>pages/achievements.php" class="nav-item <?= $current_page == 'achievements.php' ? 'active' : '' ?>" data-page="achievements.php">
      <span class="nav-item-icon"><i class="fas fa-trophy"></i></span> Logros
    </a>
    <a href="<?= $base_dir ?>pages/ranking.php" class="nav-item <?= $current_page == 'ranking.php' ? 'active' : '' ?>" data-page="ranking.php">
      <span class="nav-item-icon"><i class="fas fa-medal"></i></span> Ranking
    </a>

    <div class="sidebar-section-label">Sistema</div>
    <a href="<?= $base_dir ?>pages/settings.php" class="nav-item <?= $current_page == 'settings.php' ? 'active' : '' ?>" data-page="settings.php">
      <span class="nav-item-icon"><i class="fas fa-cog"></i></span> Configuración
    </a>
    <a href="<?= $base_dir ?>pages/logout.php" class="nav-item" style="color:var(--danger)">
      <span class="nav-item-icon"><i class="fas fa-sign-out-alt"></i></span> Cerrar Sesión
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-xp-section">
      <div class="sidebar-xp-label">
        <span class="sidebar-xp-text" id="sb-xp-text">Progreso al siguiente nivel</span>
        <span class="sidebar-xp-val" id="sb-xp-val">0 XP</span>
      </div>
      <div class="sidebar-xp-track">
        <div class="sidebar-xp-fill" id="sb-xp-fill" style="width:0%"></div>
      </div>
    </div>
  </div>
</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
