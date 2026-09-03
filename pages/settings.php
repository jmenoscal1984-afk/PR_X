<?php
$base_dir = '../';
$page_title = 'Configuración — EduQuest Bachillerato';
$current_page = 'settings.php';
$page_title_header = 'Configuración';
$page_subtitle = 'Ajusta la experiencia visual y el progreso';

require_once '../includes/db_connect.php';
require_once '../includes/head.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="app-layout">
  <?php require_once '../includes/sidebar.php'; ?>
  <main class="main-content">
    <?php require_once '../includes/topbar.php'; ?>
    <div class="page-content">
      <div class="card" style="display:grid;gap:14px;max-width:720px">
        <div class="section-title"><div class="section-icon" style="background:var(--primary-light);color:var(--primary)">🎨</div>Apariencia</div>
        <label class="form-group"><span class="form-label">Tema</span><select id="themeSelect" class="form-input"><option value="light">Claro</option><option value="dark">Oscuro</option></select></label>
        <button class="btn btn-primary" id="saveThemeBtn" type="button">Guardar tema</button>
      </div>
      <div class="card" style="display:grid;gap:14px;max-width:720px;margin-top:18px">
        <div class="section-title"><div class="section-icon" style="background:var(--warning-light)">🧹</div>Progreso</div>
        <p style="color:var(--text-muted);margin:0">Reinicia tu cuenta para comenzar desde cero.</p>
        <button class="btn btn-danger" id="resetSettingsBtn" type="button">Restablecer progreso</button>
      </div>
    </div>
  </main>
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
      EQ_UI.applyTheme(theme);
      EQ_UI.showToast({ type: 'success', icon: '🎨', title: 'Tema aplicado', message: 'La apariencia se actualizó correctamente.' });
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
</body>
</html>
