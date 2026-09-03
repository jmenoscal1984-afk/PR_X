<?php
$base_dir = '../';
$page_title = 'Materias — EduQuest Bachillerato';
$current_page = 'subjects.php';
$page_title_header = 'Materias';
$page_subtitle = 'Elige una materia y comienza a jugar';

require_once '../includes/db_connect.php';

$extra_head = <<<HTML
<style>
  .subjects-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
  .subject-full-card {
    background: var(--card-bg);
    border-radius: var(--radius-2xl);
    border: 1px solid var(--border-color);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
  }
  .subject-full-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-xl); }
  .subject-full-banner {
    height: 180px;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
  }
  .subject-full-banner::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0; height: 60px;
    background: linear-gradient(transparent, rgba(0,0,0,0.2));
  }
  .subject-emoji { font-size: 5rem; position: relative; z-index: 1; filter: drop-shadow(0 8px 20px rgba(0,0,0,0.3)); }
  .subject-full-body { padding: 28px; }
  .subject-full-title { font-family: var(--font-heading); font-size: 1.3rem; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; }
  .subject-full-desc  { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px; }
  .subject-modes { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 20px; }
  .mode-btn {
    padding: 10px 12px;
    border-radius: var(--radius-md);
    border: 2px solid var(--border-color);
    background: var(--gray-50);
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    display: flex; align-items: center; justify-content: center; gap: 6px;
  }
  .mode-btn:hover { border-color: var(--primary); background: var(--primary-light); color: var(--primary); }
  @media (max-width: 1100px) { .subjects-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 700px)  { .subjects-grid { grid-template-columns: 1fr; } }
</style>
HTML;

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
      <div class="page-header">
        <h1>📚 Materias Disponibles</h1>
        <p>Selecciona una materia y el tipo de juego para comenzar. Gana XP en cada quiz completado.</p>
      </div>
      <div class="subjects-grid" id="subjects-grid"></div>
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

  const modes = [
    { key: 'quiz',  label: '🎯 Quiz',        desc: 'Opción múltiple' },
    { key: 'tf',    label: '✅ V / F',       desc: 'Verdadero o Falso' },
    { key: 'fill',  label: '✏️ Completar',  desc: 'Llenar el espacio' },
    { key: 'match', label: '🔗 Asociar',     desc: 'Relacionar conceptos' },
  ];

  const grid = document.getElementById('subjects-grid');

  Object.values(EQ_DATA.subjects).forEach((sub, i) => {
    const progress = Math.min(100, user.stats.progressBySubject[sub.id] || 0);
    const xp = user.stats.xpBySubject[sub.id] || 0;
    const quizzes = user.stats.quizzesBySubject[sub.id] || 0;

    const card = document.createElement('div');
    card.className = 'subject-full-card will-animate';
    card.style.animationDelay = (i * 0.1) + 's';
    card.innerHTML = `
      <div class="subject-full-banner" style="background:${sub.gradient}">
        <span class="subject-emoji">${sub.icon}</span>
      </div>
      <div class="subject-full-body">
        <div class="subject-full-title">${sub.name}</div>
        <p class="subject-full-desc">${sub.desc}</p>

        <div style="margin-bottom:16px">
          <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:var(--text-muted);margin-bottom:6px">
            <span>Progreso</span>
            <span>${progress.toFixed(0)}% · ${xp} XP · ${quizzes} quizzes</span>
          </div>
          <div class="progress-track">
            <div class="progress-fill" style="width:${progress}%"></div>
          </div>
        </div>

        <div class="subject-modes">
          ${modes.map(m => `
            <a href="quiz.php?subject=${sub.id}&mode=${m.key}" class="mode-btn">
              ${m.label}
            </a>
          `).join('')}
        </div>

        <a href="quiz.php?subject=${sub.id}&mode=quiz" class="btn btn-primary btn-full">
          <i class="fas fa-play"></i> Comenzar Ahora
        </a>
      </div>
    `;
    grid.appendChild(card);
  });

  requestAnimationFrame(() => {
    document.querySelectorAll('.will-animate').forEach(el => el.classList.add('animated'));
  });
});
</script>
</body>
</html>
