<?php
$current_page = 'settings.php';
$page_title = 'Configuración — EduQuest Bachillerato';
$extra_css = "";

require_once '../includes/tailwind_header.php';
?>

<main class="max-w-[90rem] mx-auto px-4 py-8 animate-[fadeIn_0.5s_ease-out]">
  <div class="mb-8">
    <h1 class="text-3xl font-black text-white flex items-center gap-3">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
        <i class="fas fa-cogs text-xl text-white"></i>
      </div>
      Configuración del Sistema
    </h1>
    <p class="text-slate-400 mt-2 md:ml-15">Personaliza tu experiencia, integra servicios y gestiona la seguridad de tu cuenta.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    
    <!-- 1. Metas de Entrenamiento -->
    <div class="bg-[#1E293B]/80 backdrop-blur border border-white/5 rounded-3xl p-6 shadow-2xl flex flex-col" x-data="{ goal: 'explorador' }">
      <h3 class="flex items-center gap-3 text-lg font-bold text-white mb-5">
        <i class="fas fa-bullseye text-blue-400"></i> Metas de Entrenamiento
      </h3>
      <p class="text-xs text-slate-400 mb-4">Elige tu ritmo de aprendizaje diario.</p>
      <div class="space-y-3 flex-1">
        
        <!-- Opcion 1 -->
        <label class="flex items-center gap-4 p-4 rounded-2xl border cursor-pointer transition-all duration-300 group"
               :class="goal === 'casual' ? 'bg-blue-500/10 border-blue-500 shadow-[0_0_15px_rgba(59,130,246,0.2)]' : 'bg-[#0F172A]/50 border-white/5 hover:border-white/20 hover:bg-[#0F172A]'">
          <input type="radio" name="goal" value="casual" x-model="goal" class="hidden">
          <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg flex-shrink-0 transition-colors"
               :class="goal === 'casual' ? 'bg-blue-500 text-white shadow-inner' : 'bg-slate-800 text-slate-400 group-hover:text-white'">☕</div>
          <div>
            <div class="text-sm font-bold text-white">Casual</div>
            <div class="text-xs font-semibold text-blue-400 mt-0.5">50 XP / día</div>
          </div>
        </label>

        <!-- Opcion 2 -->
        <label class="flex items-center gap-4 p-4 rounded-2xl border cursor-pointer transition-all duration-300 group"
               :class="goal === 'explorador' ? 'bg-purple-500/10 border-purple-500 shadow-[0_0_15px_rgba(168,85,247,0.2)]' : 'bg-[#0F172A]/50 border-white/5 hover:border-white/20 hover:bg-[#0F172A]'">
          <input type="radio" name="goal" value="explorador" x-model="goal" class="hidden">
          <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg flex-shrink-0 transition-colors"
               :class="goal === 'explorador' ? 'bg-purple-500 text-white shadow-inner' : 'bg-slate-800 text-slate-400 group-hover:text-white'">🚀</div>
          <div>
            <div class="text-sm font-bold text-white">Explorador</div>
            <div class="text-xs font-semibold text-purple-400 mt-0.5">100 XP / día</div>
          </div>
        </label>

        <!-- Opcion 3 -->
        <label class="flex items-center gap-4 p-4 rounded-2xl border cursor-pointer transition-all duration-300 group"
               :class="goal === 'intensivo' ? 'bg-orange-500/10 border-orange-500 shadow-[0_0_15px_rgba(249,115,22,0.2)]' : 'bg-[#0F172A]/50 border-white/5 hover:border-white/20 hover:bg-[#0F172A]'">
          <input type="radio" name="goal" value="intensivo" x-model="goal" class="hidden">
          <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg flex-shrink-0 transition-colors"
               :class="goal === 'intensivo' ? 'bg-orange-500 text-white shadow-inner' : 'bg-slate-800 text-slate-400 group-hover:text-white'">🔥</div>
          <div>
            <div class="text-sm font-bold text-white">Intensivo</div>
            <div class="text-xs font-semibold text-orange-400 mt-0.5">200 XP / día</div>
          </div>
        </label>

      </div>
    </div>

    <!-- 2. Conexiones Externas -->
    <div class="bg-[#1E293B]/80 backdrop-blur border border-white/5 rounded-3xl p-6 shadow-2xl flex flex-col">
      <h3 class="flex items-center gap-3 text-lg font-bold text-white mb-5">
        <i class="fas fa-link text-indigo-400"></i> Conexiones Externas
      </h3>
      <p class="text-xs text-slate-400 mb-4">Sincroniza tu cuenta con otras plataformas.</p>
      
      <div class="space-y-4 flex-1 flex flex-col justify-center">
        <!-- Discord -->
        <button class="w-full flex items-center gap-4 bg-[#5865F2] hover:bg-[#4752C4] text-white p-3 rounded-2xl transition-all font-bold shadow-[0_4px_15px_rgba(88,101,242,0.3)] hover:-translate-y-0.5">
          <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i class="fab fa-discord"></i></div>
          <span>Vincular Discord</span>
        </button>
        <!-- Google -->
        <button class="w-full flex items-center gap-4 bg-white hover:bg-gray-100 text-slate-800 p-3 rounded-2xl transition-all font-bold shadow-md hover:-translate-y-0.5 border border-slate-200">
          <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-xl text-blue-500"><i class="fab fa-google"></i></div>
          <span>Conectar con Google</span>
        </button>
        <!-- Calendario -->
        <button class="w-full flex items-center gap-4 bg-slate-700/50 hover:bg-slate-700 text-white p-3 rounded-2xl transition-all font-bold border border-white/5 hover:border-white/10 hover:-translate-y-0.5">
          <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-xl text-emerald-400"><i class="fas fa-calendar-alt"></i></div>
          <span>Sincronizar Calendario</span>
        </button>
      </div>
    </div>

    <!-- 3. Seguridad y Accesos -->
    <div class="bg-[#1E293B]/80 backdrop-blur border border-white/5 rounded-3xl p-6 shadow-2xl flex flex-col">
      <h3 class="flex items-center gap-3 text-lg font-bold text-white mb-5">
        <i class="fas fa-shield-alt text-emerald-400"></i> Seguridad y Accesos
      </h3>
      
      <div class="space-y-6">
        <!-- 2FA -->
        <div class="flex items-center justify-between p-4 bg-emerald-900/10 border border-emerald-500/20 rounded-2xl" x-data="{ on: false }">
          <div>
            <div class="text-sm font-bold text-white">Autenticación de 2 Factores</div>
            <div class="text-xs text-emerald-400/80 mt-0.5">Recomendado para máxima seguridad</div>
          </div>
          <button @click="on = !on" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none" :class="on ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]' : 'bg-slate-700'">
            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="on ? 'translate-x-6' : 'translate-x-1'"></span>
          </button>
        </div>

        <!-- Sesiones Activas -->
        <div>
          <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Sesiones Activas</h4>
          <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-black/20 rounded-xl border border-white/5">
              <i class="fas fa-laptop text-emerald-400 text-lg"></i>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">Windows - Chrome</p>
                <p class="text-xs text-emerald-400">Sesión Actual (Guayaquil)</p>
              </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-black/20 rounded-xl border border-white/5">
              <i class="fas fa-mobile-alt text-slate-500 text-lg"></i>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-300 truncate">Dispositivo Móvil</p>
                <p class="text-xs text-slate-500">Hace 2 días</p>
              </div>
            </div>
          </div>
          <button class="w-full mt-3 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 text-slate-300 text-xs font-bold transition-colors">
            Cerrar otras sesiones
          </button>
        </div>
      </div>
    </div>

    <!-- 4. Personalización del HUD -->
    <div class="bg-[#1E293B]/80 backdrop-blur border border-white/5 rounded-3xl p-6 shadow-2xl flex flex-col" x-data="{ accent: 'blue' }">
      <h3 class="flex items-center gap-3 text-lg font-bold text-white mb-5">
        <i class="fas fa-paint-brush text-pink-400"></i> Personalización del HUD
      </h3>
      
      <div class="space-y-6">
        <!-- Color de Acento -->
        <div>
          <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Color de Acento de la Nave</h4>
          <div class="flex items-center gap-4">
            <button @click="accent = 'blue'" class="w-10 h-10 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.6)] transition-all" :class="accent === 'blue' ? 'ring-4 ring-white scale-110' : 'hover:scale-110'"></button>
            <button @click="accent = 'green'" class="w-10 h-10 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.6)] transition-all" :class="accent === 'green' ? 'ring-4 ring-white scale-110' : 'hover:scale-110'"></button>
            <button @click="accent = 'orange'" class="w-10 h-10 rounded-full bg-orange-500 shadow-[0_0_10px_rgba(249,115,22,0.6)] transition-all" :class="accent === 'orange' ? 'ring-4 ring-white scale-110' : 'hover:scale-110'"></button>
            <button @click="accent = 'purple'" class="w-10 h-10 rounded-full bg-purple-500 shadow-[0_0_10px_rgba(168,85,247,0.6)] transition-all" :class="accent === 'purple' ? 'ring-4 ring-white scale-110' : 'hover:scale-110'"></button>
          </div>
        </div>

        <!-- Título Público -->
        <div>
          <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Título Público</h4>
          <select class="w-full bg-[#0F172A] border border-slate-700 text-white font-medium rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all shadow-inner">
            <option>Cadete</option>
            <option>Explorador Estelar</option>
            <option>Maestro del Saber</option>
            <option>Vanguardia Lógica</option>
          </select>
          <p class="text-[10px] text-slate-500 mt-2 ml-1">Este título será visible para otros estudiantes en el Ranking.</p>
        </div>
      </div>
    </div>

    <!-- 5. Sistemas de Audio y Visor -->
    <div class="bg-[#1E293B]/80 backdrop-blur border border-white/5 rounded-3xl p-6 shadow-2xl flex flex-col">
      <h3 class="flex items-center gap-3 text-lg font-bold text-white mb-5">
        <i class="fas fa-volume-up text-cyan-400"></i> Sistemas de Audio y Visor
      </h3>
      
      <div class="space-y-6">
        <!-- Sliders -->
        <div>
          <div class="flex justify-between items-center mb-2">
            <span class="text-sm text-slate-300 font-medium">Música de fondo</span>
            <i class="fas fa-music text-slate-500 text-xs"></i>
          </div>
          <input type="range" min="0" max="100" value="70" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-cyan-500 shadow-inner">
        </div>
        <div>
          <div class="flex justify-between items-center mb-2">
            <span class="text-sm text-slate-300 font-medium">Efectos de sonido (SFX)</span>
            <i class="fas fa-bolt text-slate-500 text-xs"></i>
          </div>
          <input type="range" min="0" max="100" value="100" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-cyan-500 shadow-inner">
        </div>

        <hr class="border-white/5">

        <!-- Toggles de Accesibilidad -->
        <div class="flex items-center justify-between" x-data="{ on: false }">
          <span class="text-sm text-slate-300 font-medium">Alto Contraste</span>
          <button @click="on = !on" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none" :class="on ? 'bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.8)]' : 'bg-slate-700'">
            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="on ? 'translate-x-6' : 'translate-x-1'"></span>
          </button>
        </div>
        <div class="flex items-center justify-between" x-data="{ on: false }">
          <span class="text-sm text-slate-300 font-medium">Reducir animaciones</span>
          <button @click="on = !on" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none" :class="on ? 'bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.8)]' : 'bg-slate-700'">
            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="on ? 'translate-x-6' : 'translate-x-1'"></span>
          </button>
        </div>
      </div>
    </div>

    <!-- 6. Gestión de Datos y Riesgo (Zona de Peligro) -->
    <div class="bg-red-950/20 border border-red-500/20 rounded-3xl p-6 shadow-2xl flex flex-col justify-between">
      <div>
        <h3 class="flex items-center gap-3 text-lg font-bold text-red-400 mb-2">
          <i class="fas fa-exclamation-triangle"></i> Gestión de Datos
        </h3>
        <p class="text-xs text-red-300/70 mb-5 leading-relaxed">Estas acciones son irreversibles. Proceder con precaución extrema al alterar el núcleo de datos.</p>
      </div>
      
      <div class="space-y-3 mt-4">
        <button class="w-full py-3 px-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 text-slate-300 text-sm font-bold transition-all flex items-center justify-center gap-2">
          <i class="fas fa-file-pdf text-slate-400"></i> Exportar mi historial
        </button>
        <button id="resetSettingsBtn" class="w-full py-3 px-4 rounded-xl bg-red-500/20 hover:bg-red-500 border border-red-500/30 text-red-400 hover:text-white text-sm font-bold transition-all flex items-center justify-center gap-2 shadow-inner hover:shadow-[0_0_15px_rgba(239,68,68,0.5)]">
          <i class="fas fa-trash-alt"></i> Eliminar cuenta y progreso
        </button>
      </div>
    </div>

  </div>
</main>

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
    const settings = EQ_Storage.getSettings();
    document.getElementById('themeSelect').value = settings.theme || 'light';

    document.getElementById('saveThemeBtn').addEventListener('click', () => {
      const theme = document.getElementById('themeSelect').value;
      EQ_Storage.saveSettings({ theme });
      // Fallback old applyTheme call
      if(typeof EQ_UI.applyTheme === 'function') EQ_UI.applyTheme(theme);
      EQ_UI.showToast({ type: 'success', icon: '🎨', title: 'Tema guardado', message: 'Configuración antigua actualizada.' });
    });

    document.getElementById('resetSettingsBtn').addEventListener('click', () => {
      EQ_UI.confirm('¿Seguro que quieres reiniciar tu progreso?', () => {
        EQ_Auth.resetProgress();
        EQ_UI.showToast({ type: 'warning', icon: '🧹', title: 'Progreso reiniciado', message: 'Tu avance quedó en cero.' });
        setTimeout(() => location.reload(), 700);
      });
    });
  });
</script>

<?php require_once '../includes/tailwind_footer.php'; ?>
