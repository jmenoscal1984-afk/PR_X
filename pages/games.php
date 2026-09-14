<?php
$current_page = 'games.php';
$page_title = 'Juegos Demo — EduQuest Bachillerato';
$extra_css = <<<HTML
<link rel="stylesheet" href="../css/games-demo.css" />
<style>
  /* Override styles to fit the Tailwind layout */
  .game-demo-shell { background: transparent !important; min-height: auto !important; }
  .landing-nav { display: none !important; }
  .game-hero { margin-top: 0 !important; }
  .ambient { display: none !important; /* Hide custom ambient since we use Tailwind background */ }
</style>
HTML;

require_once '../includes/tailwind_header.php';
?>

<div class="page-content">
  <div class="game-demo-shell">
    <main class="container game-hero" style="max-width:100%;">
      <section class="game-hero-card" style="background:var(--bg-panel); border:2px solid var(--border-color); backdrop-filter:blur(16px);">
        <div class="hero-inner">
          <div class="hero-badge" style="background:var(--accent); color:white;">🕹️ Demo profesional de juegos educativos</div>
          <h1 class="hero-title" style="color:var(--text-primary);">Juegos interactivos, ordenados y listos para aprender</h1>
          <p class="hero-slogan" style="color:var(--text-secondary);">Elige tu modo de juego: sopas de letras, crucigramas y memoria visual. Todo con animación, feedback y sonidos para una experiencia más viva y profesional.</p>
          <div class="game-badges">
            <span style="background:rgba(255,255,255,0.1); border:1px solid var(--border-color);">Selección de juego</span>
            <span style="background:rgba(255,255,255,0.1); border:1px solid var(--border-color);">Animación de fondo</span>
            <span style="background:rgba(255,255,255,0.1); border:1px solid var(--border-color);">Sonidos</span>
            <span style="background:rgba(255,255,255,0.1); border:1px solid var(--border-color);">XP y recompensas</span>
          </div>
        </div>
      </section>

      <section id="gamification" class="game-grid two-up" style="margin-top:2rem;">
        <article class="glass-card" style="background:var(--bg-panel); border:2px solid var(--border-color);">
          <h3 style="color:var(--text-primary);">Gamificación activa</h3>
          <p style="color:var(--text-secondary);">Cada partida otorga XP, pistas y una sensación de progreso real.</p>
        </article>
        <article class="glass-card" style="background:var(--bg-panel); border:2px solid var(--border-color);">
          <h3 style="color:var(--text-primary);">Experiencia visual</h3>
          <p style="color:var(--text-secondary);">Interfaz limpia, paleta institucional y efectos de fondo para mantener al estudiante motivado.</p>
        </article>
      </section>

      <section id="games" class="game-shell-grid" style="margin-top:2rem;">
        <aside class="glass-card selector-card" style="background:var(--bg-panel); border:2px solid var(--border-color);">
          <div>
            <p class="eyebrow" style="color:var(--accent);">Elige un modo</p>
            <h2 style="color:var(--text-primary);">¿Qué juego quieres jugar?</h2>
            <p class="muted-copy" style="color:var(--text-secondary);">Tenemos tres experiencias distintas: sopa de letras, crucigrama y memoria visual.</p>
          </div>
          <div id="game-selector" class="game-selector-list"></div>
          <div class="mini-tip" style="background:rgba(255,255,255,0.05); color:var(--text-secondary);">💡 Consejo: cada juego tiene sonido y feedback instantáneo para hacer la experiencia más inmersiva.</div>
        </aside>

        <article class="glass-card stage-card" style="background:var(--bg-panel); border:2px solid var(--border-color);">
          <div class="stage-header">
            <div>
              <p class="eyebrow" id="stage-kicker" style="color:var(--accent);">Modo activo</p>
              <h2 id="stage-title" style="color:var(--text-primary);">Sopa de letras</h2>
            </div>
            <button id="reset-game" class="game-btn secondary" style="background:transparent; border:2px solid var(--border-color); color:var(--text-primary);">Reiniciar</button>
          </div>
          <p id="stage-copy" class="muted-copy" style="color:var(--text-secondary);">Haz clic sobre letras consecutivas para formar una palabra escondida.</p>
          <div id="active-game" class="game-board-shell"></div>
          <div id="game-feedback" class="feedback" style="background:rgba(255,255,255,0.05); color:var(--text-primary);">Selecciona un juego en el panel para comenzar.</div>
        </article>
      </section>
    </main>
  </div>
</div>

<script src="../js/games-demo.js"></script>

<?php require_once '../includes/tailwind_footer.php'; ?>
