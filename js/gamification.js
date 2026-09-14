/* =====================================================
   EDUQUEST BACHILLERATO — gamification.js
   XP, Niveles, Insignias, Logros, Confetti, Level Up
   ===================================================== */

const EQ_Gamification = (() => {

  /* ─── NIVEL ─── */
  const getLevelInfo = (xp) => {
    const levels = EQ_DATA.levels;
    let current = levels[0];
    for (const l of levels) {
      if (xp >= l.minXP) current = l;
    }
    const idx = levels.indexOf(current);
    const next = levels[idx + 1] || null;
    const progress = next
      ? ((xp - current.minXP) / (next.minXP - current.minXP)) * 100
      : 100;
    return { ...current, nextLevel: next, progress: Math.min(100, progress), xpInLevel: xp - current.minXP, xpToNext: next ? next.minXP - xp : 0 };
  };

  /* ─── AÑADIR XP ─── */
  const addXP = (userId, amount, subject) => {
    const user = EQ_Storage.findUserById(userId);
    if (!user) return null;

    const oldLevel = getLevelInfo(user.xp);
    const newXP = user.xp + amount;
    const newLevel = getLevelInfo(newXP);

    // Update stats
    const patch = {
      xp: newXP,
      level: newLevel.level,
      stats: {
        totalXP: (user.stats?.totalXP || 0) + amount,
        xpBySubject: {
          ...user.stats.xpBySubject,
          [subject]: (user.stats.xpBySubject[subject] || 0) + amount,
        },
      },
    };

    const updated = EQ_Storage.updateUser(userId, patch);

    // Show XP animation
    showXPFloat(amount);

    // Check level up
    if (newLevel.level > oldLevel.level) {
      setTimeout(() => showLevelUp(newLevel), 800);
    }

    // Check achievements
    setTimeout(() => checkAchievements(userId), 500);

    return updated;
  };

  /* ─── REGISTRAR QUIZ ─── */
  const recordQuizResult = (userId, { subject, score, total, correct, isPerfect, fastCorrect }) => {
    const user = EQ_Storage.findUserById(userId);
    if (!user) return;

    const xpGained = calculateXP(correct, total, isPerfect, fastCorrect);

    // Update stats
    EQ_Storage.updateUser(userId, {
      stats: {
        totalQuizzes:   (user.stats.totalQuizzes || 0) + 1,
        totalQuestions: (user.stats.totalQuestions || 0) + total,
        totalCorrect:   (user.stats.totalCorrect || 0) + correct,
        perfectQuizzes: (user.stats.perfectQuizzes || 0) + (isPerfect ? 1 : 0),
        fastCorrect:    (user.stats.fastCorrect || 0) + fastCorrect,
        quizzesBySubject: {
          ...user.stats.quizzesBySubject,
          [subject]: (user.stats.quizzesBySubject[subject] || 0) + 1,
        },
        progressBySubject: {
          ...user.stats.progressBySubject,
          [subject]: Math.min(100, (user.stats.progressBySubject[subject] || 0) + (correct / total * 10)),
        },
      },
    });

    // Add history
    EQ_Storage.addHistoryEntry(userId, {
      subject, score, total, correct, isPerfect, xpGained,
      mode: 'quiz',
      subjectName: EQ_DATA.subjects[subject]?.name || subject,
    });

    // Add XP
    addXP(userId, xpGained, subject);

    return xpGained;
  };

  /* ─── CALCULAR XP ─── */
  const calculateXP = (correct, total, isPerfect, fastCorrect) => {
    let base = correct * 20;
    let bonus = 0;
    if (isPerfect) bonus += 50;
    if (fastCorrect > 0) bonus += fastCorrect * 5;
    const accuracy = correct / total;
    if (accuracy >= 0.8) bonus += 20;
    return base + bonus;
  };

  /* ─── VERIFICAR LOGROS ─── */
  const checkAchievements = (userId) => {
    const user = EQ_Storage.findUserById(userId);
    if (!user) return;

    const stats = { ...user.stats, level: user.level, streak: user.streak, totalXP: user.xp };
    const unlocked = [];

    for (const ach of EQ_DATA.achievements) {
      if (user.achievements.includes(ach.id)) continue;
      try {
        if (ach.condition(stats)) {
          unlocked.push(ach);
        }
      } catch {}
    }

    if (unlocked.length > 0) {
      const newAchievements = [...user.achievements, ...unlocked.map(a => a.id)];
      EQ_Storage.updateUser(userId, { achievements: newAchievements });
      unlocked.forEach((a, i) => {
        setTimeout(() => showAchievementToast(a), i * 2000);
      });
    }

    checkStickers(userId, stats, user.stickers || []);
  };

  /* ─── VERIFICAR STICKERS ─── */
  const checkStickers = (userId, stats, currentStickers) => {
    // Definimos condiciones simples para desbloquear los stickers de data.js
    const unlocks = [];
    if (!currentStickers.includes('stk_1') && stats.totalQuizzes >= 1) unlocks.push('stk_1');
    if (!currentStickers.includes('stk_2') && stats.streak >= 2) unlocks.push('stk_2');
    if (!currentStickers.includes('stk_3') && stats.totalXP >= 300) unlocks.push('stk_3');
    if (!currentStickers.includes('stk_4') && stats.fastCorrect >= 5) unlocks.push('stk_4');
    if (!currentStickers.includes('stk_5') && stats.perfectQuizzes >= 1) unlocks.push('stk_5');
    if (!currentStickers.includes('stk_6') && stats.level >= 3) unlocks.push('stk_6');
    if (!currentStickers.includes('stk_7') && stats.streak >= 5) unlocks.push('stk_7');
    if (!currentStickers.includes('stk_8') && stats.level >= 5) unlocks.push('stk_8');

    if (unlocks.length > 0) {
      const newStickers = [...currentStickers, ...unlocks];
      EQ_Storage.updateUser(userId, { stickers: newStickers });
      unlocks.forEach((stkId, i) => {
        const stk = EQ_DATA.stickers.find(s => s.id === stkId);
        if (stk) {
          setTimeout(() => {
            EQ_UI.showToast({ type: 'success', icon: stk.icon, title: '¡Nueva Calcomanía!', message: stk.name, duration: 4000 });
          }, (i * 2000) + 1000);
        }
      });
    }
  };

  /* ─── MISIONES DIARIAS ─── */
  const getDailyMissions = (userId) => {
    const user = EQ_Storage.findUserById(userId);
    if (!user) return [];
    
    // Simular misiones estáticas por ahora (se podrían rotar por fecha)
    const missions = [
      { id: 'dm_1', title: 'Explorador Diario', desc: 'Inicia sesión hoy.', xp: 20, icon: '🚀', done: true },
      { id: 'dm_2', title: 'Mente Activa', desc: 'Completa 1 Quiz de Matemática.', xp: 50, icon: '🔢', done: (user.stats.quizzesBySubject?.matematica || 0) > 0 },
      { id: 'dm_3', title: 'Velocista', desc: 'Responde 3 preguntas rápido.', xp: 50, icon: '⚡', done: (user.stats.fastCorrect || 0) >= 3 }
    ];

    // Verificar si hay que dar recompensas (simplificado)
    const claimedMissions = user.claimedMissions || [];
    missions.forEach(m => {
      if (m.done && !claimedMissions.includes(m.id)) {
        addXP(userId, m.xp, 'mision');
        claimedMissions.push(m.id);
        setTimeout(() => EQ_UI.showToast({ type: 'success', icon: m.icon, title: 'Misión Completada', message: `+${m.xp} XP` }), 1000);
      }
    });
    
    if (claimedMissions.length !== (user.claimedMissions || []).length) {
      EQ_Storage.updateUser(userId, { claimedMissions });
    }

    return missions.map(m => ({ ...m, claimed: claimedMissions.includes(m.id) }));
  };

  /* ─── XP FLOAT ANIMATION ─── */
  const showXPFloat = (amount) => {
    const el = document.createElement('div');
    el.className = 'xp-float-indicator';
    el.innerHTML = `⭐ +${amount} XP`;
    el.style.left = (window.innerWidth / 2 - 60) + 'px';
    el.style.top = (window.innerHeight / 2) + 'px';
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 1200);
  };

  /* ─── LEVEL UP OVERLAY ─── */
  const showLevelUp = (levelInfo) => {
    const overlay = document.createElement('div');
    overlay.className = 'level-up-overlay';
    overlay.innerHTML = `
      <div class="level-up-card">
        <div class="level-up-stars">⭐</div>
        <div class="level-up-badge">${levelInfo.level}</div>
        <div class="level-up-title">¡Subiste de nivel!</div>
        <div class="level-up-name">${levelInfo.icon} ${levelInfo.name}</div>
        <button class="btn btn-primary" onclick="this.closest('.level-up-overlay').remove(); launchConfetti();">
          ¡Increíble! 🎉
        </button>
      </div>
    `;
    document.body.appendChild(overlay);
    launchConfetti();
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) overlay.remove();
    });
  };

  /* ─── ACHIEVEMENT TOAST ─── */
  const showAchievementToast = (ach) => {
    EQ_UI.showToast({
      type: 'xp',
      icon: ach.icon,
      title: `¡Logro desbloqueado!`,
      message: ach.name,
      duration: 4000,
    });
  };

  /* ─── CONFETTI ─── */
  const launchConfetti = () => {
    const canvas = document.getElementById('confetti-canvas') || createConfettiCanvas();
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const particles = Array.from({ length: 60 }, () => ({
      x: Math.random() * canvas.width,
      y: -10,
      w: Math.random() * 8 + 4,
      h: Math.random() * 5 + 3,
      r: Math.random() * Math.PI * 2,
      vy: Math.random() * 1.5 + 1,
      vx: (Math.random() - 0.5) * 1.5,
      vr: (Math.random() - 0.5) * 0.05,
      color: ['#93c5fd','#a7f3d0','#fde68a','#fbcfe8','#d8b4fe','#e2e8f0'][Math.floor(Math.random()*6)],
    }));

    let frame;
    const draw = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      particles.forEach(p => {
        p.y += p.vy;
        p.x += p.vx;
        p.r += p.vr;
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.r);
        ctx.fillStyle = p.color;
        ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
        ctx.restore();
      });
      if (particles.some(p => p.y < canvas.height)) {
        frame = requestAnimationFrame(draw);
      } else {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
      }
    };

    draw();
    setTimeout(() => { cancelAnimationFrame(frame); ctx.clearRect(0,0,canvas.width,canvas.height); }, 4000);
  };

  const createConfettiCanvas = () => {
    const canvas = document.createElement('canvas');
    canvas.id = 'confetti-canvas';
    document.body.appendChild(canvas);
    return canvas;
  };

  /* ─── FORMAT XP ─── */
  const formatXP = (xp) => xp >= 1000 ? `${(xp/1000).toFixed(1)}k` : xp.toString();

  /* ─── ACCURACY ─── */
  const getAccuracy = (user) => {
    const { totalQuestions, totalCorrect } = user.stats;
    if (!totalQuestions) return 0;
    return Math.round((totalCorrect / totalQuestions) * 100);
  };

  return {
    getLevelInfo, addXP, recordQuizResult,
    calculateXP, checkAchievements, checkStickers,
    getDailyMissions,
    showXPFloat, showLevelUp, launchConfetti,
    formatXP, getAccuracy,
  };
})();

window.EQ_Gamification = EQ_Gamification;
window.launchConfetti = EQ_Gamification.launchConfetti;
