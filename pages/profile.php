<?php
$base_dir = '../';
$page_title = 'Mi Perfil — EduQuest Bachillerato';
$current_page = 'profile.php';
$page_title_header = 'Mi Perfil';
$page_subtitle = 'Actualiza tu identidad y revisa tu progreso';

require_once '../includes/db_connect.php';

$extra_head = <<<HTML
<style>
  .profile-grid { display:grid; grid-template-columns: 1.1fr 0.9fr; gap:24px; }
  .mini-stat { display:flex; align-items:center; gap:12px; padding:12px 14px; border-radius:16px; background:var(--gray-50); border:1px solid var(--border-color); }
  .mini-stat strong { display:block; font-size:1.1rem; color:var(--text-primary); }
  .avatar-grid { display:flex; flex-wrap:wrap; gap:10px; }
  .avatar-chip { width:48px; height:48px; border-radius:999px; border:2px solid transparent; display:grid; place-items:center; font-size:1.3rem; background:var(--card-bg); cursor:pointer; transition:all .2s ease; }
  .avatar-chip.active, .avatar-chip:hover { border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 8px 18px rgba(79,70,229,.18); }
  @media (max-width: 1100px) { .profile-grid { grid-template-columns: 1fr; } }
</style>
HTML;

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
      <div class="profile-grid">
        <section class="card">
          <div class="section-title"><div class="section-icon" style="background:var(--primary-light);color:var(--primary)">👤</div>Datos personales</div>
          <div style="display:flex;gap:18px;align-items:center;margin-bottom:18px;flex-wrap:wrap">
            <div id="profile-avatar" style="font-size:64px">🧑‍💻</div>
            <div>
              <h2 id="profile-name" style="font-family:var(--font-heading);font-size:1.4rem;font-weight:800;margin:0 0 6px">Estudiante</h2>
              <p id="profile-email" style="color:var(--text-muted);margin:0">correo@ejemplo.com</p>
            </div>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px">
            <div class="mini-stat"><div style="font-size:1.2rem">⭐</div><div><strong id="profile-xp">0 XP</strong><span style="font-size:0.82rem;color:var(--text-muted)">Experiencia total</span></div></div>
            <div class="mini-stat"><div style="font-size:1.2rem">🏅</div><div><strong id="profile-level">Nivel 1</strong><span style="font-size:0.82rem;color:var(--text-muted)">Rango actual</span></div></div>
            <div class="mini-stat"><div style="font-size:1.2rem">🔥</div><div><strong id="profile-streak">0 días</strong><span style="font-size:0.82rem;color:var(--text-muted)">Racha</span></div></div>
            <div class="mini-stat"><div style="font-size:1.2rem">✅</div><div><strong id="profile-quizzes">0</strong><span style="font-size:0.82rem;color:var(--text-muted)">Quizzes completados</span></div></div>
          </div>
          <form id="profile-form" class="form-grid" style="display:grid;gap:12px">
            <div class="form-group"><label class="form-label" for="nameInput">Nombre visible</label><input id="nameInput" class="form-input" type="text" required /></div>
            <div class="form-group"><label class="form-label">Avatar</label><div class="avatar-grid" id="avatar-grid"></div></div>
            <button class="btn btn-primary" type="submit">Guardar cambios</button>
          </form>
        </section>

        <section class="card">
          <div class="section-title"><div class="section-icon" style="background:var(--accent-light);color:var(--accent)">🔐</div>Seguridad</div>
          <form id="password-form" style="display:grid;gap:12px">
            <div class="form-group"><label class="form-label" for="currentPassword">Contraseña actual</label><input id="currentPassword" class="form-input" type="password" required /></div>
            <div class="form-group"><label class="form-label" for="newPassword">Nueva contraseña</label><input id="newPassword" class="form-input" type="password" minlength="6" required /></div>
            <button class="btn btn-ghost" type="submit">Cambiar contraseña</button>
          </form>
          <div class="divider" style="margin:18px 0"></div>
          <button class="btn btn-danger" id="reset-btn" type="button">Restablecer progreso</button>
          <p style="font-size:0.82rem;color:var(--text-muted);margin-top:10px">Esta acción borra XP, rachas y logros del usuario actual.</p>
        </section>
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

    let selectedAvatar = user.avatar || 0;
    // Si selectedAvatar es un índice (número), obtener la URL, si no, usar la URL directamente
    let currentAvatarUrl = (typeof selectedAvatar === 'number') ? EQ_DATA.avatars[selectedAvatar] : selectedAvatar;
    
    const avatarGrid = document.getElementById('avatar-grid');
    EQ_DATA.avatars.forEach((avatarUrl, index) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      const isActive = (avatarUrl === currentAvatarUrl || index === selectedAvatar);
      btn.className = 'avatar-chip overflow-hidden relative' + (isActive ? ' active' : '');
      
      const img = document.createElement('img');
      img.src = avatarUrl;
      img.alt = `Avatar ${index + 1}`;
      img.className = 'w-full h-full object-cover';
      btn.appendChild(img);
      
      btn.title = `Avatar ${index + 1}`;
      btn.addEventListener('click', () => {
        selectedAvatar = avatarUrl; // Guardamos la URL
        currentAvatarUrl = avatarUrl;
        [...avatarGrid.children].forEach((child, i) => child.classList.toggle('active', i === index));
      });
      avatarGrid.appendChild(btn);
    });

    const levelInfo = EQ_Gamification.getLevelInfo(user.xp);
    
    const profileAvatarContainer = document.getElementById('profile-avatar');
    if (currentAvatarUrl.startsWith('http')) {
        profileAvatarContainer.innerHTML = `<img src="${currentAvatarUrl}" alt="Avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;border:4px solid var(--border-color);">`;
    } else {
        profileAvatarContainer.textContent = EQ_DATA.avatars[user.avatar] || '🧑‍💻';
    }
    
    document.getElementById('profile-name').textContent = user.name;
    document.getElementById('profile-email').textContent = user.email;
    document.getElementById('profile-xp').textContent = `${user.xp} XP`;
    document.getElementById('profile-level').textContent = `Nivel ${levelInfo.level} · ${levelInfo.name}`;
    document.getElementById('profile-streak').textContent = `${user.streak || 0} días`;
    document.getElementById('profile-quizzes').textContent = user.stats?.totalQuizzes || 0;
    document.getElementById('nameInput').value = user.name;

    document.getElementById('profile-form').addEventListener('submit', (e) => {
      e.preventDefault();
      const name = document.getElementById('nameInput').value.trim();
      const result = EQ_Auth.updateProfile({ name, avatar: selectedAvatar });
      if (result.ok) {
        EQ_UI.showToast({ type: 'success', icon: '✅', title: 'Perfil actualizado', message: 'Tu nombre y avatar se guardaron correctamente.' });
        setTimeout(() => location.reload(), 700);
      } else {
        EQ_UI.showToast({ type: 'error', icon: '❌', title: 'No se pudo actualizar', message: result.error });
      }
    });

    document.getElementById('password-form').addEventListener('submit', (e) => {
      e.preventDefault();
      const current = document.getElementById('currentPassword').value;
      const next = document.getElementById('newPassword').value;
      const result = EQ_Auth.changePassword(current, next);
      if (result.ok) {
        EQ_UI.showToast({ type: 'success', icon: '🔐', title: 'Contraseña cambiada', message: 'Tu contraseña se actualizó correctamente.' });
        e.target.reset();
      } else {
        EQ_UI.showToast({ type: 'error', icon: '❌', title: 'Error', message: result.error });
      }
    });

    document.getElementById('reset-btn').addEventListener('click', () => {
      EQ_UI.confirm('¿Deseas restablecer tu progreso? Se perderán XP, rachas y logros.', () => {
        EQ_Auth.resetProgress();
        EQ_UI.showToast({ type: 'warning', icon: '🧹', title: 'Progreso reiniciado', message: 'Tu avance quedó en cero.' });
        setTimeout(() => location.reload(), 700);
      });
    });
  });
</script>
</body>
</html>
