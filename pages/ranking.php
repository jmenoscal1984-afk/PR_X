<?php
$base_dir = '../';
$page_title = 'Ranking — EduQuest Bachillerato';
$current_page = 'ranking.php';
$page_title_header = 'Ranking';
$page_subtitle = 'Compara tu progreso con el resto del curso';

require_once '../includes/db_connect.php';
require_once '../includes/head.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>
  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>
    <div class="page-content">
      <div class="card" style="margin-bottom:18px">
        <div class="section-title"><div class="section-icon" style="background:var(--warning-light)">🏆</div>Top 3 del ranking</div>
        <div id="podium"></div>
      </div>
      <div class="card">
        <div class="section-title"><div class="section-icon" style="background:var(--primary-light);color:var(--primary)">📊</div>Top 15 estudiantes</div>
        <div id="ranking-list" style="display:grid;gap:12px"></div>
      </div>
    </div>
  </main>
</div>
<div id="toast-container" class="toast-container"></div>
<script src="../js/data.js"></script>
<script src="../js/storage.js"></script>
<script src="../js/auth.js"></script>
<script src="../js/gamification.js"></script>
<script src="../js/ranking.js"></script>
<script src="../js/ui.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const user = EQ_Auth.getUser();
    if (!user) return;
    EQ_UI.init(user);
    EQ_Ranking.renderPodium('podium');
    EQ_Ranking.render('ranking-list', user.id);
  });
</script>
</body>
</html>
