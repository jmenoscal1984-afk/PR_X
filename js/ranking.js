/* =====================================================
   EDUQUEST BACHILLERATO — ranking.js
   Ranking global, por materia y posición del usuario
   ===================================================== */

const EQ_Ranking = (() => {

  /* ─── GET ALL ENTRIES ─── */
  const getAllEntries = () => {
    const realUsers = EQ_Storage.getUsers().map(u => ({
      id:     u.id,
      name:   u.name,
      avatar: EQ_DATA.avatars[u.avatar] || '🧑‍💻',
      xp:     u.xp,
      level:  u.level,
      streak: u.streak,
      isReal: true,
    }));

    const demo = EQ_DATA.demoUsers.map((u, i) => ({
      id:     `demo_${i}`,
      name:   u.name,
      avatar: u.avatar,
      xp:     u.xp,
      level:  u.level,
      streak: u.streak,
      isReal: false,
    }));

    // Merge: replace demo if real user has same or more XP
    const all = [...realUsers, ...demo];

    // Sort by XP desc
    all.sort((a, b) => b.xp - a.xp);

    // Add rank
    return all.map((u, i) => ({ ...u, rank: i + 1 }));
  };

  /* ─── RENDER RANKING (Puestos 4 al 15) ─── */
  const render = (containerId, currentUserId) => {
    const container = document.getElementById(containerId);
    if (!container) return;

    const allEntries = getAllEntries();
    const entries = allEntries.slice(3, 15); // Del 4 al 15
    const maxXP = allEntries.length > 0 ? allEntries[0].xp : 1; // Para calcular progreso relativo

    container.innerHTML = '';

    entries.forEach((entry, index) => {
      const isMe = entry.id === currentUserId;
      const levelInfo = EQ_Gamification.getLevelInfo(entry.xp);
      const progressPercent = Math.min((entry.xp / maxXP) * 100, 100).toFixed(1);

      const card = document.createElement('button');
      card.className = `w-full flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-2xl glass-panel text-left transition-all duration-300 focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none mb-3 hover:bg-[rgba(255,255,255,0.05)] ${isMe ? 'border-theme_accent shadow-[0_0_15px_rgba(250,204,21,0.2)]' : 'border-theme_border'}`;
      card.style.animation = `fadeInUp 0.5s ${index * 0.05}s ease both`;

      card.innerHTML = `
        <div class="flex items-center gap-4 min-w-[200px]">
          <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-[rgba(255,255,255,0.1)] text-theme_text_muted font-heading font-extrabold text-xl border border-[rgba(255,255,255,0.05)] shrink-0">
            #${entry.rank}
          </div>
          <div class="flex items-center justify-center w-14 h-14 rounded-full bg-theme_bg border-2 border-[rgba(255,255,255,0.1)] text-3xl shrink-0 shadow-inner">
            ${entry.avatar}
          </div>
          <div class="flex flex-col">
            <span class="font-heading font-bold text-lg text-theme_text flex items-center gap-2">
              ${entry.name} ${isMe ? '<span class="text-[10px] bg-theme_accent text-[#000] px-2 py-0.5 rounded-full uppercase tracking-wider font-extrabold">Tú</span>' : ''}
            </span>
            <span class="text-sm text-theme_text_muted flex items-center gap-1 font-medium">
              ${levelInfo.icon} <span class="sr-only">Nivel:</span> ${levelInfo.name}
            </span>
          </div>
        </div>

        <div class="flex-1 w-full sm:px-4">
          <div class="flex justify-between items-end mb-1 text-xs font-bold text-theme_text_muted uppercase tracking-wider">
            <span class="flex items-center gap-1" aria-hidden="true"><i class="fas fa-bolt text-yellow-500"></i> Poder</span>
            <span class="sr-only">Progreso de experiencia:</span> <span>${progressPercent}%</span>
          </div>
          <div class="w-full h-2 bg-theme_bg rounded-full overflow-hidden border border-[rgba(255,255,255,0.05)]">
            <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full" style="width: ${progressPercent}%"></div>
          </div>
        </div>

        <div class="flex items-center sm:justify-end min-w-[120px]">
          <div class="bg-[rgba(250,204,21,0.1)] border border-[rgba(250,204,21,0.3)] text-yellow-400 font-extrabold px-4 py-2 rounded-xl flex items-center gap-2 text-lg w-full sm:w-auto justify-center">
            <i class="fas fa-star" aria-hidden="true"></i> ${entry.xp.toLocaleString('es')}
          </div>
        </div>
      `;

      container.appendChild(card);
    });

    // Mostrar el usuario si no está en el top 15
    const currentEntry = allEntries.find(e => e.id === currentUserId);
    if (currentEntry && currentEntry.rank > 15) {
      const sep = document.createElement('div');
      sep.innerHTML = '<div class="flex items-center gap-4 my-6"><div class="h-px bg-theme_border flex-1"></div><span class="text-theme_text_muted font-bold text-sm uppercase tracking-widest"><i class="fas fa-ellipsis-h"></i> Tu Posición <i class="fas fa-ellipsis-h"></i></span><div class="h-px bg-theme_border flex-1"></div></div>';
      container.appendChild(sep);

      // Tarjeta propia
      const card = document.createElement('button');
      card.className = `w-full flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-2xl glass-panel text-left transition-all duration-300 border-theme_accent shadow-[0_0_15px_rgba(250,204,21,0.2)] mb-3`;
      const levelInfo = EQ_Gamification.getLevelInfo(currentEntry.xp);
      const progressPercent = Math.min((currentEntry.xp / maxXP) * 100, 100).toFixed(1);

      card.innerHTML = `
        <div class="flex items-center gap-4 min-w-[200px]">
          <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-[rgba(250,204,21,0.15)] text-yellow-500 font-heading font-extrabold text-xl border border-[rgba(250,204,21,0.3)] shrink-0">
            #${currentEntry.rank}
          </div>
          <div class="flex items-center justify-center w-14 h-14 rounded-full bg-theme_bg border-2 border-theme_accent text-3xl shrink-0 shadow-[0_0_10px_rgba(250,204,21,0.3)]">
            ${currentEntry.avatar}
          </div>
          <div class="flex flex-col">
            <span class="font-heading font-bold text-lg text-theme_text flex items-center gap-2">
              ${currentEntry.name} <span class="text-[10px] bg-theme_accent text-[#000] px-2 py-0.5 rounded-full uppercase tracking-wider font-extrabold">Tú</span>
            </span>
            <span class="text-sm text-theme_text_muted flex items-center gap-1 font-medium">
              ${levelInfo.icon} <span class="sr-only">Nivel:</span> ${levelInfo.name}
            </span>
          </div>
        </div>
        <div class="flex-1 w-full sm:px-4">
          <div class="w-full h-2 bg-theme_bg rounded-full overflow-hidden border border-[rgba(255,255,255,0.05)]">
            <div class="h-full bg-theme_accent rounded-full" style="width: ${progressPercent}%"></div>
          </div>
        </div>
        <div class="flex items-center sm:justify-end min-w-[120px]">
          <div class="bg-[rgba(250,204,21,0.2)] border border-theme_accent text-yellow-400 font-extrabold px-4 py-2 rounded-xl flex items-center gap-2 text-lg w-full sm:w-auto justify-center">
            <i class="fas fa-star" aria-hidden="true"></i> ${currentEntry.xp.toLocaleString('es')}
          </div>
        </div>
      `;
      container.appendChild(card);
    }
  };

  /* ─── TOP 3 (PODIUM) ─── */
  const renderPodium = (containerId) => {
    const container = document.getElementById(containerId);
    if (!container) return;

    const entries = getAllEntries().slice(0, 3);
    const order = [1, 0, 2]; // Orden visual: 2do a la izquierda, 1ro en el centro, 3ro a la derecha

    container.innerHTML = `<div class="flex items-end justify-center gap-2 sm:gap-6 pt-12 pb-4">
      ${order.map(i => {
        const e = entries[i];
        if (!e) return '';
        const levelInfo = EQ_Gamification.getLevelInfo(e.xp);
        
        // Estilos específicos para cada puesto
        const isFirst = i === 0;
        const isSecond = i === 1;
        const isThird = i === 2;
        
        const medalIcon = isFirst ? '🥇' : isSecond ? '🥈' : '🥉';
        const medalColor = isFirst ? 'text-yellow-400' : isSecond ? 'text-slate-300' : 'text-amber-600';
        const medalBg = isFirst ? 'from-yellow-500 to-amber-600' : isSecond ? 'from-slate-400 to-slate-600' : 'from-amber-700 to-orange-900';
        const glowClass = isFirst ? 'shadow-[0_0_40px_rgba(250,204,21,0.4)] border-yellow-400' : 'border-[rgba(255,255,255,0.1)]';
        const height = isFirst ? 'h-48' : isSecond ? 'h-40' : 'h-32';
        const avatarSize = isFirst ? 'w-24 h-24 text-6xl -mt-12' : 'w-20 h-20 text-5xl -mt-10';

        return `
          <button class="relative flex flex-col items-center group focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent rounded-t-2xl transition-transform hover:-translate-y-2" style="animation: fadeInUp 0.6s ${i * 0.15}s ease both">
            
            <!-- Avatar y Medalla -->
            <div class="relative z-10 flex flex-col items-center">
              <div class="absolute -top-6 text-3xl z-20 ${medalColor} filter drop-shadow-[0_0_8px_currentColor] animate-bounce" style="animation-delay: ${i * 0.2}s">
                ${medalIcon}
              </div>
              <div class="flex items-center justify-center rounded-full bg-theme_bg border-4 ${glowClass} ${avatarSize} shadow-xl relative overflow-hidden group-hover:scale-105 transition-transform">
                ${e.avatar}
              </div>
              <div class="mt-3 text-center">
                <div class="font-heading font-extrabold text-theme_text text-base sm:text-lg max-w-[100px] truncate" title="${e.name}">
                  ${e.name}
                </div>
                <div class="text-xs font-bold text-theme_text_muted uppercase tracking-wider mt-1">
                  ${levelInfo.name}
                </div>
              </div>
            </div>

            <!-- Base del Podio -->
            <div class="w-24 sm:w-32 ${height} mt-4 rounded-t-2xl bg-gradient-to-b ${medalBg} relative overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.5)] border-t border-l border-r border-[rgba(255,255,255,0.2)] flex flex-col items-center justify-start pt-6">
              
              <!-- Reflejo Glassmorphism interno -->
              <div class="absolute inset-0 bg-gradient-to-b from-white/20 to-transparent pointer-events-none"></div>
              
              <div class="relative z-10 flex flex-col items-center text-[#000] font-heading">
                <span class="text-4xl font-black opacity-80">${i + 1}</span>
                <span class="text-[10px] uppercase font-bold tracking-widest opacity-80 mt-1">Lugar</span>
              </div>
              
              <div class="mt-auto mb-4 bg-black/30 px-3 py-1.5 rounded-xl backdrop-blur-sm border border-white/10 text-white font-extrabold text-sm sm:text-base flex items-center gap-1 shadow-inner relative z-10">
                <i class="fas fa-star text-yellow-400"></i> ${e.xp > 999 ? (e.xp/1000).toFixed(1) + 'k' : e.xp}
              </div>
            </div>
            
          </button>
        `;
      }).join('')}
    </div>`;
  };

  return { getAllEntries, render, renderPodium };
})();

window.EQ_Ranking = EQ_Ranking;
