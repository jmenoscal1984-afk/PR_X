<?php
// pages/student_view.php
if (!isset($userRole) || $userRole !== 'alumno') exit;
?>
<!-- Contenedor principal con diseño EduQuest (Modular, Analítico y Lúdico) -->
<div x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)" class="space-y-8 animate-[fadeIn_0.5s_ease-out] w-full max-w-[1400px] mx-auto">

    <!-- 1. HERO CARD (Bienvenida y Avatar) -->
    <section class="glass-panel rounded-3xl p-8 lg:p-10 relative overflow-hidden border border-theme_border shadow-lg bg-theme_panel flex flex-col md:flex-row items-center justify-between gap-8 focus-within:ring-4 focus-within:ring-theme_accent">
        <!-- Contenido Izquierdo -->
        <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left z-10">
            <div class="flex items-center gap-4 mb-3">
                <h1 class="font-heading text-3xl lg:text-4xl font-extrabold text-theme_text tracking-tight">¡Hola, <?= htmlspecialchars($userName) ?>! 👋</h1>
                <button type="button" aria-label="Leer saludo en voz alta" onclick="window.speechSynthesis.cancel(); let u = new SpeechSynthesisUtterance('¡Hola, <?= htmlspecialchars($userName) ?>! Tienes 5 materias activas hoy. Sigue así.'); u.rate = 0.9; window.speechSynthesis.speak(u);" class="w-10 h-10 bg-theme_bg text-theme_text hover:text-white hover:bg-theme_accent focus:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent rounded-full flex items-center justify-center text-lg transition-colors border border-theme_border shadow-sm" title="Escuchar">
                    <i class="fas fa-volume-up"></i>
                </button>
            </div>
            <p class="text-lg text-theme_text_muted font-medium mb-6">Tu aventura de aprendizaje continúa. Tienes 5 materias activas hoy.</p>
            
            <div class="flex items-center gap-6 bg-theme_bg/50 p-4 rounded-2xl border border-theme_border/50">
                <div>
                    <p class="text-sm text-theme_text_muted font-bold uppercase tracking-wider mb-1">Nivel Actual</p>
                    <p class="text-2xl font-extrabold text-theme_accent"><?= htmlspecialchars($userLevel) ?></p>
                </div>
                <div class="w-px h-12 bg-theme_border"></div>
                <div>
                    <p class="text-sm text-theme_text_muted font-bold uppercase tracking-wider mb-1">Experiencia</p>
                    <p class="text-2xl font-extrabold text-theme_text"><?= $userXP ?> <span class="text-sm text-theme_text_muted">XP</span></p>
                </div>
            </div>
        </div>
        
        <!-- Avatar / Ilustración Derecha -->
        <div class="relative z-10 shrink-0 w-32 h-32 md:w-40 md:h-40 bg-theme_accent/10 rounded-full border-4 border-theme_accent/30 shadow-[0_0_30px_rgba(var(--accent),0.2)] flex items-center justify-center text-6xl md:text-7xl group cursor-pointer hover:bg-theme_accent/20 transition-all duration-300">
            <?= htmlspecialchars($userAvatar) ?>
            <div class="absolute -bottom-2 -right-2 bg-theme_bg border-2 border-theme_border p-2 rounded-full shadow-lg">
                <i class="fas fa-star text-yellow-500 animate-pulse text-xl"></i>
            </div>
        </div>
        
        <!-- Elemento decorativo de fondo -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-theme_accent/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
    </section>

    <!-- 2. FILA DE MÉTRICAS (Stat Cards) -->
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1: Quizzes -->
        <button tabindex="0" class="glass-panel p-6 rounded-2xl border border-theme_border shadow-sm flex items-center gap-5 hover:-translate-y-1 hover:border-theme_accent/50 transition-all duration-300 focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none group text-left">
            <div class="w-14 h-14 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-tasks"></i>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-theme_text leading-none mb-1">15</p>
                <p class="text-sm text-theme_text_muted font-bold uppercase tracking-wider">Quizzes</p>
            </div>
        </button>
        <!-- Stat 2: Precisión -->
        <button tabindex="0" class="glass-panel p-6 rounded-2xl border border-theme_border shadow-sm flex items-center gap-5 hover:-translate-y-1 hover:border-theme_accent/50 transition-all duration-300 focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none group text-left">
            <div class="w-14 h-14 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-bullseye"></i>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-theme_text leading-none mb-1">92%</p>
                <p class="text-sm text-theme_text_muted font-bold uppercase tracking-wider">Precisión</p>
            </div>
        </button>
        <!-- Stat 3: Racha -->
        <button tabindex="0" class="glass-panel p-6 rounded-2xl border border-theme_border shadow-sm flex items-center gap-5 hover:-translate-y-1 hover:border-theme_accent/50 transition-all duration-300 focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none group text-left">
            <div class="w-14 h-14 rounded-full bg-orange-500/20 text-orange-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-fire animate-pulse"></i>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-theme_text leading-none mb-1">5</p>
                <p class="text-sm text-theme_text_muted font-bold uppercase tracking-wider">Días Racha</p>
            </div>
        </button>
        <!-- Stat 4: Logros -->
        <button tabindex="0" class="glass-panel p-6 rounded-2xl border border-theme_border shadow-sm flex items-center gap-5 hover:-translate-y-1 hover:border-theme_accent/50 transition-all duration-300 focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none group text-left">
            <div class="w-14 h-14 rounded-full bg-purple-500/20 text-purple-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-medal"></i>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-theme_text leading-none mb-1">8</p>
                <p class="text-sm text-theme_text_muted font-bold uppercase tracking-wider">Logros</p>
            </div>
        </button>
    </section>

    <!-- 3. ACCESO RÁPIDO A MATERIAS (Grid de 5) -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-heading text-2xl font-extrabold text-theme_text flex items-center gap-3">
                <i class="fas fa-layer-group text-theme_accent"></i> Materias
            </h2>
            <button type="button" aria-label="Leer materias en voz alta" onclick="window.speechSynthesis.cancel(); let u = new SpeechSynthesisUtterance('Tus materias son: Matemática al 85%, Lenguaje al 60%, Física al 50%, Química al 70%, y Biología al 40%.'); u.rate = 0.9; window.speechSynthesis.speak(u);" class="w-10 h-10 bg-theme_panel text-theme_text hover:text-theme_bg hover:bg-theme_accent focus:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent rounded-full flex items-center justify-center text-sm transition-colors border border-theme_border">
                <i class="fas fa-volume-up"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-6">
            
            <!-- Materia 1: Matemática (Azul) -->
            <button tabindex="0" class="relative bg-theme_panel border border-theme_border p-6 rounded-3xl hover:border-blue-500/50 hover:shadow-[0_0_30px_rgba(59,130,246,0.15)] transition-all duration-300 focus-visible:ring-4 focus-visible:ring-blue-500 focus-visible:outline-none group text-left flex flex-col justify-between min-h-[200px]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/20 text-blue-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-square-root-alt"></i>
                    </div>
                    <span class="text-xs font-bold bg-theme_bg text-theme_text px-2 py-1 rounded-md border border-theme_border">+500 XP</span>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-theme_text mb-4">Matemática</h3>
                    <div class="flex justify-between items-end mb-2 text-sm">
                        <span class="text-theme_text_muted font-medium">Progreso</span>
                        <span class="font-extrabold text-blue-500">85%</span>
                    </div>
                    <div class="w-full h-2 bg-theme_bg rounded-full overflow-hidden border border-theme_border/50">
                        <div class="h-full bg-blue-500 rounded-full transition-all duration-1000 ease-out" :style="'width: ' + (mounted ? '85%' : '0%')"></div>
                    </div>
                </div>
            </button>

            <!-- Materia 2: Lenguaje (Fucsia/Morado) -->
            <button tabindex="0" class="relative bg-theme_panel border border-theme_border p-6 rounded-3xl hover:border-fuchsia-500/50 hover:shadow-[0_0_30px_rgba(217,70,239,0.15)] transition-all duration-300 focus-visible:ring-4 focus-visible:ring-fuchsia-500 focus-visible:outline-none group text-left flex flex-col justify-between min-h-[200px]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-fuchsia-500/20 text-fuchsia-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-language"></i>
                    </div>
                    <span class="text-xs font-bold bg-theme_bg text-theme_text px-2 py-1 rounded-md border border-theme_border">+350 XP</span>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-theme_text mb-4">Lenguaje</h3>
                    <div class="flex justify-between items-end mb-2 text-sm">
                        <span class="text-theme_text_muted font-medium">Progreso</span>
                        <span class="font-extrabold text-fuchsia-500">60%</span>
                    </div>
                    <div class="w-full h-2 bg-theme_bg rounded-full overflow-hidden border border-theme_border/50">
                        <div class="h-full bg-fuchsia-500 rounded-full transition-all duration-1000 ease-out" :style="'width: ' + (mounted ? '60%' : '0%')"></div>
                    </div>
                </div>
            </button>

            <!-- Materia 3: Física (Naranja) -->
            <button tabindex="0" class="relative bg-theme_panel border border-theme_border p-6 rounded-3xl hover:border-orange-500/50 hover:shadow-[0_0_30px_rgba(249,115,22,0.15)] transition-all duration-300 focus-visible:ring-4 focus-visible:ring-orange-500 focus-visible:outline-none group text-left flex flex-col justify-between min-h-[200px]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-orange-500/20 text-orange-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-atom"></i>
                    </div>
                    <span class="text-xs font-bold bg-theme_bg text-theme_text px-2 py-1 rounded-md border border-theme_border">+200 XP</span>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-theme_text mb-4">Física</h3>
                    <div class="flex justify-between items-end mb-2 text-sm">
                        <span class="text-theme_text_muted font-medium">Progreso</span>
                        <span class="font-extrabold text-orange-500">50%</span>
                    </div>
                    <div class="w-full h-2 bg-theme_bg rounded-full overflow-hidden border border-theme_border/50">
                        <div class="h-full bg-orange-500 rounded-full transition-all duration-1000 ease-out" :style="'width: ' + (mounted ? '50%' : '0%')"></div>
                    </div>
                </div>
            </button>

            <!-- Materia 4: Química (Esmeralda) -->
            <button tabindex="0" class="relative bg-theme_panel border border-theme_border p-6 rounded-3xl hover:border-emerald-500/50 hover:shadow-[0_0_30px_rgba(16,185,129,0.15)] transition-all duration-300 focus-visible:ring-4 focus-visible:ring-emerald-500 focus-visible:outline-none group text-left flex flex-col justify-between min-h-[200px]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-flask"></i>
                    </div>
                    <span class="text-xs font-bold bg-theme_bg text-theme_text px-2 py-1 rounded-md border border-theme_border">+400 XP</span>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-theme_text mb-4">Química</h3>
                    <div class="flex justify-between items-end mb-2 text-sm">
                        <span class="text-theme_text_muted font-medium">Progreso</span>
                        <span class="font-extrabold text-emerald-500">70%</span>
                    </div>
                    <div class="w-full h-2 bg-theme_bg rounded-full overflow-hidden border border-theme_border/50">
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000 ease-out" :style="'width: ' + (mounted ? '70%' : '0%')"></div>
                    </div>
                </div>
            </button>

            <!-- Materia 5: Biología (Rosado) -->
            <button tabindex="0" class="relative bg-theme_panel border border-theme_border p-6 rounded-3xl hover:border-rose-500/50 hover:shadow-[0_0_30px_rgba(244,63,94,0.15)] transition-all duration-300 focus-visible:ring-4 focus-visible:ring-rose-500 focus-visible:outline-none group text-left flex flex-col justify-between min-h-[200px]">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-rose-500/20 text-rose-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-dna"></i>
                    </div>
                    <span class="text-xs font-bold bg-theme_bg text-theme_text px-2 py-1 rounded-md border border-theme_border">+150 XP</span>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-theme_text mb-4">Biología</h3>
                    <div class="flex justify-between items-end mb-2 text-sm">
                        <span class="text-theme_text_muted font-medium">Progreso</span>
                        <span class="font-extrabold text-rose-500">40%</span>
                    </div>
                    <div class="w-full h-2 bg-theme_bg rounded-full overflow-hidden border border-theme_border/50">
                        <div class="h-full bg-rose-500 rounded-full transition-all duration-1000 ease-out" :style="'width: ' + (mounted ? '40%' : '0%')"></div>
                    </div>
                </div>
            </button>

        </div>
    </section>

    <!-- 4. FILA INFERIOR (Insignias y Actividad) -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Columna 1: Insignias Recientes -->
        <div class="glass-panel p-8 rounded-[2rem] border border-theme_border shadow-lg flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-heading text-2xl font-extrabold text-theme_text flex items-center gap-3">
                    <i class="fas fa-medal text-theme_accent"></i> Insignias Recientes
                </h3>
                <button class="text-sm text-theme_accent font-bold hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-theme_accent rounded px-2">Ver Todas</button>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 gap-4 flex-1 content-start">
                <!-- Insignia 1 -->
                <div tabindex="0" class="flex flex-col items-center justify-center p-4 bg-theme_bg rounded-2xl border border-theme_border hover:bg-theme_panel transition focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none cursor-pointer group">
                    <i class="fas fa-trophy text-4xl text-yellow-500 mb-2 group-hover:scale-110 transition"></i>
                    <span class="text-xs text-theme_text_muted font-bold text-center">Primer 100%</span>
                </div>
                <!-- Insignia 2 -->
                <div tabindex="0" class="flex flex-col items-center justify-center p-4 bg-theme_bg rounded-2xl border border-theme_border hover:bg-theme_panel transition focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none cursor-pointer group">
                    <i class="fas fa-fire text-4xl text-orange-500 mb-2 group-hover:scale-110 transition"></i>
                    <span class="text-xs text-theme_text_muted font-bold text-center">Racha x5</span>
                </div>
                <!-- Insignia 3 -->
                <div tabindex="0" class="flex flex-col items-center justify-center p-4 bg-theme_bg rounded-2xl border border-theme_border hover:bg-theme_panel transition focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none cursor-pointer group">
                    <i class="fas fa-bolt text-4xl text-blue-500 mb-2 group-hover:scale-110 transition"></i>
                    <span class="text-xs text-theme_text_muted font-bold text-center">Veloz</span>
                </div>
                <!-- Insignia Vacia -->
                <div class="flex flex-col items-center justify-center p-4 bg-theme_bg/30 rounded-2xl border border-theme_border border-dashed opacity-50">
                    <i class="fas fa-lock text-2xl text-theme_text_muted mb-2"></i>
                    <span class="text-xs text-theme_text_muted font-bold text-center">Bloqueado</span>
                </div>
            </div>
        </div>

        <!-- Columna 2: Actividad Reciente -->
        <div class="glass-panel p-8 rounded-[2rem] border border-theme_border shadow-lg flex flex-col">
            <h3 class="font-heading text-2xl font-extrabold text-theme_text flex items-center gap-3 mb-8">
                <i class="fas fa-history text-theme_accent"></i> Actividad Reciente
            </h3>
            
            <div class="space-y-6 flex-1">
                <!-- Ítem 1 -->
                <div tabindex="0" class="flex gap-4 group focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent rounded-xl p-2 -m-2 transition">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center shrink-0">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <p class="text-theme_text font-bold text-lg mb-1 group-hover:text-theme_accent transition">Completaste "Ecuaciones Básicas"</p>
                        <p class="text-theme_text_muted text-sm">Hace 2 horas • <span class="text-yellow-500 font-bold">+50 XP</span></p>
                    </div>
                </div>
                
                <!-- Ítem 2 -->
                <div tabindex="0" class="flex gap-4 group focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent rounded-xl p-2 -m-2 transition">
                    <div class="w-12 h-12 rounded-full bg-purple-500/20 text-purple-500 flex items-center justify-center shrink-0">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div>
                        <p class="text-theme_text font-bold text-lg mb-1 group-hover:text-theme_accent transition">Obtuviste la insignia "Veloz"</p>
                        <p class="text-theme_text_muted text-sm">Hace 1 día</p>
                    </div>
                </div>
                
                <!-- Ítem 3 -->
                <div tabindex="0" class="flex gap-4 group focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-theme_accent rounded-xl p-2 -m-2 transition">
                    <div class="w-12 h-12 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center shrink-0">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div>
                        <p class="text-theme_text font-bold text-lg mb-1 group-hover:text-theme_accent transition">Empezaste lectura "El Origen"</p>
                        <p class="text-theme_text_muted text-sm">Hace 2 días</p>
                    </div>
                </div>
            </div>
            
            <button class="mt-6 w-full py-3 bg-theme_bg hover:bg-theme_panel text-theme_text_muted hover:text-theme_text font-bold rounded-xl transition border border-theme_border focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none">
                Ver historial completo
            </button>
        </div>

    </section>

</div>
