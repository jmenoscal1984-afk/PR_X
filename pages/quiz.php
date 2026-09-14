<?php
$base_dir = '../';
$page_title = 'Misión Estelar — EduQuest Bachillerato';

require_once '../includes/db_connect.php';
require_once '../includes/tailwind_header.php'; // Usa el layout estelar
?>
<style>
  /* Ocultar barra lateral y header para inmersión total (Fullscreen mode) */
  aside { display: none !important; }
  header { display: none !important; }
  main { width: 100% !important; margin-left: 0 !important; padding: 0 !important; }
  
  .quiz-fullscreen {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    padding: 24px;
    position: relative;
    z-index: 10;
    max-width: 900px;
    margin: 0 auto;
  }
  
  /* Barra de Progreso Estelar */
  .stellar-progress-bg {
    width: 100%;
    height: 16px;
    background: rgba(255,255,255,0.1);
    border-radius: 99px;
    overflow: hidden;
    position: relative;
    border: 2px solid rgba(255,255,255,0.2);
  }
  .stellar-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #a855f7, #facc15);
    background-size: 200% 200%;
    animation: gradientMove 3s ease infinite;
    transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 99px;
    box-shadow: 0 0 15px rgba(250,204,21,0.6);
  }
  @keyframes gradientMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
  
  /* Opciones de Respuesta Gigantes (Kahoot style) */
  .quiz-options-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    width: 100%;
    margin-top: 32px;
  }
  @media (max-width: 640px) {
    .quiz-options-grid { grid-template-columns: 1fr; }
  }
  .quiz-option-big {
    min-height: 120px;
    background: var(--bg-panel);
    border: 3px solid var(--border-color);
    border-radius: 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 8px 0 rgba(0,0,0,0.2);
  }
  .quiz-option-big:hover:not(.disabled) {
    transform: translateY(-4px);
    border-color: var(--accent);
    box-shadow: 0 12px 0 rgba(0,0,0,0.3), 0 0 20px rgba(var(--accent), 0.3);
  }
  .quiz-option-big:active:not(.disabled) {
    transform: translateY(4px);
    box-shadow: 0 4px 0 rgba(0,0,0,0.2);
  }
  .quiz-option-big.correct {
    background: rgba(34,197,94,0.2) !important;
    border-color: #22c55e !important;
    color: #4ade80 !important;
    box-shadow: 0 0 30px rgba(34,197,94,0.4) !important;
  }
  .quiz-option-big.wrong {
    background: rgba(239,68,68,0.2) !important;
    border-color: #ef4444 !important;
    color: #f87171 !important;
    opacity: 0.7;
  }
  
  /* Escudos de Energía (Duolingo style) */
  .energy-shields {
    display: flex;
    gap: 8px;
    align-items: center;
  }
  .shield-icon {
    font-size: 1.5rem;
    color: #38bdf8;
    filter: drop-shadow(0 0 8px rgba(56,189,248,0.8));
    transition: all 0.3s;
  }
  .shield-icon.broken {
    color: rgba(255,255,255,0.2);
    filter: none;
    transform: scale(0.8);
  }
</style>

<div class="quiz-fullscreen animate-[fadeIn_0.5s_ease-out]">
  
  <!-- Header: Controles, Barra y Escudos -->
  <div class="flex items-center gap-6 mb-8 w-full">
    <button onclick="window.location.href='subjects.php'" class="w-12 h-12 rounded-2xl bg-theme_panel border-2 border-theme_border flex items-center justify-center text-xl text-theme_text_muted hover:text-white hover:border-theme_accent transition-all shrink-0">
      <i class="fas fa-times"></i>
    </button>
    
    <div class="flex-1">
      <div class="stellar-progress-bg">
        <div class="stellar-progress-fill" id="quiz-progress-fill" style="width:0%"></div>
      </div>
    </div>
    
    <div class="energy-shields" id="energy-shields-container">
      <i class="fas fa-shield-alt shield-icon"></i>
      <i class="fas fa-shield-alt shield-icon"></i>
      <i class="fas fa-shield-alt shield-icon"></i>
    </div>
  </div>

  <!-- Pregunta Central -->
  <div class="flex-1 flex flex-col justify-center items-center w-full">
    <div class="text-center w-full max-w-3xl">
      <p id="quiz-question-num" class="text-theme_accent font-bold tracking-widest uppercase mb-4 text-sm">Pregunta 1</p>
      
      <div class="flex items-center justify-center gap-4 mb-8">
        <h2 id="quiz-question-text" class="font-heading text-3xl md:text-5xl font-extrabold text-white leading-tight drop-shadow-lg">
          Cargando misión...
        </h2>
        <button id="tts-btn" class="w-14 h-14 rounded-full bg-theme_panel border-2 border-theme_border flex items-center justify-center text-2xl text-theme_accent hover:scale-110 transition-transform shadow-lg shrink-0">
          <i class="fas fa-volume-up"></i>
        </button>
      </div>

      <!-- Temporizador (Opcional visual) -->
      <div class="w-full h-2 bg-[rgba(255,255,255,0.1)] rounded-full overflow-hidden mb-8" id="timer-bar-container">
        <div id="timer-bar-fill" class="h-full bg-yellow-400 transition-all duration-1000 ease-linear w-full"></div>
      </div>

      <div id="quiz-explanation" class="hidden mb-6 p-6 rounded-2xl bg-theme_bg border-2 border-theme_border text-lg font-medium text-left shadow-xl"></div>
    </div>
    
    <!-- Opciones (inyectadas por JS) -->
    <div id="quiz-options-area" class="w-full max-w-3xl"></div>
  </div>
</div>

<div id="toast-container" class="toast-container"></div>
<script src="../js/sounds.js"></script>
<script src="../js/data.js"></script>
<script src="../js/storage.js"></script>
<script src="../js/auth.js"></script>
<script src="../js/gamification.js"></script>
<script src="../js/ui.js"></script>
<script src="../js/quiz.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const user = EQ_Auth.getUser();
    if (!user) { window.location.href = 'login.php'; return; }
    EQ_Quiz.init();
  });
</script>
</body>
</html>
