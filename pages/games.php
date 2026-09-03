<?php
$base_dir = '../';
$page_title = 'Demo Juegos — EduQuest Institutional';

$extra_head = <<<HTML
<link rel="stylesheet" href="../css/games-demo.css" />
HTML;

require_once '../includes/db_connect.php';
require_once '../includes/head.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<body class="game-demo-shell">
  <div class="ambient ambient-a"></div>
  <div class="ambient ambient-b"></div>
  <div class="ambient ambient-c"></div>

  <nav class="landing-nav" id="landing-nav">
    <a href="../index.php" class="landing-nav-logo"><div class="landing-nav-logo-icon">🎓</div><div class="landing-nav-logo-name">Edu<span>Quest</span></div></a>
    <div class="landing-nav-links"><a href="#games">Juegos</a><a href="#gamification">Gamificación</a><a href="dashboard.php">Dashboard</a></div>
    <div class="landing-nav-actions"><a href="login.php" class="btn btn-ghost" style="color:white;border-color:rgba(255,255,255,0.2)">Iniciar sesión</a><a href="register.php" class="btn btn-primary">Registrar</a></div>
  </nav>

  <main class="container game-hero">
    <section class="game-hero-card">
      <div class="hero-inner">
        <div class="hero-badge">🕹️ Demo profesional de juegos educativos</div>
        <h1 class="hero-title">Juegos interactivos, ordenados y listos para aprender</h1>
        <p class="hero-slogan">Elige tu modo de juego: sopas de letras, crucigramas y memoria visual. Todo con animación, feedback y sonidos para una experiencia más viva y profesional.</p>
        <div class="game-badges"><span>Selección de juego</span><span>Animación de fondo</span><span>Sonidos</span><span>XP y recompensas</span></div>
      </div>
    </section>

    <section id="gamification" class="game-grid two-up">
      <article class="glass-card"><h3>Gamificación activa</h3><p>Cada partida otorga XP, pistas y una sensación de progreso real.</p></article>
      <article class="glass-card"><h3>Experiencia visual</h3><p>Interfaz limpia, paleta institucional y efectos de fondo para mantener al estudiante motivado.</p></article>
    </section>

    <section id="games" class="game-shell-grid">
      <aside class="glass-card selector-card">
        <div>
          <p class="eyebrow">Elige un modo</p>
          <h2>¿Qué juego quieres jugar?</h2>
          <p class="muted-copy">Tenemos tres experiencias distintas: sopa de letras, crucigrama y memoria visual.</p>
        </div>
        <div id="game-selector" class="game-selector-list"></div>
        <div class="mini-tip">💡 Consejo: cada juego tiene sonido y feedback instantáneo para hacer la experiencia más inmersiva.</div>
      </aside>

      <article class="glass-card stage-card">
        <div class="stage-header">
          <div>
            <p class="eyebrow" id="stage-kicker">Modo activo</p>
            <h2 id="stage-title">Sopa de letras</h2>
          </div>
          <button id="reset-game" class="game-btn secondary">Reiniciar</button>
        </div>
        <p id="stage-copy" class="muted-copy">Haz clic sobre letras consecutivas para formar una palabra escondida.</p>
        <div id="active-game" class="game-board-shell"></div>
        <div id="game-feedback" class="feedback">Selecciona un juego en el panel para comenzar.</div>
      </article>
    </section>
  </main>
  <script src="../js/games-demo.js"></script>
</body>
</html>
