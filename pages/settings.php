<?php
$current_page = 'settings.php';
$page_title = 'Configuración — EduQuest Bachillerato';
$extra_css = <<<HTML
<style>
  .card { background: var(--bg-panel); backdrop-filter: blur(16px); border: 2px solid var(--border-color); border-radius: 1.5rem; padding: 2rem; }
  .section-title { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
  .section-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
  .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }
  .form-label { font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
  .form-input { padding: 12px 16px; border-radius: 0.75rem; border: 1px solid var(--border-color); background: rgba(0,0,0,0.2); color: var(--text-primary); }
  
  .btn { padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.3s; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; width: fit-content; }
  .btn-primary { background: linear-gradient(to right, #2563EB, #9333EA); color: white; }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(147, 51, 234, 0.4); }
  .btn-danger { background: rgba(239, 68, 68, 0.1); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.2); }
  .btn-danger:hover { background: #EF4444; color: white; }
</style>
HTML;

require_once '../includes/tailwind_header.php';
?>

<div class="page-content">
  <div class="card" style="display:grid;gap:14px;max-width:720px;margin-bottom:24px;">
    <div class="section-title"><div class="section-icon" style="background:rgba(147,51,234,0.2);color:var(--accent);">🎨</div>Apariencia</div>
    <label class="form-group">
      <span class="form-label">Tema Visual del Sistema Antiguo</span>
      <select id="themeSelect" class="form-input">
        <option value="light">Claro</option>
        <option value="dark">Oscuro</option>
      </select>
      <p style="font-size:0.8rem;color:var(--text-muted);margin-top:4px;">Nota: El nuevo layout Tailwind tiene su propio selector de temas en la barra superior.</p>
    </label>
    <button class="btn btn-primary" id="saveThemeBtn" type="button">Guardar tema heredado</button>
  </div>
  
  <div class="card" style="display:grid;gap:14px;max-width:720px;">
    <div class="section-title"><div class="section-icon" style="background:rgba(239,68,68,0.2);color:#EF4444;">🧹</div>Progreso</div>
    <p style="color:var(--text-secondary);margin:0">Reinicia tu cuenta para comenzar desde cero. Se perderán todos tus logros, XP y niveles.</p>
    <button class="btn btn-danger" id="resetSettingsBtn" type="button">Restablecer progreso</button>
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
