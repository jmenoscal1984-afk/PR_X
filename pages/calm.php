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

<main class="h-[calc(100vh-100px)] w-full flex flex-col items-center justify-center animate-[fadeIn_1s_ease-out] relative max-w-[90rem] mx-auto px-4 lg:px-12" x-data="{
    modoResp: 'caja',
    audioActivo: 'alpha',
    fraseIndex: 0,
    frases: [
        'El espacio es vasto, al igual que tu capacidad de aprender.',
        'La quietud de la mente es como el vacío del cosmos: pura calma.',
        'Respira profundo. Cada exhalación suelta la presión de la gravedad.',
        'No hay errores, solo datos de navegación para tu siguiente viaje.'
    ],
    nextFrase() { this.fraseIndex = (this.fraseIndex + 1) % this.frases.length; },
    textoFrustracion: '',
    liberado: false,
    liberar() {
        if(this.textoFrustracion.trim() !== '') {
            this.textoFrustracion = '';
            this.liberado = true;
            setTimeout(() => { this.liberado = false; }, 3000);
        }
    }
}">
    
  <!-- Layout Principal (3 columnas) -->
  <div class="flex flex-col lg:flex-row items-center justify-between w-full gap-8 lg:gap-12 z-10 flex-1 py-12">
    
    <!-- PANEL IZQUIERDO: Estación Ambiental -->
    <div class="w-full lg:w-72 opacity-40 hover:opacity-100 transition-opacity duration-500 bg-[#1E293B]/40 backdrop-blur-md border border-white/5 p-6 rounded-3xl shadow-xl flex flex-col gap-4 order-2 lg:order-1">
      <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest text-center mb-2">Paisajes Sonoros</h3>
      
      <button @click="audioActivo = 'alpha'" :class="audioActivo === 'alpha' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30 shadow-[0_0_15px_rgba(59,130,246,0.2)]' : 'bg-transparent text-slate-400 border-white/5 hover:bg-white/5'" class="w-full py-3.5 px-4 rounded-xl border flex items-center gap-3 text-sm font-medium transition-all">
        <i class="fas fa-brain w-5"></i> Frecuencia Alpha
      </button>
      
      <button @click="audioActivo = 'lluvia'" :class="audioActivo === 'lluvia' ? 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.2)]' : 'bg-transparent text-slate-400 border-white/5 hover:bg-white/5'" class="w-full py-3.5 px-4 rounded-xl border flex items-center gap-3 text-sm font-medium transition-all">
        <i class="fas fa-cloud-rain w-5"></i> Lluvia Estelar
      </button>

      <button @click="audioActivo = 'ruido'" :class="audioActivo === 'ruido' ? 'bg-purple-500/20 text-purple-400 border-purple-500/30 shadow-[0_0_15px_rgba(168,85,247,0.2)]' : 'bg-transparent text-slate-400 border-white/5 hover:bg-white/5'" class="w-full py-3.5 px-4 rounded-xl border flex items-center gap-3 text-sm font-medium transition-all">
        <i class="fas fa-water w-5"></i> Ruido Blanco
      </button>
    </div>

    <!-- ZONA CENTRAL: La Burbuja -->
    <div class="flex-1 flex flex-col items-center justify-center relative order-1 lg:order-2 w-full max-w-lg min-h-[400px]">
      
      <!-- Selector de Modo -->
      <div class="absolute top-0 opacity-40 hover:opacity-100 transition-opacity duration-500 flex gap-2 p-1.5 bg-black/40 rounded-full border border-white/10 z-20">
        <button @click="modoResp = 'caja'" :class="modoResp === 'caja' ? 'bg-white/15 text-white' : 'text-slate-500 hover:text-slate-300'" class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all">Caja</button>
        <button @click="modoResp = '478'" :class="modoResp === '478' ? 'bg-white/15 text-white' : 'text-slate-500 hover:text-slate-300'" class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all">4-7-8</button>
      </div>

      <!-- Burbuja -->
      <div class="relative w-72 h-72 md:w-96 md:h-96 flex items-center justify-center mt-12">
        <!-- Círculos decorativos -->
        <div class="absolute inset-0 border-2 border-dashed border-cyan-500/20 rounded-full animate-[spin_30s_linear_infinite]"></div>
        <div class="absolute inset-6 border border-cyan-500/10 rounded-full animate-[spin_20s_linear_infinite_reverse]"></div>
        
        <!-- CSS Animado de respiración -->
        <div class="breathe-circle w-56 h-56 md:w-72 md:h-72 rounded-full bg-gradient-to-tr from-cyan-400/10 to-blue-500/10 border border-cyan-400/40 shadow-[0_0_60px_rgba(34,211,238,0.2)] flex items-center justify-center backdrop-blur-sm">
          <div class="w-40 h-40 md:w-56 md:h-56 rounded-full bg-gradient-to-tr from-cyan-500/30 to-blue-500/30 blur-xl animate-pulse"></div>
        </div>

        <div id="breathe-text" class="absolute font-heading text-3xl md:text-4xl font-black text-white tracking-widest drop-shadow-[0_0_15px_rgba(255,255,255,0.8)]">
          INHALA
        </div>
      </div>
    </div>

    <!-- PANEL DERECHO: Citas de Anclaje -->
    <div class="w-full lg:w-72 opacity-40 hover:opacity-100 transition-opacity duration-500 bg-[#1E293B]/40 backdrop-blur-md border border-white/5 p-8 rounded-3xl shadow-xl flex flex-col justify-center text-center order-3 min-h-[200px]">
      <i class="fas fa-quote-left text-3xl text-white/10 mb-4"></i>
      <p class="text-sm text-slate-300 italic font-medium leading-relaxed" x-text="frases[fraseIndex]" x-transition></p>
      <button @click="nextFrase()" class="mt-6 text-xs text-slate-500 hover:text-white transition-colors uppercase tracking-widest font-bold flex items-center justify-center gap-2 mx-auto">
        <i class="fas fa-sync-alt"></i> Siguiente
      </button>
    </div>

  </div>

  <!-- ZONA INFERIOR: Cápsula de Liberación -->
  <div class="w-full max-w-2xl opacity-40 hover:opacity-100 transition-opacity duration-500 z-10 pb-8">
    <div class="relative flex items-center">
      <input type="text" x-model="textoFrustracion" @keydown.enter="liberar()" placeholder="¿Qué te frustra en este momento? Escríbelo y suéltalo..." class="w-full bg-black/40 border border-white/10 text-white rounded-full pl-8 pr-44 py-4 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 transition-all placeholder-slate-600 text-sm shadow-inner">
      <button @click="liberar()" class="absolute right-2 bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white text-xs font-bold px-6 py-2.5 rounded-full transition-all shadow-[0_0_15px_rgba(168,85,247,0.4)]">
        Soltar al espacio
      </button>
    </div>
    <!-- Mensaje de éxito -->
    <div x-show="liberado" x-transition.opacity class="text-center mt-3 text-purple-400 text-xs font-bold tracking-widest uppercase h-4">
      <i class="fas fa-wind mr-1"></i> Liberado en el vacío cósmico.
    </div>
  </div>

  <!-- Botón Volver -->
  <div class="absolute top-6 left-6 opacity-40 hover:opacity-100 transition-opacity duration-500 z-50">
    <a href="dashboard.php" class="bg-black/40 text-slate-300 hover:text-white border border-white/10 rounded-full px-5 py-2.5 text-sm font-bold flex items-center gap-2 transition-all hover:bg-black/60">
      <i class="fas fa-arrow-left"></i> Volver al panel
    </a>
  </div>

</main>

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
