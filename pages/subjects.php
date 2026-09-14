<?php
$current_page = 'subjects.php';
$page_title = 'Viaje Estelar — EduQuest Bachillerato';

require_once '../includes/tailwind_header.php';
?>

<div class="max-w-[1000px] mx-auto px-4 py-12 relative animate-[fadeIn_0.5s_ease-out]">
  
  <div class="text-center mb-16 relative z-10">
    <h1 class="font-heading text-4xl md:text-5xl font-extrabold text-white mb-4 drop-shadow-lg flex items-center justify-center gap-4">
      <i class="fas fa-rocket text-theme_accent"></i> Viaje Estelar
    </h1>
    <p class="text-theme_text_muted text-lg max-w-2xl mx-auto font-medium">Explora diferentes galaxias de conocimiento. Selecciona una misión y completa el recorrido para desbloquear la siguiente.</p>
  </div>

  <!-- Línea de conexión de la constelación -->
  <div class="absolute left-1/2 top-48 bottom-20 w-1.5 bg-[rgba(255,255,255,0.05)] -translate-x-1/2 rounded-full hidden md:block z-0"></div>

  <!-- Contenedor del Mapa Estelar (inyectado por JS) -->
  <div id="star-map-container" class="flex flex-col gap-12 md:gap-20 relative z-10"></div>
  
</div>

<div id="toast-container" class="toast-container"></div>
<script src="../js/sounds.js"></script>
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

  const container = document.getElementById('star-map-container');
  const subjects = Object.values(EQ_DATA.subjects);
  
  // Skeleton
  container.innerHTML = Array(4).fill(0).map((_, i) => `
    <div class="flex flex-col md:flex-row items-center gap-8 ${i % 2 !== 0 ? 'md:flex-row-reverse' : ''}">
      <div class="w-32 h-32 rounded-full bg-theme_bg border-4 border-theme_border/50 animate-pulse"></div>
      <div class="flex-1 glass-panel h-40 rounded-[2rem] bg-theme_bg animate-pulse"></div>
    </div>
  `).join('');

  setTimeout(() => {
    container.innerHTML = '';
    let previousProgress = 100; // La primera siempre desbloqueada

    subjects.forEach((sub, i) => {
      const progress = Math.min(100, user.stats.progressBySubject[sub.id] || 0);
      const isUnlocked = previousProgress >= 20; // Requiere 20% en la anterior para desbloquear
      previousProgress = progress;
      
      const isEven = i % 2 === 0;
      
      // Estado Visual
      let planetStatus = '';
      let planetColor = 'bg-theme_bg border-theme_border/50 filter grayscale opacity-60';
      let cardStyle = 'bg-theme_panel border-theme_border opacity-70 filter grayscale';
      let actionBtn = `<button class="btn bg-theme_bg text-theme_text_muted border border-theme_border cursor-not-allowed px-6 py-3 rounded-2xl font-bold w-full md:w-auto"><i class="fas fa-lock"></i> Bloqueado</button>`;
      
      if (progress === 100) {
        planetStatus = '<span class="absolute -top-3 -right-3 w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white border-4 border-theme_bg shadow-lg"><i class="fas fa-check"></i></span>';
        planetColor = `bg-gradient-to-br from-emerald-400 to-teal-600 border-emerald-400 shadow-[0_0_30px_rgba(52,211,153,0.3)] hover:scale-105 transition-transform`;
        cardStyle = 'glass-panel border-emerald-500/30 shadow-[0_0_20px_rgba(52,211,153,0.1)]';
        actionBtn = `<a href="quiz.php?subject=${sub.id}&mode=quiz" class="btn bg-emerald-500/20 text-emerald-400 border border-emerald-500 hover:bg-emerald-500 hover:text-white transition-colors px-6 py-3 rounded-2xl font-bold w-full md:w-auto"><i class="fas fa-redo"></i> Repasar</a>`;
      } else if (isUnlocked) {
        planetStatus = '<span class="absolute -top-3 -right-3 w-10 h-10 bg-theme_accent rounded-full flex items-center justify-center text-theme_bg border-4 border-theme_bg shadow-lg animate-bounce"><i class="fas fa-play"></i></span>';
        planetColor = `bg-gradient-to-br ${sub.gradient || 'from-blue-500 to-purple-600'} border-white shadow-[0_0_30px_rgba(255,255,255,0.4)] hover:scale-110 transition-transform animate-[pulse_3s_ease-in-out_infinite]`;
        cardStyle = 'glass-panel border-theme_accent/50 shadow-[0_0_30px_rgba(var(--accent),0.2)] hover:border-theme_accent transition-colors';
        actionBtn = `<a href="quiz.php?subject=${sub.id}&mode=quiz" class="btn bg-theme_accent text-theme_bg hover:shadow-[0_0_20px_rgba(var(--accent),0.6)] hover:scale-105 transition-all px-6 py-3 rounded-2xl font-bold w-full md:w-auto"><i class="fas fa-rocket"></i> Explorar</a>`;
      }

      const node = document.createElement('div');
      node.className = `flex flex-col ${isEven ? 'md:flex-row' : 'md:flex-row-reverse'} items-center gap-6 md:gap-12 w-full`;
      node.style.animation = `fadeInUp 0.6s ${i * 0.15}s ease both`;

      node.innerHTML = `
        <!-- Planeta (Nodo) -->
        <div class="relative z-10 shrink-0">
          <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-[6px] flex items-center justify-center text-5xl md:text-6xl ${planetColor} relative cursor-pointer" onclick="${isUnlocked ? `window.location.href='quiz.php?subject=${sub.id}&mode=quiz'` : ''}">
            ${sub.icon}
            ${planetStatus}
          </div>
          <!-- Conector móvil -->
          ${i !== subjects.length -1 ? '<div class="w-1.5 h-12 bg-[rgba(255,255,255,0.1)] mx-auto md:hidden mt-4 rounded-full"></div>' : ''}
        </div>

        <!-- Tarjeta de Información -->
        <div class="${cardStyle} p-6 md:p-8 rounded-[2.5rem] flex-1 w-full relative overflow-hidden group">
          <div class="absolute inset-0 bg-gradient-to-br from-[rgba(255,255,255,0.05)] to-transparent pointer-events-none"></div>
          
          <h2 class="font-heading text-2xl md:text-3xl font-extrabold ${isUnlocked ? 'text-white' : 'text-theme_text_muted'} mb-2">${sub.name}</h2>
          <p class="text-theme_text_muted font-medium mb-6 text-sm md:text-base">${sub.desc}</p>
          
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="w-full sm:w-1/2">
              <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-theme_text_muted mb-2">
                <span>Progreso</span>
                <span class="${progress === 100 ? 'text-emerald-400' : isUnlocked ? 'text-theme_accent' : ''}">${progress}%</span>
              </div>
              <div class="w-full h-3 bg-theme_bg rounded-full overflow-hidden border border-[rgba(255,255,255,0.05)]">
                <div class="h-full ${progress === 100 ? 'bg-emerald-400' : 'bg-theme_accent'} rounded-full transition-all duration-1000 ease-out" style="width: ${progress}%"></div>
              </div>
            </div>
            
            <div class="shrink-0 w-full sm:w-auto">
              ${actionBtn}
            </div>
          </div>
        </div>
      `;
      
      container.appendChild(node);
    });
  }, 400);
});
</script>

<?php require_once '../includes/tailwind_footer.php'; ?>

<?php require_once '../includes/tailwind_footer.php'; ?>
