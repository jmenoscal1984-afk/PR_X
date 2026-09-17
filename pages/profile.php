<?php
$current_page = 'profile.php';
$page_title = 'Mi Perfil — EduQuest Bachillerato';
$extra_css = <<<HTML
<style>
  .profile-grid { display:grid; grid-template-columns: 1.1fr 0.9fr; gap:24px; }
  .mini-stat { display:flex; align-items:center; gap:12px; padding:12px 14px; border-radius:16px; background:var(--bg-panel); border:1px solid var(--border-color); }
  .mini-stat strong { display:block; font-size:1.1rem; color:var(--text-primary); }
  .avatar-grid { display:flex; flex-wrap:wrap; gap:10px; }
  .avatar-chip { width:48px; height:48px; border-radius:999px; border:2px solid transparent; display:grid; place-items:center; font-size:1.3rem; background:rgba(0,0,0,0.3); cursor:pointer; transition:all .2s ease; }
  .avatar-chip.active, .avatar-chip:hover { border-color: var(--accent); transform: translateY(-2px); box-shadow: 0 8px 18px rgba(168,85,247,.18); }
  @media (max-width: 1100px) { .profile-grid { grid-template-columns: 1fr; } }
  
  .card { background: var(--bg-panel); backdrop-filter: blur(16px); border: 2px solid var(--border-color); border-radius: 1.5rem; padding: 2rem; }
  .section-title { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
  .section-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
  .form-group { margin-bottom: 1rem; }
  .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
  .form-input { width: 100%; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid var(--border-color); background: rgba(0,0,0,0.2); color: var(--text-primary); transition: all 0.2s; }
  .form-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 2px rgba(168,85,247,0.2); }
  
  .btn { padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.3s; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }
  .btn-primary { background: linear-gradient(to right, #2563EB, #9333EA); color: white; }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(147, 51, 234, 0.4); }
  .btn-ghost { background: transparent; border: 1px solid var(--border-color); color: var(--text-primary); }
  .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
  .btn-danger { background: rgba(239, 68, 68, 0.1); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.2); }
  .btn-danger:hover { background: #EF4444; color: white; }
  .divider { height: 1px; background: var(--border-color); margin: 1.5rem 0; }
  
  .toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
  .toast { background: var(--bg-panel); border: 1px solid var(--border-color); padding: 1rem 1.5rem; border-radius: 1rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.5); animation: slideIn 0.3s ease forwards; }
  .toast.removing { animation: slideOut 0.3s ease forwards; }
  .toast-icon { font-size: 1.5rem; }
  .toast-title { font-weight: 700; color: var(--text-primary); }
  .toast-msg { color: var(--text-secondary); font-size: 0.875rem; }
  @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
  @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
</style>
HTML;

require_once '../includes/tailwind_header.php';
?>

<main class="max-w-6xl mx-auto px-4 py-8 animate-[fadeIn_0.5s_ease-out]">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Columna Izquierda: Identidad y Subida (1/3) -->
    <div class="lg:col-span-1 space-y-6">
      <form @submit.prevent="uploadAvatar" enctype="multipart/form-data" 
            x-data="{ 
              photoPreview: '<?= htmlspecialchars($userAvatar) ?>', 
              isUploading: false,
              fileChosen(event) {
                const file = event.target.files[0];
                if (file) {
                  const reader = new FileReader();
                  reader.onload = (e) => { this.photoPreview = e.target.result; };
                  reader.readAsDataURL(file);
                }
              },
              async uploadAvatar(event) {
                const fileInput = event.target.querySelector('input[type=file]');
                if (!fileInput.files.length) {
                    alert('Selecciona una imagen primero.');
                    return;
                }
                
                this.isUploading = true;
                const formData = new FormData();
                formData.append('avatar_file', fileInput.files[0]);

                try {
                    const response = await fetch('procesar_avatar.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('¡Avatar actualizado con éxito!');
                        window.location.reload(); 
                    } else {
                        alert('Error: ' + data.error);
                    }
                } catch (error) {
                    alert('Error de conexión al servidor.');
                } finally {
                    this.isUploading = false;
                }
              }
            }" 
            class="bg-[#1E293B]/80 backdrop-blur border border-white/5 rounded-3xl p-6 shadow-2xl flex flex-col items-center text-center relative overflow-hidden">
        
        <!-- Gradiente de fondo -->
        <div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 to-transparent pointer-events-none"></div>

        <!-- Avatar Upload -->
        <div class="relative group cursor-pointer w-32 h-32 mb-4 rounded-full overflow-hidden ring-4 ring-blue-500/30 ring-offset-4 ring-offset-[#0F172A] transition-all hover:ring-blue-400">
          <template x-if="photoPreview.startsWith('http') || photoPreview.startsWith('data:') || photoPreview.includes('/')">
            <img :src="photoPreview" alt="Avatar" class="w-full h-full object-cover">
          </template>
          <template x-if="!photoPreview.startsWith('http') && !photoPreview.startsWith('data:') && !photoPreview.includes('/')">
             <div class="w-full h-full flex items-center justify-center text-6xl bg-gray-800" x-text="photoPreview"></div>
          </template>
          
          <!-- Overlay hover -->
          <div class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <i class="fas fa-camera text-white text-2xl mb-1"></i>
            <span class="text-[10px] text-white font-bold tracking-wider uppercase">Cambiar</span>
          </div>
          <!-- Input File -->
          <input type="file" name="avatar_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="fileChosen">
        </div>

        <!-- Info -->
        <h2 class="text-2xl font-bold text-white mb-1"><?= htmlspecialchars($userName) ?></h2>
        <span class="bg-gradient-to-r from-amber-500 to-orange-600 text-[10px] font-black tracking-wider px-3 py-1 rounded-full text-white uppercase shadow-[0_0_10px_rgba(245,158,11,0.5)] mb-6 inline-block">
          <?= htmlspecialchars($userRole) ?>
        </span>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-3 w-full mb-4">
          <!-- Nivel -->
          <div class="bg-black/20 border border-white/5 rounded-2xl p-3 flex flex-col items-center">
            <i class="fas fa-star text-yellow-400 text-lg drop-shadow-[0_0_8px_rgba(250,204,21,0.8)] mb-1"></i>
            <span class="text-white font-bold"><?= htmlspecialchars($userLevel) ?></span>
            <span class="text-[9px] text-slate-400 uppercase tracking-widest mt-1">Nivel</span>
          </div>
          <!-- XP -->
          <div class="bg-black/20 border border-white/5 rounded-2xl p-3 flex flex-col items-center">
            <i class="fas fa-bolt text-blue-400 text-lg drop-shadow-[0_0_8px_rgba(59,130,246,0.8)] mb-1"></i>
            <span class="text-white font-bold"><?= $userXP ?></span>
            <span class="text-[9px] text-slate-400 uppercase tracking-widest mt-1">XP Total</span>
          </div>
        </div>

        <!-- Nuevos Elementos Gamificados -->
        <!-- Barra de Progreso de Nivel -->
        <div class="w-full bg-black/30 rounded-xl p-3 border border-white/5 mb-4 text-left shadow-inner">
          <div class="flex justify-between items-center mb-1.5">
            <span class="text-[10px] text-blue-400 font-bold uppercase tracking-wider">Próximo Nivel</span>
            <span class="text-[10px] text-slate-400 font-bold">75%</span>
          </div>
          <div class="w-full bg-gray-800 rounded-full h-1.5 overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-400 h-full rounded-full shadow-[0_0_10px_rgba(56,189,248,0.8)]" style="width: 75%;"></div>
          </div>
          <p class="text-[9px] text-slate-500 mt-1.5 text-center uppercase tracking-widest font-semibold">Faltan 500 XP para el Nivel 2</p>
        </div>

        <!-- Racha y Medallas -->
        <div class="grid grid-cols-2 gap-3 w-full mb-6">
          <!-- Racha de Estudio -->
          <div class="bg-black/30 border border-orange-500/20 rounded-xl p-3 flex flex-col items-center justify-center shadow-inner group cursor-default">
            <div class="flex items-center gap-2">
              <i class="fas fa-fire text-orange-500 text-lg drop-shadow-[0_0_8px_rgba(249,115,22,0.8)] group-hover:scale-110 transition-transform animate-pulse"></i>
              <span class="text-white font-bold text-lg">5</span>
            </div>
            <span class="text-[9px] text-orange-200/50 uppercase tracking-widest mt-1 text-center leading-tight">Días de<br>Racha</span>
          </div>
          <!-- Insignias Destacadas -->
          <div class="bg-black/30 border border-purple-500/20 rounded-xl p-3 flex flex-col items-center justify-center shadow-inner cursor-default">
            <span class="text-[8px] text-purple-300/60 uppercase tracking-widest mb-1.5 font-bold">Insignias Top</span>
            <div class="flex gap-1.5">
              <div class="w-6 h-6 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-[0_0_8px_rgba(250,204,21,0.6)] hover:scale-110 transition-transform"><i class="fas fa-award text-[10px] text-white"></i></div>
              <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-[0_0_8px_rgba(59,130,246,0.6)] hover:scale-110 transition-transform"><i class="fas fa-brain text-[10px] text-white"></i></div>
              <div class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center shadow-[0_0_8px_rgba(168,85,247,0.6)] hover:scale-110 transition-transform"><i class="fas fa-star text-[10px] text-white"></i></div>
            </div>
          </div>
        </div>

        <button type="submit" :disabled="isUploading" class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-600/20 to-purple-600/20 border border-blue-500/30 text-blue-400 text-sm font-bold hover:bg-blue-600 hover:text-white hover:border-blue-400 transition-all flex items-center justify-center gap-2 mx-auto disabled:opacity-50">
          <i class="fas fa-upload" x-show="!isUploading"></i>
          <i class="fas fa-spinner fa-spin" x-show="isUploading" style="display: none;"></i>
          <span x-text="isUploading ? 'Subiendo...' : 'Guardar Foto'"></span>
        </button>
      </form>
    </div>

    <!-- Columna Derecha: Formularios (2/3) -->
    <div class="lg:col-span-2 space-y-6">
      
      <!-- Tarjeta A: Datos Personales -->
      <div class="bg-[#1E293B]/80 backdrop-blur border border-white/5 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full blur-[80px] pointer-events-none"></div>
        <h3 class="flex items-center gap-3 text-xl font-bold text-white mb-6 relative">
          <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shadow-inner border border-blue-500/30">
            <i class="fas fa-id-card"></i>
          </div>
          Datos Personales
        </h3>
        
        <form class="space-y-5 relative">
          <div class="space-y-1.5">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nombre Visible</label>
            <input type="text" value="<?= htmlspecialchars($userName) ?>" name="nombre" class="w-full bg-[#0F172A] border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-inner">
          </div>
          <div class="space-y-1.5">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
            <input type="email" value="<?= htmlspecialchars($_SESSION['usuario_correo'] ?? '') ?>" disabled class="w-full bg-[#0F172A]/50 border border-slate-800 text-slate-500 rounded-xl px-4 py-3 cursor-not-allowed shadow-inner">
            <p class="text-xs text-slate-500 ml-1 mt-1">El correo no puede ser modificado por seguridad.</p>
          </div>
          <div class="flex justify-end pt-2">
            <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-sm shadow-[0_4px_15px_rgba(59,130,246,0.4)] hover:shadow-[0_6px_20px_rgba(59,130,246,0.6)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
              <i class="fas fa-save"></i> Guardar Cambios
            </button>
          </div>
        </form>
      </div>

      <!-- Tarjeta B: Seguridad -->
      <div class="bg-[#1E293B]/80 backdrop-blur border border-white/5 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <h3 class="flex items-center gap-3 text-xl font-bold text-white mb-6">
          <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center shadow-inner border border-purple-500/30">
            <i class="fas fa-lock"></i>
          </div>
          Seguridad
        </h3>
        
        <form class="space-y-5">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-1.5">
              <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Contraseña Actual</label>
              <input type="password" name="current_password" class="w-full bg-[#0F172A] border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-inner">
            </div>
            <div class="space-y-1.5">
              <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nueva Contraseña</label>
              <input type="password" name="new_password" class="w-full bg-[#0F172A] border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all shadow-inner">
            </div>
          </div>
          <div class="flex justify-end pt-2">
            <button type="submit" class="px-8 py-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold text-sm hover:bg-white/10 hover:border-purple-400 transition-all flex items-center gap-2">
              <i class="fas fa-key"></i> Actualizar Contraseña
            </button>
          </div>
        </form>
      </div>

      <!-- Tarjeta C: Peligro -->
      <div class="bg-red-950/10 border border-red-500/20 rounded-3xl p-6 shadow-lg mt-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
          <div>
            <h4 class="text-red-400 font-bold mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> Zona de Peligro</h4>
            <p class="text-sm text-slate-400">Restablecer tu progreso borrará permanentemente toda tu XP, rachas e insignias.</p>
          </div>
          <button type="button" onclick="confirm('¿Estás seguro de que quieres borrar todo tu progreso?') && alert('Esta acción requerirá confirmación del backend próximamente.');" class="px-6 py-2.5 rounded-xl bg-red-500/20 text-red-400 font-bold text-sm border border-red-500/30 hover:bg-red-500 hover:text-white transition-all whitespace-nowrap">
            Restablecer Progreso
          </button>
        </div>
      </div>

    </div>
  </div>
</main>

<?php require_once '../includes/tailwind_footer.php'; ?>
