<?php
$base_dir = '../';
$page_title = 'Quiz — EduQuest Bachillerato';

$extra_head = <<<HTML
<style>
  body { background: var(--body-bg); }
  .quiz-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 24px 16px 48px;
  }
  .quiz-topbar {
    width: 100%; max-width: 760px;
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap; gap: 12px;
  }
  .quiz-stats-row {
    display: flex; gap: 12px; align-items: center; flex-wrap: wrap;
  }
  .quiz-stat-chip {
    display: flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: var(--radius-full);
    font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700;
  }
</style>
HTML;

require_once '../includes/db_connect.php';
require_once '../includes/head.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="quiz-page">
  <!-- Quiz Topbar -->
  <div class="quiz-topbar">
    <a href="subjects.php" class="btn btn-ghost btn-sm">
      <i class="fas fa-arrow-left"></i> Salir
    </a>
    <div style="display:flex;align-items:center;gap:8px">
      <div style="font-family:var(--font-heading);font-weight:700;font-size:0.9rem;color:var(--text-muted)" id="quiz-subject-name">Materia</div>
    </div>
    <div class="quiz-stats-row">
      <div class="quiz-stat-chip" style="background:var(--secondary-light);color:var(--secondary-dark)">
        ✅ <span id="quiz-correct-count">0</span>
      </div>
      <div class="quiz-stat-chip" style="background:var(--danger-light);color:#991B1B">
        ❌ <span id="quiz-wrong-count">0</span>
      </div>
      <div class="quiz-stat-chip" style="background:var(--warning-light);color:#92400E">
        ⭐ <span id="quiz-score-display">0 pts</span>
      </div>
    </div>
  </div>

  <!-- Main Quiz Area -->
  <div class="quiz-container" style="width:100%" id="quiz-main-area">
    <div class="quiz-header">
      <div>
        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:4px" id="quiz-question-num">Pregunta 1 de 10</div>
      </div>
      <div class="quiz-timer" id="quiz-timer">
        <svg viewBox="0 0 80 80">
          <circle cx="40" cy="40" r="35" id="quiz-timer-circle"/>
        </svg>
        <span id="quiz-timer-val">20</span>
      </div>
    </div>

    <div class="quiz-progress-bar">
      <div class="quiz-progress-fill" id="quiz-progress-fill" style="width:0%"></div>
    </div>

    <div class="quiz-question-card animate-fade-in">
      <p class="quiz-question-text" id="quiz-question-text">Cargando pregunta...</p>
      <div id="quiz-explanation" style="display:none"></div>
    </div>

    <div id="quiz-options-area"></div>
  </div>
</div>
<div id="toast-container" class="toast-container"></div>
<canvas id="confetti-canvas"></canvas>
<script src="../js/data.js"></script>
<script src="../js/storage.js"></script>
<script src="../js/auth.js"></script>
<script src="../js/gamification.js"></script>
<script src="../js/ui.js"></script>
<script src="../js/quiz.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    EQ_UI.loadTheme();
    const user = EQ_Auth.getUser();
    if (!user) { window.location.href = 'login.php'; return; }
    EQ_Quiz.init();
  });
</script>
</body>
</html>
