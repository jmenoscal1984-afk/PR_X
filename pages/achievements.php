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

<div class="max-w-[1200px] mx-auto space-y-12 animate-[fadeIn_0.5s_ease-out]">
  
  <div class="glass-panel p-8 sm:p-10 rounded-[2.5rem] border border-theme_border shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
    <div class="relative z-10">
      <h1 class="font-heading text-4xl font-extrabold text-theme_text flex items-center gap-4 mb-4">
        <i class="fas fa-briefcase text-theme_accent"></i> Mi Mochila
      </h1>
      <p class="text-theme_text_muted text-lg max-w-xl">Colecciona calcomanías estelares y desbloquea insignias por tus grandes hazañas en la academia.</p>
    </div>
    <div class="relative z-10 w-32 h-32 bg-[rgba(255,255,255,0.05)] rounded-full border-4 border-theme_accent/30 shadow-[0_0_30px_rgba(168,85,247,0.2)] flex items-center justify-center text-6xl hover:scale-105 transition-transform">
      🎒
    </div>
    <div class="absolute right-[-10%] top-[-50%] w-96 h-96 bg-theme_accent/10 rounded-full blur-[80px] pointer-events-none"></div>
  </div>

  <!-- STICKERS / CALCOMANÍAS -->
  <section>
    <div class="flex items-center gap-4 mb-6">
      <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-2xl shadow-inner border border-purple-500/30">
        <i class="fas fa-sticky-note"></i>
      </div>
      <h2 class="font-heading text-3xl font-bold text-theme_text">Álbum de Calcomanías</h2>
    </div>
    <div id="stickers-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6"></div>
  </section>

  <!-- LOGROS / INSIGNIAS -->
  <section>
    <div class="flex items-center gap-4 mb-6">
      <div class="w-12 h-12 rounded-xl bg-yellow-500/20 text-yellow-500 flex items-center justify-center text-2xl shadow-inner border border-yellow-500/30">
        <i class="fas fa-medal"></i>
      </div>
      <h2 class="font-heading text-3xl font-bold text-theme_text">Insignias de Honor</h2>
    </div>
    <div id="achievements-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
  </section>

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
