<?php
$current_page = 'ranking.php';
$page_title = 'Ranking — EduQuest Bachillerato';
<div class="max-w-[1200px] mx-auto space-y-12 animate-[fadeIn_0.5s_ease-out]">
  
  <!-- Podium Section -->
  <section>
    <div class="flex items-center justify-between mb-8">
      <h2 class="font-heading text-3xl font-extrabold text-theme_text flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-[rgba(250,204,21,0.2)] text-yellow-400 flex items-center justify-center text-2xl shadow-inner border border-[rgba(250,204,21,0.3)]">
          <i class="fas fa-trophy"></i>
        </div>
        Top 3 Exploradores
      </h2>
      <button type="button" aria-label="Leer posiciones en voz alta" onclick="window.speechSynthesis.cancel(); let u = new SpeechSynthesisUtterance('Sección del podio, aquí se muestran los tres mejores estudiantes.'); u.rate = 0.9; window.speechSynthesis.speak(u);" class="w-12 h-12 bg-theme_panel text-theme_text hover:bg-theme_accent hover:text-[#000] focus:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent rounded-full flex items-center justify-center text-lg transition-colors border border-theme_border shadow-sm">
        <i class="fas fa-volume-up"></i>
      </button>
    </div>
    
    <div class="glass-panel p-6 sm:p-10 rounded-[2.5rem] relative overflow-hidden shadow-2xl">
      <div id="podium" class="relative z-10 w-full min-h-[300px]"></div>
      <!-- Background glow -->
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[80%] bg-yellow-500/10 blur-[100px] pointer-events-none rounded-full"></div>
    </div>
  </section>

  <!-- Top 15 Section -->
  <section>
    <div class="flex items-center gap-4 mb-6">
      <div class="w-10 h-10 rounded-lg bg-[rgba(168,85,247,0.2)] text-purple-400 flex items-center justify-center text-xl shadow-inner border border-[rgba(168,85,247,0.3)]">
        <i class="fas fa-list-ol"></i>
      </div>
      <h3 class="font-heading text-2xl font-bold text-theme_text">Clasificación General (Top 15)</h3>
    </div>
    
    <div id="ranking-list" class="flex flex-col min-h-[400px]"></div>
  </section>

</div>

<div id="toast-container" class="toast-container"></div>
<script src="../js/data.js"></script>
<script src="../js/storage.js"></script>
<script src="../js/auth.js"></script>
<script src="../js/gamification.js"></script>
<script src="../js/ranking.js"></script>
<script src="../js/ui.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const user = EQ_Auth.getUser();
    if (!user) return;
    EQ_UI.init(user);
    
    const podiumDiv = document.getElementById('podium');
    const listDiv = document.getElementById('ranking-list');
    
    // 1. Mostrar Skeletons
    podiumDiv.innerHTML = `<div class="skeleton" style="width:100%; height:200px; margin: 32px 0;"></div>`;
    listDiv.innerHTML = Array(3).fill(0).map(() => `<div class="skeleton" style="width:100%; height:80px; margin-bottom:12px;"></div>`).join('');
    
    const fetchAndRenderScores = () => {
      // Obtenemos los datos para ver si hay estado vacío
      // (Si hay una llamada asíncrona a BD, colócala aquí antes de renderizar)
      const allUsers = EQ_Storage.getAllUsers();
      if (!allUsers || allUsers.length === 0) {
        podiumDiv.innerHTML = '';
        listDiv.innerHTML = `
          <div class="empty-state">
            <div class="empty-state-icon">🏆</div>
            <h3 class="empty-state-title">No hay jugadores en el ranking</h3>
            <p class="empty-state-desc">¡Sé el primero en jugar y conviértete en el líder del curso!</p>
          </div>
        `;
        return;
      }
      
      EQ_Ranking.renderPodium('podium');
      EQ_Ranking.render('ranking-list', user.id);
    };

    setTimeout(() => {
      // Carga inicial
      fetchAndRenderScores();
    }, 600); // Simulando carga de red

    // Suscripción al canal de Realtime de Supabase
    if (typeof supabase !== 'undefined') {
      const scoresChannel = supabase
        .channel('public:scores')
        .on('postgres_changes', { event: '*', schema: 'public', table: 'scores' }, payload => {
            console.log('Cambio detectado en vivo:', payload);
            // Función para actualizar el DOM del ranking dinámicamente
            fetchAndRenderScores();
        })
        .subscribe();
    } else {
      console.warn("Cliente Supabase no encontrado. Asegúrate de incluir el script de Supabase.");
    }
  });
</script>

<?php require_once '../includes/tailwind_footer.php'; ?>
