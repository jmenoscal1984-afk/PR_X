/* =====================================================
   EDUQUEST BACHILLERATO — ui.js
   Sidebar, Topbar, Toast, Navegación, Tema, Animaciones UI
   ===================================================== */

const EQ_UI = (() => {

  /* ─── TOAST ─── */
  const showToast = ({ type = 'info', icon, title, message, duration = 3000 }) => {
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      container.className = 'toast-container';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
      <span class="toast-icon">${icon || getToastIcon(type)}</span>
      <div class="toast-text">
        ${title ? `<div class="toast-title">${title}</div>` : ''}
        ${message ? `<div class="toast-msg">${message}</div>` : ''}
      </div>
    `;
    container.appendChild(toast);

    setTimeout(() => {
      toast.classList.add('removing');
      setTimeout(() => toast.remove(), 300);
    }, duration);
  };

  const getToastIcon = (type) => {
    const icons = { success:'✅', error:'❌', warning:'⚠️', info:'ℹ️', xp:'⭐' };
    return icons[type] || '📢';
  };

  /* ─── SIDEBAR ─── */
  const initSidebar = () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const hamburger = document.getElementById('hamburger-btn');

    if (!sidebar) return;

    hamburger?.addEventListener('click', () => toggleSidebar());
    overlay?.addEventListener('click', () => closeSidebar());

    // Active link
    const page = window.location.pathname.split('/').pop();
    const links = sidebar.querySelectorAll('.nav-item[data-page]');
    links.forEach(link => {
      if (link.dataset.page === page) link.classList.add('active');
    });

    // Touch swipe to close
    let touchStartX = 0;
    sidebar.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; });
    sidebar.addEventListener('touchend', e => {
      if (e.changedTouches[0].clientX - touchStartX < -80) closeSidebar();
    });
  };

  const toggleSidebar = () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar?.classList.toggle('open');
    overlay?.classList.toggle('active');
  };

  const closeSidebar = () => {
    document.getElementById('sidebar')?.classList.remove('open');
    document.getElementById('sidebar-overlay')?.classList.remove('active');
  };

  /* ─── POPULATE SIDEBAR USER ─── */
  const populateSidebar = (user) => {
    if (!user) return;
    const levelInfo = EQ_Gamification.getLevelInfo(user.xp);

    const nameEl  = document.getElementById('sb-user-name');
    const levelEl = document.getElementById('sb-user-level');
    const xpEl    = document.getElementById('sb-user-xp');
    const avatarEl = document.getElementById('sb-user-avatar');
    const xpFill  = document.getElementById('sb-xp-fill');
    const xpVal   = document.getElementById('sb-xp-val');
    const xpText  = document.getElementById('sb-xp-text');

    if (nameEl)  nameEl.textContent  = user.name;
    if (levelEl) levelEl.textContent = `Nivel ${levelInfo.level} · ${levelInfo.name}`;
    if (xpEl)    xpEl.textContent    = `${user.xp} XP`;
    if (avatarEl) {
      if (user.avatar && user.avatar.startsWith('http')) {
        avatarEl.innerHTML = `<img src="${user.avatar}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`;
      } else {
        avatarEl.textContent = EQ_DATA.avatars[user.avatar] || user.avatar || '🧑‍💻';
      }
    }
    if (xpFill) xpFill.style.width   = `${levelInfo.progress}%`;
    if (xpVal)  xpVal.textContent    = `${user.xp} XP`;
    if (xpText) xpText.textContent   = levelInfo.nextLevel
      ? `Nivel ${levelInfo.level + 1} en ${levelInfo.xpToNext} XP`
      : '¡Nivel máximo alcanzado!';
  };

  /* ─── POPULATE TOPBAR ─── */
  const populateTopbar = (user) => {
    if (!user) return;
    const topbarRight = document.querySelector('header .flex.items-center.gap-6');
    if (!topbarRight) return;

    // Remover chips antiguos si existen
    const oldStreak = document.getElementById('topbar-streak');
    if (oldStreak) oldStreak.remove();

    // Propulsor de Fuego Cósmico (Racha)
    let streakContainer = document.getElementById('stellar-streak-container');
    if (!streakContainer) {
      streakContainer = document.createElement('div');
      streakContainer.id = 'stellar-streak-container';
      streakContainer.className = 'flex items-center gap-2 px-4 py-2 rounded-xl bg-theme_panel border-[3px] border-theme_border shadow-sm';
      // Insertar antes del selector de temas
      topbarRight.insertBefore(streakContainer, topbarRight.firstChild);
    }

    const isHot = user.streak >= 3;
    const fireColor = isHot ? 'text-orange-500' : 'text-orange-300 opacity-70';
    const fireAnim = isHot ? 'animate-pulse drop-shadow-[0_0_8px_rgba(249,115,22,0.8)]' : '';

    streakContainer.innerHTML = `
      <i class="fas fa-fire ${fireColor} ${fireAnim} text-xl"></i>
      <span class="font-extrabold text-theme_text">${user.streak}</span>
    `;
    
    // Si la racha es alta, el borde brilla
    if (isHot) {
      streakContainer.classList.add('border-orange-500/50', 'shadow-[0_0_15px_rgba(249,115,22,0.2)]');
    }
  };

  /* ─── THEME ─── */
  const applyTheme = (theme) => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('eq_theme', theme);
  };

  const loadTheme = () => {
    const settings = EQ_Storage.getSettings();
    applyTheme(settings.theme || 'light');
  };

  /* ─── ANIMATE ON SCROLL ─── */
  const initScrollReveal = () => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.will-animate').forEach(el => observer.observe(el));
  };

  /* ─── STAGGER CHILDREN ─── */
  const staggerChildren = (parentSelector, childSelector = '.card, .stat-card, .subject-card, .achievement-card') => {
    const parent = document.querySelector(parentSelector);
    if (!parent) return;
    const children = parent.querySelectorAll(childSelector);
    children.forEach((child, i) => {
      child.style.animationDelay = `${i * 0.08}s`;
      child.classList.add('will-animate');
    });
    // Trigger immediately
    requestAnimationFrame(() => {
      children.forEach(child => child.classList.add('animated'));
    });
  };

  /* ─── RIPPLE ─── */
  const addRipple = (el) => {
    el.classList.add('ripple-container');
    el.addEventListener('click', (e) => {
      const rect = el.getBoundingClientRect();
      const r = document.createElement('span');
      r.className = 'ripple-effect';
      const size = Math.max(rect.width, rect.height);
      r.style.width = r.style.height = size + 'px';
      r.style.left = (e.clientX - rect.left - size / 2) + 'px';
      r.style.top  = (e.clientY - rect.top  - size / 2) + 'px';
      el.appendChild(r);
      setTimeout(() => r.remove(), 600);
    });
  };

  /* ─── PROGRESS BAR ─── */
  const animateProgress = (el, pct, delay = 0) => {
    if (!el) return;
    el.style.width = '0%';
    setTimeout(() => { el.style.width = pct + '%'; }, delay + 100);
  };

  /* ─── COUNTER ─── */
  const animateCounter = (el, target, duration = 1000) => {
    if (!el) return;
    const start = performance.now();
    const update = (now) => {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
      el.textContent = Math.round(target * eased).toLocaleString('es');
      if (progress < 1) requestAnimationFrame(update);
    };
    requestAnimationFrame(update);
  };

  /* ─── MODAL ─── */
  const openModal = (id) => {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  };
  const closeModal = (id) => {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  };
  const initModals = () => {
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeModal(overlay.id);
      });
    });
    document.querySelectorAll('[data-open-modal]').forEach(btn => {
      btn.addEventListener('click', () => openModal(btn.dataset.openModal));
    });
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
      btn.addEventListener('click', () => closeModal(btn.dataset.closeModal));
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => closeModal(m.id));
      }
    });
  };

  /* ─── CONFIRM DIALOG ─── */
  const confirm = (message, onConfirm) => {
    const existing = document.getElementById('eq-confirm-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'eq-confirm-modal';
    modal.className = 'modal-overlay active';
    modal.innerHTML = `
      <div class="modal">
        <div class="modal-title">⚠️ Confirmar acción</div>
        <p class="modal-desc">${message}</p>
        <div style="display:flex;gap:12px;justify-content:flex-end">
          <button class="btn btn-ghost" id="eq-confirm-cancel">Cancelar</button>
          <button class="btn btn-danger" id="eq-confirm-ok">Confirmar</button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
    document.getElementById('eq-confirm-cancel').onclick = () => modal.remove();
    document.getElementById('eq-confirm-ok').onclick = () => { modal.remove(); onConfirm(); };
  };

  /* ─── LOGOUT BUTTON ─── */
  const initLogout = () => {
    document.querySelectorAll('[data-logout]').forEach(btn => {
      btn.addEventListener('click', () => {
        confirm('¿Seguro que deseas cerrar sesión?', () => EQ_Auth.logout());
      });
    });
  };

  /* ─── TEXT-TO-SPEECH (TTS) ─── */
  const speak = (text) => {
    if (!('speechSynthesis' in window)) {
      showToast({ type: 'error', icon: '🔇', title: 'TTS no soportado', message: 'Tu navegador no soporta lectura en voz alta.' });
      return;
    }
    window.speechSynthesis.cancel(); // Detener anterior
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'es-ES';
    utterance.rate = 0.9;
    utterance.pitch = 1.1; // Tono ligeramente más amigable
    window.speechSynthesis.speak(utterance);
  };

  /* ─── RENDER DAILY MISSIONS ─── */
  const renderDailyMissions = (user) => {
    const grid = document.getElementById('daily-missions-grid');
    if (!grid || !user) return;
    
    const missions = EQ_Gamification.getDailyMissions(user.id);
    grid.innerHTML = '';
    
    missions.forEach((m, i) => {
      const isDone = m.claimed;
      const card = document.createElement('button');
      card.className = `w-full min-h-[72px] flex items-center justify-between p-4 rounded-2xl border transition-all duration-300 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent text-left group ${isDone ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-theme_panel border-theme_border hover:border-theme_accent hover:shadow-[0_0_15px_rgba(250,204,21,0.2)]'}`;
      card.style.animation = `fadeInUp 0.5s ${i * 0.1}s ease both`;
      
      card.innerHTML = `
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl ${isDone ? 'bg-emerald-500/20 text-emerald-500' : 'bg-theme_bg text-theme_text_muted group-hover:bg-[rgba(250,204,21,0.1)] group-hover:text-yellow-500'} transition-colors flex items-center justify-center text-2xl shrink-0 border ${isDone ? 'border-emerald-500/30' : 'border-theme_border'}">
            ${isDone ? '<i class="fas fa-check"></i>' : m.icon}
          </div>
          <div>
            <h3 class="font-bold text-base sm:text-lg ${isDone ? 'text-emerald-500 line-through opacity-70' : 'text-theme_text'} leading-tight">${m.title}</h3>
            <p class="text-xs sm:text-sm text-theme_text_muted">${m.desc}</p>
          </div>
        </div>
        <div class="flex items-center shrink-0 ml-4">
          <span class="bg-[rgba(250,204,21,0.2)] text-yellow-500 font-extrabold px-3 py-1.5 rounded-lg text-xs sm:text-sm border border-[rgba(250,204,21,0.3)] whitespace-nowrap">
            +${m.xp} XP
          </span>
        </div>
      `;
      grid.appendChild(card);
    });
  };

  /* ─── INIT ALL ─── */
  const init = (user) => {
    loadTheme();
    initSidebar();
    initModals();
    initLogout();
    initScrollReveal();
    if (user) {
      populateSidebar(user);
      populateTopbar(user);
      renderDailyMissions(user);
    }
    // Ripple on all .btn
    document.querySelectorAll('.btn').forEach(addRipple);
  };

  return {
    showToast, initSidebar, toggleSidebar, closeSidebar,
    populateSidebar, populateTopbar,
    applyTheme, loadTheme,
    initScrollReveal, staggerChildren, animateProgress, animateCounter,
    addRipple, openModal, closeModal, initModals, confirm, initLogout,
    speak, init,
  };
})();

window.EQ_UI = EQ_UI;
