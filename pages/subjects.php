<?php
$current_page = 'subjects.php';
$page_title = 'Viaje Estelar — EduQuest Bachillerato';

require_once '../includes/tailwind_header.php';
?>

<main class="max-w-2xl mx-auto flex flex-col items-center gap-12 py-10 relative animate-[fadeIn_0.5s_ease-out]" x-data="{ activeNode: null }">
  
  <div class="text-center mb-8 relative z-10 w-full">
    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 drop-shadow-lg flex items-center justify-center gap-4">
      <i class="fas fa-rocket text-blue-500"></i> Viaje Estelar
    </h1>
    <p class="text-slate-400 text-lg font-medium">Sigue el recorrido de misiones para dominar el universo.</p>
  </div>

  <!-- Línea Conectora (Fondo) -->
  <div class="absolute top-48 bottom-20 left-1/2 -translate-x-1/2 border-l-4 border-dashed border-white/20 z-0"></div>

  <!-- NODO 1 (Completado) -->
  <div class="relative z-10 flex flex-col items-center ml-24" @mouseenter="activeNode = 1" @mouseleave="activeNode = null">
    <button class="w-24 h-24 rounded-full bg-[#0B1120] border-4 border-yellow-500 shadow-[0_0_20px_rgba(234,179,8,0.5)] flex items-center justify-center text-3xl text-yellow-500 hover:scale-110 transition-transform">
      <i class="fas fa-star"></i>
    </button>
    <div class="mt-3 bg-black/50 px-4 py-1.5 rounded-full border border-white/10 text-xs font-bold text-white shadow-lg backdrop-blur-md">
      Lógica Matemática - 100%
    </div>
    
    <!-- Pop-over Alpine -->
    <div x-show="activeNode === 1" x-transition.opacity.duration.300ms class="absolute left-full ml-6 top-0 w-48 bg-[#1E293B]/90 backdrop-blur-md border border-white/10 rounded-2xl p-4 shadow-2xl z-50">
      <h4 class="text-white font-bold text-sm mb-1">Lógica Matemática</h4>
      <p class="text-yellow-400 text-xs font-bold mb-3">+500 XP Obtenidos</p>
      <button class="w-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold py-2 rounded-xl transition-colors">Repasar</button>
    </div>
  </div>

  <!-- NODO 2 (En Progreso) -->
  <div class="relative z-10 flex flex-col items-center mr-24" @mouseenter="activeNode = 2" @mouseleave="activeNode = null">
    <button class="w-24 h-24 rounded-full bg-blue-900 border-4 border-blue-500 animate-pulse ring-4 ring-blue-500 ring-offset-4 ring-offset-[#0B1120] flex items-center justify-center text-4xl text-white hover:scale-110 transition-transform">
      <i class="fas fa-rocket"></i>
    </button>
    <div class="mt-5 bg-blue-500/20 px-4 py-1.5 rounded-full border border-blue-500/50 text-xs font-bold text-blue-300 shadow-lg backdrop-blur-md">
      Física de Vectores
    </div>
    
    <!-- Pop-over Alpine -->
    <div x-show="activeNode === 2" x-transition.opacity.duration.300ms class="absolute right-full mr-6 top-0 w-48 bg-[#1E293B]/90 backdrop-blur-md border border-blue-500/30 rounded-2xl p-4 shadow-[0_0_30px_rgba(59,130,246,0.3)] z-50">
      <h4 class="text-white font-bold text-sm mb-1">Física de Vectores</h4>
      <p class="text-blue-400 text-xs font-bold mb-3">Recompensa: +500 XP</p>
      <button class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-2 rounded-xl transition-colors shadow-inner">Iniciar Misión</button>
    </div>
  </div>

  <!-- NODO 3 (Bloqueado) -->
  <div class="relative z-10 flex flex-col items-center ml-24" @mouseenter="activeNode = 3" @mouseleave="activeNode = null">
    <button class="w-24 h-24 rounded-full bg-[#1E293B] border-4 border-[#334155] grayscale opacity-60 flex items-center justify-center text-3xl text-slate-400 cursor-not-allowed">
      <i class="fas fa-lock"></i>
    </button>
    <div class="mt-3 bg-black/30 px-4 py-1.5 rounded-full border border-white/5 text-xs font-bold text-slate-500 shadow-lg backdrop-blur-md">
      Misión Desconocida
    </div>
    
    <!-- Pop-over Alpine -->
    <div x-show="activeNode === 3" x-transition.opacity.duration.300ms class="absolute left-full ml-6 top-0 w-48 bg-[#1E293B]/90 backdrop-blur-md border border-white/5 rounded-2xl p-4 shadow-2xl z-50">
      <h4 class="text-slate-400 font-bold text-sm mb-1">Misión Bloqueada</h4>
      <p class="text-slate-500 text-xs mb-2">Completa Física de Vectores para desbloquear.</p>
    </div>
  </div>

  <!-- NODO 4 (Bloqueado) -->
  <div class="relative z-10 flex flex-col items-center ml-0" @mouseenter="activeNode = 4" @mouseleave="activeNode = null">
    <button class="w-24 h-24 rounded-full bg-[#1E293B] border-4 border-[#334155] grayscale opacity-60 flex items-center justify-center text-3xl text-slate-400 cursor-not-allowed">
      <i class="fas fa-lock"></i>
    </button>
    <div class="mt-3 bg-black/30 px-4 py-1.5 rounded-full border border-white/5 text-xs font-bold text-slate-500 shadow-lg backdrop-blur-md">
      Misión Desconocida
    </div>
  </div>

</main>

<?php require_once '../includes/tailwind_footer.php'; ?>

<?php require_once '../includes/tailwind_footer.php'; ?>
