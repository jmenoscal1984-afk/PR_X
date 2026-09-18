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

<main class="max-w-7xl mx-auto px-4 py-12 animate-[fadeIn_0.5s_ease-out]">
  <!-- 1. Cabecera de la Sala (Hero Section) -->
  <div class="text-center mb-16 relative z-10">
    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-bold uppercase tracking-widest mb-6 shadow-lg backdrop-blur-md">
      <i class="fas fa-bolt text-yellow-400"></i> Potenciado por IA
    </div>
    <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight drop-shadow-2xl">
      Simuladores de <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">Conocimiento</span>
    </h1>
    <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
      Pon a prueba tus habilidades, completa las misiones de tus profesores y gana XP para liderar el ranking.
    </p>
  </div>

  <!-- 2. Cuadrícula de Juegos (El Arcade) -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
    
    <!-- Tarjeta 1: Quiz Táctico -->
    <div class="bg-[#1E293B]/80 backdrop-blur-md border border-white/5 rounded-3xl p-6 shadow-xl hover:-translate-y-2 hover:shadow-[0_0_25px_rgba(59,130,246,0.4)] transition-all duration-300 flex flex-col group relative overflow-hidden">
      <!-- Indicador Estado -->
      <div class="absolute top-4 right-4 flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/30 px-3 py-1 rounded-full">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
        <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">Nuevo Reto</span>
      </div>
      
      <div class="w-16 h-16 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-3xl text-blue-400 mb-6 group-hover:scale-110 transition-transform">
        <i class="fas fa-bolt"></i>
      </div>
      <h3 class="text-xl font-bold text-white mb-3">Quiz Táctico</h3>
      <p class="text-slate-400 text-sm mb-6 flex-1">Preguntas de opción múltiple generadas a partir de tu última clase.</p>
      
      <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/5">
        <div class="px-3 py-1 bg-gradient-to-r from-emerald-500/20 to-yellow-500/20 border border-emerald-500/30 rounded-lg text-emerald-400 text-xs font-black shadow-inner">
          +50 XP
        </div>
        <button class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-all shadow-[0_0_15px_rgba(59,130,246,0.3)] hover:shadow-[0_0_20px_rgba(59,130,246,0.6)]">
          Iniciar Quiz
        </button>
      </div>
    </div>

    <!-- Tarjeta 2: Sopa de Letras Cuántica -->
    <div class="bg-[#1E293B]/80 backdrop-blur-md border border-white/5 rounded-3xl p-6 shadow-xl hover:-translate-y-2 hover:shadow-[0_0_25px_rgba(168,85,247,0.4)] transition-all duration-300 flex flex-col group relative overflow-hidden">
      
      <div class="w-16 h-16 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-3xl text-purple-400 mb-6 group-hover:scale-110 transition-transform">
        <i class="fas fa-border-all"></i>
      </div>
      <h3 class="text-xl font-bold text-white mb-3">Sopa de Letras Cuántica</h3>
      <p class="text-slate-400 text-sm mb-6 flex-1">Encuentra los conceptos clave ocultos en el menor tiempo posible.</p>
      
      <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/5">
        <div class="px-3 py-1 bg-gradient-to-r from-emerald-500/20 to-yellow-500/20 border border-emerald-500/30 rounded-lg text-emerald-400 text-xs font-black shadow-inner">
          +75 XP
        </div>
        <button class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-all shadow-[0_0_15px_rgba(168,85,247,0.3)] hover:shadow-[0_0_20px_rgba(168,85,247,0.6)]">
          Buscar Palabras
        </button>
      </div>
    </div>

    <!-- Tarjeta 3: Crucigrama Estelar -->
    <div class="bg-[#1E293B]/80 backdrop-blur-md border border-white/5 rounded-3xl p-6 shadow-xl hover:-translate-y-2 hover:shadow-[0_0_25px_rgba(249,115,22,0.4)] transition-all duration-300 flex flex-col group relative overflow-hidden">
      
      <div class="w-16 h-16 rounded-2xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-3xl text-orange-400 mb-6 group-hover:scale-110 transition-transform">
        <i class="fas fa-puzzle-piece"></i>
      </div>
      <h3 class="text-xl font-bold text-white mb-3">Crucigrama Estelar</h3>
      <p class="text-slate-400 text-sm mb-6 flex-1">Descifra las pistas y completa el mapa de palabras de la lección.</p>
      
      <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/5">
        <div class="px-3 py-1 bg-gradient-to-r from-emerald-500/20 to-yellow-500/20 border border-emerald-500/30 rounded-lg text-emerald-400 text-xs font-black shadow-inner">
          +100 XP
        </div>
        <button class="bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-400 hover:to-red-400 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-all shadow-[0_0_15px_rgba(249,115,22,0.3)] hover:shadow-[0_0_20px_rgba(249,115,22,0.6)]">
          Resolver
        </button>
      </div>
    </div>

  </div>
</main>

<script src="../js/games-demo.js"></script>

<?php require_once '../includes/tailwind_footer.php'; ?>
