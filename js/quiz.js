/* =====================================================
   EDUQUEST BACHILLERATO — quiz.js
   Motor de Juegos: Quiz, V/F, Completar, Asociar
   ===================================================== */

const EQ_Quiz = (() => {

  /* Estado del quiz */
  let state = {
    subject:      null,
    mode:         'quiz', // quiz | tf | fill | match
    questions:    [],
    current:      0,
    score:        0,
    correct:      0,
    wrong:        0,
    timeLeft:     20,
    timerInterval: null,
    fastCorrect:  0,
    answered:     false,
    startTime:    null,
    selectedLeft: null,  // for match mode
    matchedPairs: 0,
    shields:      3,     // Energy shields
  };

  const TIMER_DEFAULT = 20;

  /* ─── INIT ─── */
  const init = () => {
    const params = new URLSearchParams(window.location.search);
    state.subject = params.get('subject') || 'matematica';
    state.mode    = params.get('mode')    || 'quiz';

    if (!EQ_Data.questions[state.subject]) {
      state.subject = 'matematica';
    }

    // Filter questions by mode
    let pool = EQ_DATA.questions[state.subject].filter(q => q.type === state.mode);
    if (pool.length === 0) pool = EQ_DATA.questions[state.subject].filter(q => q.type === 'quiz');

    // Shuffle & pick 10
    state.questions = shuffle(pool).slice(0, 10);
    state.current   = 0;
    state.score     = 0;
    state.correct   = 0;
    state.wrong     = 0;
    state.fastCorrect = 0;
    state.startTime = Date.now();
    state.shields   = 3;
    
    updateShieldsUI();
    renderQuestion();
  };

  /* ─── RENDER QUESTION ─── */
  const renderQuestion = () => {
    const q = state.questions[state.current];
    if (!q) { showResults(); return; }

    state.answered = false;

    updateHeader();
    renderQuestionCard(q);
    startTimer();
  };

  /* ─── HEADER & SHIELDS ─── */
  const updateHeader = () => {
    const total = state.questions.length;
    const idx   = state.current;

    const qNum = document.getElementById('quiz-question-num');
    if (qNum) qNum.textContent = `Pregunta ${idx + 1} / ${total}`;

    const progressFill = document.getElementById('quiz-progress-fill');
    if (progressFill) progressFill.style.width = `${((idx) / total) * 100}%`;
  };

  const updateShieldsUI = () => {
    const container = document.getElementById('energy-shields-container');
    if (!container) return;
    const icons = container.querySelectorAll('.shield-icon');
    icons.forEach((icon, index) => {
      if (index < state.shields) {
        icon.classList.remove('broken');
      } else {
        icon.classList.add('broken');
      }
    });
  };

  /* ─── RENDER QUESTION CARD ─── */
  const renderQuestionCard = (q) => {
    const questionText = document.getElementById('quiz-question-text');
    const optionsArea  = document.getElementById('quiz-options-area');
    const explanation  = document.getElementById('quiz-explanation');
    const ttsBtn       = document.getElementById('tts-btn');

    if (questionText) questionText.textContent = q.q;
    if (ttsBtn) {
      ttsBtn.onclick = () => EQ_UI.speak(q.q.replace(/'/g, "\\'"));
    }
    
    if (explanation)  { explanation.textContent = ''; explanation.style.display = 'none'; explanation.classList.add('hidden'); }

    if (!optionsArea) return;
    optionsArea.innerHTML = '';

    if (q.type === 'quiz') renderQuizOptions(q, optionsArea);
    else if (q.type === 'tf') renderTFOptions(q, optionsArea);
    else if (q.type === 'fill') renderFillOption(q, optionsArea);
    else if (q.type === 'match') renderMatchOptions(q, optionsArea);
  };

  /* ─── QUIZ (Opción múltiple) ─── */
  const renderQuizOptions = (q, container) => {
    const div = document.createElement('div');
    div.className = 'quiz-options-grid';

    q.options.forEach((opt, i) => {
      const btn = document.createElement('button');
      btn.className = 'quiz-option-big';
      btn.innerHTML = `<span>${opt}</span>`;
      btn.addEventListener('click', () => handleQuizAnswer(i, q, div));
      div.appendChild(btn);
    });
    container.appendChild(div);
  };

  const handleQuizAnswer = (chosen, q, container) => {
    if (state.answered) return;
    state.answered = true;
    stopTimer();

    const options = container.querySelectorAll('.quiz-option-big');
    options.forEach(o => o.classList.add('disabled'));

    const isCorrect = chosen === q.answer;
    options[chosen].classList.add(isCorrect ? 'correct' : 'wrong');
    options[chosen].innerHTML += isCorrect ? ' <i class="fas fa-check-circle ml-auto text-3xl"></i>' : ' <i class="fas fa-times-circle ml-auto text-3xl"></i>';
    
    if (!isCorrect) {
      options[q.answer].classList.add('correct');
      options[q.answer].innerHTML += ' <i class="fas fa-check-circle ml-auto text-3xl"></i>';
    }

    handleResult(isCorrect, q);
  };

  /* ─── VERDADERO O FALSO ─── */
  const renderTFOptions = (q, container) => {
    const div = document.createElement('div');
    div.className = 'tf-options';

    ['✅ Verdadero', '❌ Falso'].forEach((label, i) => {
      const btn = document.createElement('button');
      btn.className = `tf-btn ${i === 0 ? 'verdadero' : 'falso'}`;
      btn.textContent = label;
      const value = i === 0;
      btn.addEventListener('click', () => handleTFAnswer(value, q, div));
      div.appendChild(btn);
    });
    container.appendChild(div);
  };

  const handleTFAnswer = (chosen, q, container) => {
    if (state.answered) return;
    state.answered = true;
    stopTimer();

    const btns = container.querySelectorAll('.tf-btn');
    btns.forEach(b => b.classList.add('disabled'));

    const isCorrect = chosen === q.answer;
    const chosenIdx = chosen ? 0 : 1;
    const correctIdx = q.answer ? 0 : 1;
    btns[chosenIdx].classList.add(isCorrect ? 'correct' : 'wrong');
    btns[chosenIdx].innerHTML += isCorrect ? ' <i class="fas fa-check-circle" style="margin-left:8px;"></i>' : ' <i class="fas fa-times-circle" style="margin-left:8px;"></i>';
    
    if (!isCorrect) {
      btns[correctIdx].classList.add('correct');
      btns[correctIdx].innerHTML += ' <i class="fas fa-check-circle" style="margin-left:8px;"></i>';
    }

    handleResult(isCorrect, q);
  };

  /* ─── COMPLETAR PALABRAS ─── */
  const renderFillOption = (q, container) => {
    const div = document.createElement('div');
    div.className = 'fill-input-wrapper';

    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'fill-input';
    input.placeholder = `Pista: ${q.hint}`;
    input.id = 'fill-answer-input';

    const btn = document.createElement('button');
    btn.className = 'btn btn-primary';
    btn.textContent = 'Responder';
    btn.addEventListener('click', () => handleFillAnswer(input.value, q, input));
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') handleFillAnswer(input.value, q, input);
    });

    div.appendChild(input);
    div.appendChild(btn);
    container.appendChild(div);

    setTimeout(() => input.focus(), 100);
  };

  const handleFillAnswer = (value, q, input) => {
    if (state.answered) return;
    const trimmed = value.trim().toLowerCase();
    const expected = q.answer.toLowerCase();
    const isCorrect = trimmed === expected || expected.includes(trimmed) && trimmed.length > 2;

    state.answered = true;
    stopTimer();

    input.disabled = true;
    input.classList.add(isCorrect ? 'correct' : 'wrong');
    if (!isCorrect) {
      const hint = document.createElement('p');
      hint.style.cssText = 'color:var(--secondary);font-weight:600;margin-top:8px;font-size:0.9rem;';
      hint.textContent = `Respuesta correcta: ${q.answer}`;
      input.parentNode.appendChild(hint);
    }

    handleResult(isCorrect, q);
  };

  /* ─── ASOCIAR CONCEPTOS ─── */
  const renderMatchOptions = (q, container) => {
    state.selectedLeft = null;
    state.matchedPairs = 0;

    const div = document.createElement('div');
    div.className = 'match-container';

    const leftCol  = document.createElement('div');
    leftCol.className = 'match-col';
    leftCol.id = 'match-left';

    const rightCol = document.createElement('div');
    rightCol.className = 'match-col';
    rightCol.id = 'match-right';

    const shuffledRight = shuffle([...q.pairs.map(p => p.right)]);

    q.pairs.forEach((pair, i) => {
      const left = document.createElement('div');
      left.className = 'match-item';
      left.textContent = pair.left;
      left.dataset.index = i;
      left.addEventListener('click', () => handleMatchLeft(left, q));
      leftCol.appendChild(left);
    });

    shuffledRight.forEach(right => {
      const rightItem = document.createElement('div');
      rightItem.className = 'match-item';
      rightItem.textContent = right;
      rightItem.dataset.value = right;
      rightItem.addEventListener('click', () => handleMatchRight(rightItem, q));
      rightCol.appendChild(rightItem);
    });

    div.appendChild(leftCol);
    div.appendChild(rightCol);
    container.appendChild(div);
  };

  const handleMatchLeft = (el, q) => {
    if (el.classList.contains('matched')) return;
    document.querySelectorAll('#match-left .match-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    state.selectedLeft = el;
  };

  const handleMatchRight = (el, q) => {
    if (!state.selectedLeft || el.classList.contains('matched')) return;

    const leftIndex = parseInt(state.selectedLeft.dataset.index);
    const expectedRight = q.pairs[leftIndex].right;
    const chosenRight = el.dataset.value;

    if (chosenRight === expectedRight) {
      state.selectedLeft.classList.remove('selected');
      state.selectedLeft.classList.add('matched');
      el.classList.add('matched');
      state.matchedPairs++;
      state.selectedLeft = null;

      if (state.matchedPairs === q.pairs.length) {
        stopTimer();
        handleResult(true, q);
      }
    } else {
      state.selectedLeft.classList.add('wrong');
      el.classList.add('wrong');
      setTimeout(() => {
        state.selectedLeft?.classList.remove('wrong', 'selected');
        el.classList.remove('wrong');
        state.selectedLeft = null;
      }, 800);
      // Penalty: -2s
      state.timeLeft = Math.max(1, state.timeLeft - 2);
    }
  };

  /* ─── HANDLE RESULT ─── */
  const handleResult = (isCorrect, q) => {
    if (isCorrect) {
      if (window.EQ_Sounds) EQ_Sounds.playCorrect();
      const pts = calculatePoints();
      state.correct++;
      state.score += pts;
      if (state.timeLeft > 15) state.fastCorrect++;
    } else {
      if (window.EQ_Sounds) EQ_Sounds.playWrong();
      state.wrong++;
      state.shields--;
      updateShieldsUI();
    }

    // Show explanation
    const expEl = document.getElementById('quiz-explanation');
    if (expEl && q.explanation) {
      expEl.innerHTML = `<strong>${isCorrect ? '✅ ¡Correcto!' : '❌ Incorrecto.'}</strong> ${q.explanation}`;
      expEl.classList.remove('hidden');
      expEl.style.display = 'block';
      if (isCorrect) {
        expEl.classList.add('bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-400');
        expEl.classList.remove('bg-red-500/10', 'border-red-500/30', 'text-red-400');
      } else {
        expEl.classList.add('bg-red-500/10', 'border-red-500/30', 'text-red-400');
        expEl.classList.remove('bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-400');
      }
    }

    // Comprobar si se acabaron los escudos
    if (state.shields <= 0) {
      setTimeout(() => {
        showDefeat();
      }, 2000);
      return;
    }

    // Auto-advance
    setTimeout(() => {
      state.current++;
      renderQuestion();
    }, 2500);
  };

  /* ─── TIMER ─── */
  const startTimer = () => {
    state.timeLeft = TIMER_DEFAULT;
    updateTimerUI();

    state.timerInterval = setInterval(() => {
      state.timeLeft--;
      updateTimerUI();

      if (state.timeLeft <= 0) {
        stopTimer();
        if (!state.answered) {
          state.answered = true;
          handleResult(false, state.questions[state.current]);
        }
      }
    }, 1000);
  };

  const stopTimer = () => {
    clearInterval(state.timerInterval);
    state.timerInterval = null;
  };

  const updateTimerUI = () => {
    const bar = document.getElementById('timer-bar-fill');
    if (bar) {
      const pct = (state.timeLeft / TIMER_DEFAULT) * 100;
      bar.style.width = `${pct}%`;
      if (state.timeLeft <= 5) {
        bar.classList.add('bg-red-500');
        bar.classList.remove('bg-yellow-400');
      } else {
        bar.classList.add('bg-yellow-400');
        bar.classList.remove('bg-red-500');
      }
    }
  };

  /* ─── RESULTS & DEFEAT ─── */
  const showDefeat = () => {
    const container = document.getElementById('quiz-main-area');
    if (!container) return;

    container.innerHTML = `
      <div class="glass-panel p-8 md:p-12 rounded-[2.5rem] border border-red-500/30 text-center animate-[scaleIn_0.5s_ease-out] shadow-[0_0_50px_rgba(239,68,68,0.2)] max-w-2xl mx-auto w-full">
        <div class="text-6xl mb-6">💥</div>
        <h2 class="font-heading text-3xl font-extrabold text-red-400 mb-4">¡Escudos Agotados!</h2>
        <p class="text-theme_text_muted text-lg mb-8">No te preocupes, las estrellas se están recargando. La práctica hace al maestro. ¡Inténtalo de nuevo!</p>
        
        <div class="flex justify-center gap-4">
          <button class="btn bg-theme_panel text-theme_text border border-theme_border hover:border-theme_accent px-8 py-4 rounded-2xl font-bold text-lg focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent" onclick="window.location.href='subjects.php'">Salir</button>
          <button class="btn bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:shadow-[0_0_20px_rgba(168,85,247,0.5)] px-8 py-4 rounded-2xl font-bold text-lg focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent" onclick="location.reload()">Reintentar</button>
        </div>
      </div>
    `;
  };

  const showResults = () => {
    stopTimer();
    const total = state.questions.length;
    const pct   = Math.round((state.correct / total) * 100);
    const isPerfect = state.correct === total;
    const elapsed = Math.round((Date.now() - state.startTime) / 1000);

    const user = EQ_Auth.getUser();
    let xpGained = 0;
    if (user) {
      xpGained = EQ_Gamification.recordQuizResult(user.id, {
        subject:   state.subject,
        score:     state.score,
        total,
        correct:   state.correct,
        isPerfect,
        fastCorrect: state.fastCorrect,
      });
    }

    const progressFill = document.getElementById('quiz-progress-fill');
    if (progressFill) progressFill.style.width = '100%';

    const container = document.getElementById('quiz-main-area');
    if (!container) return;

    container.innerHTML = `
      <div class="glass-panel p-8 md:p-12 rounded-[2.5rem] border border-theme_border text-center animate-[scaleIn_0.5s_ease-out] shadow-[0_0_50px_rgba(250,204,21,0.2)] max-w-2xl mx-auto w-full relative overflow-hidden">
        
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-yellow-500/20 via-transparent to-transparent pointer-events-none"></div>

        <div class="text-7xl mb-6 relative z-10">${isPerfect ? '🏆' : pct >= 70 ? '🎉' : '📚'}</div>
        <h2 class="font-heading text-4xl font-extrabold text-white mb-2 relative z-10">¡Misión Completada!</h2>
        <p class="text-theme_accent font-bold text-xl mb-8 relative z-10">Precisión: ${pct}%</p>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8 relative z-10">
          <div class="bg-theme_bg p-4 rounded-2xl border border-theme_border">
            <div class="text-2xl text-emerald-400 font-bold mb-1">${state.correct}</div>
            <div class="text-xs text-theme_text_muted uppercase tracking-wider font-bold">Aciertos</div>
          </div>
          <div class="bg-theme_bg p-4 rounded-2xl border border-theme_border">
            <div class="text-2xl text-red-400 font-bold mb-1">${state.wrong}</div>
            <div class="text-xs text-theme_text_muted uppercase tracking-wider font-bold">Fallos</div>
          </div>
          <div class="bg-theme_bg p-4 rounded-2xl border border-theme_border">
            <div class="text-2xl text-blue-400 font-bold mb-1">${elapsed}s</div>
            <div class="text-xs text-theme_text_muted uppercase tracking-wider font-bold">Tiempo</div>
          </div>
          <div class="bg-theme_bg p-4 rounded-2xl border border-theme_border border-yellow-500/30">
            <div class="text-2xl text-yellow-400 font-bold mb-1">${state.score}</div>
            <div class="text-xs text-theme_text_muted uppercase tracking-wider font-bold">Puntos</div>
          </div>
        </div>

        ${xpGained ? `<div class="inline-block bg-yellow-500/20 text-yellow-400 border border-yellow-500/50 px-6 py-3 rounded-full font-bold text-lg mb-8 relative z-10 animate-bounce">⭐ +${xpGained} XP Ganados</div>` : ''}

        <div class="flex justify-center gap-4 relative z-10">
          <button class="btn bg-theme_panel text-theme_text border border-theme_border hover:border-theme_accent px-6 py-3 rounded-2xl font-bold text-lg focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent" onclick="window.location.href='subjects.php'">Continuar Viaje</button>
        </div>
      </div>
    `;

    if (isPerfect || pct >= 80) {
      // Small delay for the CSS animation to play
      setTimeout(() => {
        if(window.EQ_Gamification) EQ_Gamification.launchConfetti();
      }, 300);
    }
  };

  /* ─── HELPERS ─── */
  const calculatePoints = () => {
    const timeBonus = Math.floor(state.timeLeft * 2);
    return 10 + timeBonus;
  };

  const shuffle = (arr) => {
    const a = [...arr];
    for (let i = a.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  };

  const setText = (id, text) => {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
  };

  return { init, shuffle };
})();

window.EQ_Quiz = EQ_Quiz;
