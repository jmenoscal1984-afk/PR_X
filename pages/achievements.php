<?php
$current_page = 'achievements.php';
$page_title = 'Logros — EduQuest Bachillerato';
$extra_css = <<<HTML
<style>
  .ach-grid { display:grid; grid-template-columns: repeat(2, 1fr); gap:20px; margin-top:20px; }
  .achievement-card { 
    background: var(--bg-panel); backdrop-filter: blur(16px);
    border-radius: 1.5rem;
    border: 2px solid var(--border-color);
    padding: 24px;
    min-height: 160px;
    transition: all 0.3s ease;
    display: flex; flex-direction: column; align-items: center; text-align: center;
  }
  .achievement-card.unlocked { border-color: var(--accent); box-shadow: 0 4px 20px rgba(168,85,247,0.2); }
  .achievement-card.locked { opacity: 0.7; filter: grayscale(100%); }
  .achievement-icon { font-size: 3.5rem; margin-bottom: 12px; }
  .achievement-name { font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
  .achievement-desc { color: var(--text-secondary); font-size: 0.9rem; }
  
  .card { background: var(--bg-panel); backdrop-filter: blur(16px); border: 2px solid var(--border-color); border-radius: 1.5rem; padding: 2rem; }
  .section-title { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; }
  .section-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
  @media (max-width: 1100px) { .ach-grid { grid-template-columns: 1fr; } }
</style>
HTML;

require_once '../includes/tailwind_header.php';
?>

<div class="max-w-[1200px] mx-auto space-y-12 animate-[fadeIn_0.5s_ease-out]" x-data="{ tab: 'calcomanias' }">
  
  <!-- 1. Cabecera (Hero Section) -->
  <div class="glass-panel p-8 sm:p-10 rounded-[2.5rem] border border-theme_border shadow-xl relative overflow-hidden flex flex-col items-center text-center">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-900/30 to-blue-900/10 blur-3xl -z-10 pointer-events-none"></div>
    <div class="w-24 h-24 bg-gradient-to-tr from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-5xl mb-6 shadow-[0_0_40px_rgba(168,85,247,0.5)]">
      🎒
    </div>
    <h1 class="font-heading text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-300 mb-4">
      Mi Mochila Estelar
    </h1>
    <p class="text-theme_text_muted text-lg mb-8 max-w-2xl">
      Tu colección personal de hazañas. Cada calcomanía y medalla es un testimonio de tu viaje en la academia.
    </p>
    
    <!-- Barra de progreso general -->
    <div class="w-full max-w-md bg-theme_bg/80 rounded-full h-4 border border-[rgba(255,255,255,0.1)] p-0.5 shadow-inner overflow-hidden relative">
      <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-transparent"></div>
      <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-full rounded-full shadow-[0_0_15px_rgba(236,72,153,0.6)] relative z-10" style="width: 15%;"></div>
    </div>
    <p class="text-xs text-theme_text_muted font-bold tracking-widest uppercase mt-3">
      Nivel de Coleccionista: 15% completado
    </p>
  </div>

  <!-- 2. Sistema de Pestañas (Tabs) con Alpine.js -->
  <div class="flex justify-center">
    <div class="glass-panel inline-flex p-1.5 rounded-full border border-[rgba(255,255,255,0.1)] shadow-lg gap-2">
      <button @click="tab = 'calcomanias'" 
              :class="tab === 'calcomanias' ? 'bg-gradient-to-r from-purple-600/80 to-blue-600/80 text-white shadow-[0_0_15px_rgba(168,85,247,0.5)] border border-purple-400/50' : 'text-theme_text_muted hover:text-white border border-transparent hover:bg-white/5'"
              class="px-6 md:px-8 py-3 rounded-full font-bold text-sm transition-all duration-300 flex items-center">
        <i class="fas fa-sticky-note mr-2"></i> Álbum de Calcomanías
      </button>
      <button @click="tab = 'insignias'" 
              :class="tab === 'insignias' ? 'bg-gradient-to-r from-blue-600/80 to-cyan-600/80 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)] border border-blue-400/50' : 'text-theme_text_muted hover:text-white border border-transparent hover:bg-white/5'"
              class="px-6 md:px-8 py-3 rounded-full font-bold text-sm transition-all duration-300 flex items-center">
        <i class="fas fa-medal mr-2"></i> Insignias de Honor
      </button>
    </div>
  </div>

  <!-- Contenedor de Vistas -->
  <div class="relative min-h-[500px]">
    
    <!-- 3. Cuadrícula de Calcomanías -->
    <div x-show="tab === 'calcomanias'" 
         x-transition:enter="transition ease-out duration-500" 
         x-transition:enter-start="opacity-0 translate-y-8 scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
         class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
      
      <!-- EJEMPLO: ESTADO DESBLOQUEADO (Holográfico) -->
      <div class="glass-panel aspect-[3/4] rounded-2xl border-2 border-purple-500/30 p-4 flex flex-col items-center justify-between hover:scale-105 hover:border-purple-400 hover:shadow-[0_0_25px_rgba(168,85,247,0.4)] transition-all duration-300 group cursor-pointer relative overflow-hidden bg-gradient-to-br from-[rgba(255,255,255,0.05)] to-purple-500/10">
        <!-- Brillo holográfico on hover -->
        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/15 to-transparent opacity-0 group-hover:opacity-100 group-hover:translate-x-full transition-all duration-700 pointer-events-none transform -skew-x-12 -ml-20 w-[200%]"></div>
        <!-- Contenedor del Icono -->
        <div class="w-full aspect-square bg-theme_bg/50 rounded-xl shadow-[inset_0_4px_10px_rgba(0,0,0,0.5)] border border-theme_border/50 flex items-center justify-center text-5xl md:text-6xl group-hover:rotate-12 transition-transform duration-500 bg-gradient-to-br from-gray-800 to-gray-900">
          🚀
        </div>
        <div class="text-center w-full mt-3">
          <h3 class="font-heading font-extrabold text-white text-sm md:text-base leading-tight">Primer Vuelo</h3>
          <p class="text-[10px] text-purple-300 mt-1 uppercase font-bold tracking-wider"><i class="fas fa-check-circle"></i> Adquirida</p>
        </div>
      </div>

      <!-- EJEMPLO: ESTADO BLOQUEADO -->
      <div class="glass-panel aspect-[3/4] rounded-2xl border-2 border-dashed border-theme_border/50 p-4 flex flex-col items-center justify-between bg-theme_bg/40 grayscale opacity-50 relative pointer-events-none">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-4xl text-gray-500 z-10 drop-shadow-md">
          <i class="fas fa-lock"></i>
        </div>
        <div class="w-full aspect-square bg-theme_bg rounded-xl shadow-inner border border-theme_border/30 flex items-center justify-center text-6xl blur-[3px]">
          👽
        </div>
        <div class="text-center w-full mt-3">
          <h3 class="font-heading font-bold text-gray-400 text-sm md:text-base leading-tight">???</h3>
          <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold tracking-wider">Bloqueado</p>
        </div>
      </div>
      
    </div>

    <!-- 4. Cuadrícula de Insignias -->
    <div x-show="tab === 'insignias'" 
         x-transition:enter="transition ease-out duration-500" 
         x-transition:enter-start="opacity-0 translate-y-8 scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
         style="display: none;"
         class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 place-items-center">
      
      <!-- EJEMPLO: INSIGNIA DESBLOQUEADA -->
      <div class="group flex flex-col items-center cursor-pointer hover:-translate-y-2 transition-transform duration-300">
        <!-- Medalla base -->
        <div class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 p-[3px] shadow-[0_0_15px_rgba(59,130,246,0.5)] group-hover:shadow-[0_0_30px_rgba(59,130,246,0.8)] transition-shadow">
          <div class="w-full h-full rounded-full bg-theme_panel border-[4px] border-indigo-900/80 flex items-center justify-center text-4xl shadow-inner relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(59,130,246,0.3)_0%,_transparent_70%)]"></div>
            <i class="fas fa-brain text-blue-400 group-hover:text-white transition-colors relative z-10 drop-shadow-[0_0_10px_rgba(59,130,246,0.8)]"></i>
          </div>
        </div>
        <h3 class="mt-4 font-heading font-extrabold text-blue-300 text-center text-sm uppercase tracking-wide">Mente Maestra</h3>
      </div>

      <!-- EJEMPLO: INSIGNIA BLOQUEADA -->
      <div class="flex flex-col items-center grayscale opacity-40">
        <div class="w-28 h-28 rounded-full bg-theme_border p-[3px] border border-dashed border-gray-600">
          <div class="w-full h-full rounded-full bg-theme_bg flex items-center justify-center text-3xl shadow-inner">
            <i class="fas fa-lock text-gray-500"></i>
          </div>
        </div>
        <h3 class="mt-4 font-heading font-bold text-gray-500 text-center text-sm uppercase tracking-wide">Desconocida</h3>
      </div>

    </div>

  </div>

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

    const sGrid = document.getElementById('stickers-grid');
    const aGrid = document.getElementById('achievements-grid');
    
    // Skeleton
    sGrid.innerHTML = Array(4).fill('<div class="h-48 glass-panel rounded-2xl animate-pulse bg-theme_bg border border-theme_border/50"></div>').join('');
    aGrid.innerHTML = Array(3).fill('<div class="h-40 glass-panel rounded-2xl animate-pulse bg-theme_bg border border-theme_border/50"></div>').join('');

    setTimeout(() => {
      // 1. STICKERS
      const userStickers = new Set(user.stickers || []);
      sGrid.innerHTML = '';
      
      EQ_DATA.stickers.forEach((stk, idx) => {
        const unlocked = userStickers.has(stk.id);
        const card = document.createElement('button');
        card.className = `group flex flex-col items-center justify-center text-center p-6 rounded-3xl transition-all duration-300 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent border-2 
          ${unlocked 
            ? 'glass-panel border-[rgba(255,255,255,0.2)] hover:border-theme_accent hover:shadow-[0_0_20px_rgba(168,85,247,0.3)] hover:-translate-y-2' 
            : 'bg-theme_bg/30 border-dashed border-theme_border/50 opacity-60 hover:opacity-100 cursor-not-allowed'}`;
        card.style.animation = `fadeInUp 0.5s ${idx * 0.05}s ease both`;

        card.innerHTML = `
          <div class="relative mb-4">
            <div class="w-24 h-24 rounded-full flex items-center justify-center text-6xl transition-transform duration-500 
              ${unlocked ? 'bg-gradient-to-br from-[rgba(255,255,255,0.1)] to-[rgba(0,0,0,0.3)] shadow-inner border-4 border-[rgba(255,255,255,0.2)] group-hover:rotate-12 group-hover:scale-110' : 'bg-transparent border-4 border-dashed border-theme_border/50 filter grayscale opacity-50'}">
              ${stk.icon}
            </div>
            ${!unlocked ? '<div class="absolute -bottom-2 -right-2 bg-theme_panel border border-theme_border w-10 h-10 rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-lock text-theme_text_muted"></i></div>' : ''}
          </div>
          <h3 class="font-heading font-extrabold text-lg text-theme_text mb-1">${stk.name}</h3>
          <p class="text-sm text-theme_text_muted font-medium px-2 ${!unlocked ? 'hidden' : ''}">${stk.desc}</p>
          <span class="mt-3 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full border 
            ${unlocked ? 'bg-purple-500/20 text-purple-400 border-purple-500/30' : 'bg-theme_bg text-theme_text_muted border-theme_border'}">
            ${unlocked ? '<i class="fas fa-check mr-1"></i> Adquirido' : 'Bloqueado'}
          </span>
        `;
        sGrid.appendChild(card);
      });

      // 2. LOGROS
      const userAchievements = new Set(user.achievements || []);
      aGrid.innerHTML = '';
      
      EQ_DATA.achievements.forEach((ach, idx) => {
        const unlocked = userAchievements.has(ach.id);
        const card = document.createElement('button');
        card.className = `group flex items-start gap-5 p-6 rounded-2xl transition-all duration-300 text-left focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent border-2 
          ${unlocked 
            ? 'glass-panel border-yellow-500/30 shadow-[0_0_15px_rgba(250,204,21,0.1)] hover:border-yellow-400 hover:shadow-[0_0_20px_rgba(250,204,21,0.3)] hover:-translate-y-1' 
            : 'bg-theme_panel border-theme_border hover:border-[rgba(255,255,255,0.2)] opacity-70 filter grayscale hover:grayscale-0'}`;
        card.style.animation = `fadeInUp 0.5s ${idx * 0.05}s ease both`;

        card.innerHTML = `
          <div class="w-16 h-16 shrink-0 rounded-full flex items-center justify-center text-4xl 
            ${unlocked ? 'bg-gradient-to-br from-yellow-400 to-orange-500 shadow-inner' : 'bg-theme_bg border border-theme_border/50 text-theme_text_muted'} transition-transform group-hover:scale-110">
            ${unlocked ? ach.icon : '<i class="fas fa-lock text-2xl"></i>'}
          </div>
          <div class="flex-1">
            <h3 class="font-heading font-extrabold text-xl ${unlocked ? 'text-yellow-400' : 'text-theme_text'} mb-1">${ach.name}</h3>
            <p class="text-sm text-theme_text_muted font-medium mb-3">${ach.desc}</p>
            <div class="w-full bg-theme_bg h-1.5 rounded-full overflow-hidden border border-theme_border/50">
              <div class="h-full ${unlocked ? 'bg-yellow-400 w-full' : 'bg-theme_text_muted w-0'} transition-all duration-1000 ease-out"></div>
            </div>
          </div>
        `;
        aGrid.appendChild(card);
      });
      
    }, 600);
  });
</script>

<?php require_once '../includes/tailwind_footer.php'; ?>
