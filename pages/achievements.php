<?php
$base_dir = '../';
$page_title = 'Logros — EduQuest Bachillerato';
$current_page = 'achievements.php';
$page_title_header = 'Logros';
$page_subtitle = 'Revisa tus insignias y recompensas desbloqueadas';

require_once '../includes/db_connect.php';
require_once '../includes/head.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$extra_head = <<<HTML
<style>
  .ach-grid { display:grid; grid-template-columns: repeat(2, 1fr); gap:20px; }
  .achievement-card { min-height: 160px; }
  @media (max-width: 1100px) { .ach-grid { grid-template-columns: 1fr; } }
</style>
HTML;
?>
<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>
  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>
    <div class="page-content">
      <div class="card" style="margin-bottom:18px">
        <div class="section-title"><div class="section-icon" style="background:var(--warning-light)">🏆</div>Logros disponibles</div>
        <p style="color:var(--text-muted);margin:0">Completa quizzes, mantén rachas y acumula XP para desbloquear nuevas recompensas.</p>
      </div>
      <div class="ach-grid" id="achievements-grid"></div>
    </div>
  </main>
</div>
<div id="toast-container" class="toast-container"></div>
<script src="../js/data.js"></script>
<script src="../js/storage.js"></script>
<script src="../js/auth.js"></script>
<script src="../js/gamification.js"></script>
<script src="../js/ui.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const user = EQ_Auth.getUser();
    if (!user) return;
    EQ_UI.init(user);

    const grid = document.getElementById('achievements-grid');
    const unlockedIds = new Set(user.achievements || []);

    EQ_DATA.achievements.forEach((ach, index) => {
      const unlocked = unlockedIds.has(ach.id);
      const card = document.createElement('article');
      card.className = `achievement-card ${unlocked ? 'unlocked' : 'locked'} will-animate`;
      card.style.animationDelay = `${index * 0.05}s`;
      card.innerHTML = `
        <div class="achievement-badge-glow"></div>
        <span class="achievement-icon">${ach.icon}</span>
        <div class="achievement-name">${ach.name}</div>
        <div class="achievement-desc">${ach.desc}</div>
        <div style="margin-top:12px;font-size:0.75rem;color:${unlocked ? 'var(--success)' : 'var(--text-muted)'};font-weight:700">${unlocked ? 'Desbloqueado' : 'Pendiente por completar'}</div>
      `;
      grid.appendChild(card);
    });

    requestAnimationFrame(() => document.querySelectorAll('.will-animate').forEach(el => el.classList.add('animated')));
  });
</script>
</body>
</html>
