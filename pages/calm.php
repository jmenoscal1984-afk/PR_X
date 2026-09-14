<?php
$current_page = 'calm.php';
$page_title = 'Mi Rincón Seguro — EduQuest Bachillerato';
$extra_css = <<<HTML
<style>
  /* Animación de respiración profunda (4s inhalar, 2s sostener, 4s exhalar, 2s pausa = 12s total) */
  @keyframes breathe {
    0% { transform: scale(0.6); opacity: 0.6; }
    33% { transform: scale(1); opacity: 1; } /* 4s: Inhala */
    50% { transform: scale(1); opacity: 1; } /* 2s: Sostén */
    83% { transform: scale(0.6); opacity: 0.6; } /* 4s: Exhala */
    100% { transform: scale(0.6); opacity: 0.6; } /* 2s: Pausa */
  }

  .breathe-circle {
    animation: breathe 12s infinite ease-in-out;
  }

  /* Ocultar barra lateral automáticamente en esta vista para máxima calma */
  @media (min-width: 1024px) {
    aside { display: none !important; }
    main { width: 100% !important; margin-left: 0 !important; }
  }
</style>
HTML;

require_once '../includes/tailwind_header.php';
?>

<div class="h-[calc(100vh-160px)] w-full flex flex-col items-center justify-center animate-[fadeIn_1s_ease-out] relative max-w-4xl mx-auto">
  
  <div class="absolute top-4 left-4">
    <a href="dashboard.php" class="btn bg-theme_panel text-theme_text hover:text-theme_accent border border-theme_border rounded-full px-6 py-3 font-bold flex items-center gap-2 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent transition-all hover:scale-105">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>

  <div class="text-center mb-12 z-10">
    <h1 class="font-heading text-4xl md:text-5xl font-extrabold text-theme_text mb-4 tracking-tight">Mi Rincón Seguro</h1>
    <p class="text-theme_text_muted text-lg max-w-lg mx-auto font-medium">Tómate un respiro. Sincroniza tu respiración con la burbuja cósmica para recuperar tu energía.</p>
  </div>

  <!-- Burbuja de Respiración -->
  <div class="relative w-64 h-64 md:w-80 md:h-80 flex items-center justify-center mb-16 z-10">
    <!-- Círculos decorativos externos -->
    <div class="absolute inset-0 border-2 border-dashed border-emerald-500/20 rounded-full animate-[spin_30s_linear_infinite]"></div>
    <div class="absolute inset-4 border border-emerald-500/10 rounded-full animate-[spin_20s_linear_infinite_reverse]"></div>
    
    <!-- Burbuja animada (CSS breathe) -->
    <div class="breathe-circle w-48 h-48 md:w-64 md:h-64 rounded-full bg-gradient-to-tr from-emerald-400/20 to-cyan-400/20 border-2 border-emerald-400 shadow-[0_0_50px_rgba(52,211,153,0.4)] flex items-center justify-center backdrop-blur-sm">
      <div class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-gradient-to-tr from-emerald-500/40 to-cyan-500/40 blur-md animate-pulse"></div>
    </div>

    <!-- Indicador de texto dinámico via JS -->
    <div id="breathe-text" class="absolute font-heading text-2xl font-extrabold text-white text-shadow-md tracking-widest drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">
      INHALA
    </div>
  </div>

  <!-- Controles de Audio Relajante -->
  <div class="glass-panel p-6 rounded-3xl border border-theme_border shadow-xl flex items-center gap-8 z-10">
    <div class="flex flex-col">
      <span class="font-bold text-theme_text">Frecuencia de Calma</span>
      <span class="text-sm text-theme_text_muted">Ondas Alpha / Ruido Marrón</span>
    </div>
    
    <button id="toggle-audio" aria-label="Reproducir audio relajante" class="w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 border-2 border-emerald-500/50 flex items-center justify-center text-2xl hover:bg-emerald-500 hover:text-white transition-all duration-300 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500 hover:scale-110 hover:shadow-[0_0_20px_rgba(52,211,153,0.4)]">
      <i class="fas fa-play" id="audio-icon"></i>
    </button>
  </div>

</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Sincronizar texto con animación de 12s
    const textEl = document.getElementById('breathe-text');
    const cycle = () => {
      textEl.textContent = 'INHALA';
      setTimeout(() => { textEl.textContent = 'SOSTÉN'; }, 4000);
      setTimeout(() => { textEl.textContent = 'EXHALA'; }, 6000);
      setTimeout(() => { textEl.textContent = 'PAUSA'; }, 10000);
    };
    cycle();
    setInterval(cycle, 12000);

    // 2. Sintetizador de Web Audio API para Ruido Marrón continuo
    let audioCtx;
    let brownNoiseNode;
    let gainNode;
    let isPlaying = false;

    const toggleBtn = document.getElementById('toggle-audio');
    const icon = document.getElementById('audio-icon');

    const startAudio = () => {
      if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      
      const bufferSize = audioCtx.sampleRate * 2; // 2 seconds of noise
      const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
      const output = buffer.getChannelData(0);
      
      let lastOut = 0;
      for (let i = 0; i < bufferSize; i++) {
        let white = Math.random() * 2 - 1;
        output[i] = (lastOut + (0.02 * white)) / 1.02;
        lastOut = output[i];
        output[i] *= 3.5; // Compensate for gain loss
      }

      brownNoiseNode = audioCtx.createBufferSource();
      brownNoiseNode.buffer = buffer;
      brownNoiseNode.loop = true;

      // Lowpass filter para suavizar el ruido (sonido de océano/viento suave)
      const filter = audioCtx.createBiquadFilter();
      filter.type = 'lowpass';
      filter.frequency.value = 400; // Hz

      gainNode = audioCtx.createGain();
      gainNode.gain.value = 0.3; // Volumen suave

      brownNoiseNode.connect(filter);
      filter.connect(gainNode);
      gainNode.connect(audioCtx.destination);
      
      brownNoiseNode.start(0);
    };

    const stopAudio = () => {
      if (brownNoiseNode) {
        brownNoiseNode.stop();
        brownNoiseNode.disconnect();
      }
    };

    toggleBtn.addEventListener('click', () => {
      if (!isPlaying) {
        if (audioCtx && audioCtx.state === 'suspended') audioCtx.resume();
        startAudio();
        icon.classList.remove('fa-play');
        icon.classList.add('fa-pause');
        toggleBtn.classList.add('bg-emerald-500', 'text-white');
        isPlaying = true;
      } else {
        stopAudio();
        icon.classList.remove('fa-pause');
        icon.classList.add('fa-play');
        toggleBtn.classList.remove('bg-emerald-500', 'text-white');
        isPlaying = false;
      }
    });

    // Cierra el sidebar por defecto si existe, para no distraer
    if (window.EQ_UI) {
      setTimeout(() => EQ_UI.closeSidebar(), 100);
    }
  });
</script>

<?php require_once '../includes/tailwind_footer.php'; ?>
