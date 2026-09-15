<?php
$require_auth = true;
require_once '../includes/auth_middleware.php';

$page_title = 'Misión Deep Space — EduQuest';
require_once '../includes/tailwind_header.php'; // Asumiendo que carga Tailwind y dependencias base
?>
<!-- Deep Space Theme Styles -->
<style>
  body {
    background-color: #0f172a;
    background-image: radial-gradient(circle at center, #1e1b4b 0%, #0f172a 100%);
    color: #f8fafc;
  }
  .glass-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(168, 85, 247, 0.3);
    box-shadow: 0 0 20px rgba(168, 85, 247, 0.15);
  }
  .neon-btn {
    border: 2px solid transparent;
    transition: all 0.3s ease;
  }
  .neon-btn:hover {
    border-color: rgba(168, 85, 247, 0.8);
    box-shadow: 0 0 15px rgba(168, 85, 247, 0.5);
    transform: translateY(-2px);
  }
  .neon-btn.correct {
    background: rgba(34, 197, 94, 0.2);
    border-color: #22c55e;
    box-shadow: 0 0 20px rgba(34, 197, 94, 0.4);
  }
  .neon-btn.incorrect {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
  }
  
  /* Animaciones */
  @keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
  }
  .floating { animation: float 3s ease-in-out infinite; }
</style>

<div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
  
  <!-- Estrellas de fondo animadas (Simulación CSS simple) -->
  <div class="absolute inset-0 pointer-events-none opacity-50 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] animate-pulse"></div>

  <!-- Header del Quiz -->
  <div class="w-full max-w-4xl flex justify-between items-center mb-8 z-10 glass-card p-4 rounded-2xl">
    <div class="flex items-center gap-3 text-purple-400">
      <i class="fas fa-rocket text-2xl"></i>
      <h1 class="text-xl font-bold tracking-widest uppercase">Misión Estelar</h1>
    </div>
    
    <div class="flex gap-6">
      <div class="flex items-center gap-2">
        <i class="fas fa-star text-yellow-400"></i>
        <span id="score-counter" class="text-2xl font-bold font-mono text-yellow-400">0</span>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-slate-400">Progreso:</span>
        <span id="progress-text" class="font-bold">1 / 5</span>
      </div>
    </div>
  </div>

  <!-- Contenedor Principal de la Pregunta -->
  <div id="quiz-container" class="w-full max-w-4xl glass-card rounded-3xl p-8 z-10 flex flex-col items-center text-center transition-all duration-500">
    
    <div class="mb-12 w-full">
      <h2 id="question-text" class="text-3xl md:text-5xl font-extrabold text-white leading-tight drop-shadow-md mb-6">
        Cargando coordenadas...
      </h2>
    </div>

    <!-- Opciones de Respuesta -->
    <div id="options-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl">
      <!-- Inyectado por JS -->
    </div>
    
  </div>

  <!-- Modal de Celebración (Oculto inicialmente) -->
  <div id="celebration-modal" class="fixed inset-0 bg-slate-900/90 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-500">
    <div class="glass-card p-12 rounded-3xl text-center max-w-lg transform scale-90 transition-transform duration-500" id="celebration-content">
      <div class="text-6xl mb-6 floating flex justify-center gap-4">
        <span>🚀</span><span>🏆</span><span>⭐</span>
      </div>
      <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600 mb-4">
        ¡Misión Cumplida!
      </h2>
      <p class="text-xl text-slate-300 mb-8">Has navegado el sector con éxito y sumado nuevos puntos a tu historial estelar.</p>
      
      <button onclick="window.location.href='student_view.php'" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold py-3 px-8 rounded-full shadow-lg transform transition hover:scale-105">
        Volver a la Base
      </button>
    </div>
  </div>

  <!-- Modal de Error: Señal Perdida (Oculto inicialmente) -->
  <div id="error-modal" class="fixed inset-0 bg-slate-950/95 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="glass-card border-red-500/50 p-10 rounded-3xl text-center max-w-lg shadow-[0_0_30px_rgba(239,68,68,0.2)]">
      <div class="text-6xl mb-6 text-red-500 animate-pulse">
        <i class="fas fa-satellite-dish"></i>
      </div>
      <h2 class="text-3xl font-bold text-red-400 mb-4 tracking-wider uppercase">
        ¡Señal Perdida!
      </h2>
      <p id="error-modal-message" class="text-lg text-slate-300 mb-8">
        No pudimos establecer contacto con la base de datos central. Revisa tu conexión de red o las credenciales del sistema.
      </p>
      
      <button onclick="window.location.reload()" class="bg-red-600 hover:bg-red-500 text-white font-bold py-3 px-8 rounded-full shadow-lg transform transition hover:scale-105 flex items-center justify-center gap-2 mx-auto">
        <i class="fas fa-sync-alt"></i> Reintentar Conexión
      </button>
    </div>
  </div>

</div>

<!-- Inyectar User ID para la lógica JS -->
<script>
  window.CURRENT_USER_ID = "<?= htmlspecialchars($_SESSION['user_id'] ?? '') ?>";
</script>

<!-- Scripts Requeridos -->
<script src="../js/quiz_mission.js"></script>

<?php require_once '../includes/tailwind_footer.php'; ?>
