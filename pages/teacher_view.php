<?php
// pages/teacher_view.php
// 1. Control de Acceso por Roles (Seguridad de Sesión)
if (!isset($userRole) || $userRole !== 'profesor') {
    // Bloquear acceso a estudiantes y redirigir
    header("Location: dashboard.php?error=" . urlencode("Acceso denegado. Área exclusiva para docentes."));
    exit;
}
?>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('animate-[fadeIn_0.3s_ease-out]');
    });
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('bg-blue-600/30', 'border-blue-500', 'text-white');
        el.classList.add('border-transparent', 'text-slate-400');
    });
    
    const content = document.getElementById(tabId);
    content.classList.remove('hidden');
    content.classList.add('animate-[fadeIn_0.3s_ease-out]');
    
    const btn = document.querySelector(`[onclick="switchTab('${tabId}')"]`);
    btn.classList.add('bg-blue-600/30', 'border-blue-500', 'text-white');
    btn.classList.remove('border-transparent', 'text-slate-400');
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
}
</script>

<div class="space-y-8 animate-[fadeIn_0.5s_ease-out]">
    
    <!-- Hero Section Profesor -->
    <div class="glass-panel rounded-3xl p-8 relative overflow-hidden border border-blue-500/30">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-purple-600/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white mb-2">Panel Docente, <?= htmlspecialchars($userName) ?></h1>
                <p class="text-slate-400">Gestiona tus aulas, crea contenidos interactivos y evalúa con ayuda de IA.</p>
            </div>
            <button onclick="openModal('modal-nueva-aula')" class="min-h-[48px] min-w-[48px] px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold shadow-[0_0_15px_rgba(59,130,246,0.4)] transition transform hover:scale-105 flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Crear Nueva Aula
            </button>
        </div>
    </div>

    <!-- Navegación por Pestañas (Grandes y accesibles) -->
    <div class="flex flex-wrap gap-2 border-b border-slate-700/50 pb-2">
        <button onclick="switchTab('tab-aulas')" class="tab-btn bg-blue-600/30 border-blue-500 text-white border-b-2 min-h-[48px] px-6 py-2 rounded-t-xl font-bold transition hover:bg-blue-600/20 flex items-center gap-2">
            <i class="fas fa-users"></i> Mis Aulas
        </button>
        <button onclick="switchTab('tab-contenidos')" class="tab-btn border-transparent text-slate-400 border-b-2 min-h-[48px] px-6 py-2 rounded-t-xl font-bold transition hover:text-white hover:bg-slate-800/50 flex items-center gap-2">
            <i class="fas fa-book-open"></i> Contenidos y Tareas
        </button>
        <button onclick="switchTab('tab-evaluaciones')" class="tab-btn border-transparent text-slate-400 border-b-2 min-h-[48px] px-6 py-2 rounded-t-xl font-bold transition hover:text-white hover:bg-slate-800/50 flex items-center gap-2">
            <i class="fas fa-check-double"></i> Evaluaciones y Quizzes
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TABS CONTENT -->
    <!-- ========================================== -->

    <!-- TAB 1: Mis Aulas -->
    <div id="tab-aulas" class="tab-content space-y-6 block">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Mock Aula Card 1 -->
            <div class="glass-panel p-6 rounded-2xl border border-slate-700/50 hover:border-blue-500/50 transition group cursor-pointer relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-blue-500/20 text-blue-400 rounded-xl">
                            <i class="fas fa-atom text-xl"></i>
                        </div>
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-bold rounded-full border border-green-500/30">Activa</span>
                    </div>
                    <h3 class="text-xl font-heading font-bold text-white mb-1">Física Cuántica Básica</h3>
                    <p class="text-sm text-slate-400 mb-4">Introducción a las partículas subatómicas.</p>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-slate-700 border-2 border-slate-900 flex justify-center items-center text-xs">👨‍🎓</div>
                            <div class="w-8 h-8 rounded-full bg-slate-700 border-2 border-slate-900 flex justify-center items-center text-xs">👩‍🎓</div>
                            <div class="w-8 h-8 rounded-full bg-slate-800 border-2 border-slate-900 flex justify-center items-center text-xs text-slate-400">+24</div>
                        </div>
                        <span class="text-blue-400 font-bold group-hover:text-blue-300">Gestionar <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </div>
            </div>

            <!-- Mock Aula Card 2 -->
            <div class="glass-panel p-6 rounded-2xl border border-slate-700/50 hover:border-purple-500/50 transition group cursor-pointer relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-purple-500/20 text-purple-400 rounded-xl">
                            <i class="fas fa-globe-americas text-xl"></i>
                        </div>
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-bold rounded-full border border-green-500/30">Activa</span>
                    </div>
                    <h3 class="text-xl font-heading font-bold text-white mb-1">Historia Universal II</h3>
                    <p class="text-sm text-slate-400 mb-4">Renacimiento a la era moderna.</p>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-slate-700 border-2 border-slate-900 flex justify-center items-center text-xs">🥷</div>
                            <div class="w-8 h-8 rounded-full bg-slate-800 border-2 border-slate-900 flex justify-center items-center text-xs text-slate-400">+15</div>
                        </div>
                        <span class="text-purple-400 font-bold group-hover:text-purple-300">Gestionar <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: Contenidos y Tareas -->
    <div id="tab-contenidos" class="tab-content hidden space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulario Subida -->
            <div class="lg:col-span-2 glass-panel p-6 rounded-2xl border border-slate-700/50">
                <h2 class="font-heading text-xl font-bold text-white mb-6 border-b border-slate-700/50 pb-4">Crear Nuevo Material</h2>
                <form class="space-y-5">
                    <div>
                        <label class="block text-slate-300 font-bold mb-2">Título de la Lección / Tarea</label>
                        <input type="text" class="w-full min-h-[48px] bg-slate-900/50 border border-slate-600 rounded-xl px-4 text-white focus:outline-none focus:border-blue-500 transition" placeholder="Ej: Las leyes de Newton">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-bold mb-2">Descripción o Contenido</label>
                        <textarea rows="5" class="w-full bg-slate-900/50 border border-slate-600 rounded-xl p-4 text-white focus:outline-none focus:border-blue-500 transition" placeholder="Redacta el contenido..."></textarea>
                    </div>
                    <div>
                        <label class="block text-slate-300 font-bold mb-2">Archivos Adjuntos (PDF, Videos)</label>
                        <div class="w-full min-h-[100px] border-2 border-dashed border-slate-600 rounded-xl flex flex-col items-center justify-center text-slate-500 hover:border-blue-500 hover:text-blue-400 transition cursor-pointer bg-slate-900/30">
                            <i class="fas fa-cloud-upload-alt text-3xl mb-2"></i>
                            <span>Arrastra tus archivos aquí o haz clic para subir</span>
                        </div>
                    </div>
                    <button type="button" class="w-full min-h-[48px] bg-green-600 hover:bg-green-500 text-white rounded-xl font-bold transition shadow-[0_0_15px_rgba(34,197,94,0.3)]">
                        Publicar Contenido
                    </button>
                </form>
            </div>
            
            <!-- AI Assistant Sidebar -->
            <div class="glass-panel p-6 rounded-2xl border border-purple-500/40 relative overflow-hidden bg-gradient-to-b from-purple-900/20 to-transparent">
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <i class="fas fa-robot text-6xl text-purple-400"></i>
                </div>
                <h2 class="font-heading text-xl font-bold text-purple-300 mb-4 flex items-center gap-2">
                    <i class="fas fa-magic"></i> Asistente IA
                </h2>
                <p class="text-sm text-slate-300 mb-6 relative z-10">Genera resúmenes, viñetas o simplifica textos complejos automáticamente para tus alumnos.</p>
                
                <div class="space-y-3 relative z-10">
                    <button class="w-full min-h-[48px] bg-purple-600/30 hover:bg-purple-600/50 border border-purple-500 text-white rounded-xl text-sm font-bold transition flex items-center gap-3 px-4">
                        <i class="fas fa-align-left text-purple-300"></i> Resumir texto actual
                    </button>
                    <button class="w-full min-h-[48px] bg-purple-600/30 hover:bg-purple-600/50 border border-purple-500 text-white rounded-xl text-sm font-bold transition flex items-center gap-3 px-4">
                        <i class="fas fa-list-ul text-purple-300"></i> Extraer ideas clave
                    </button>
                    <button class="w-full min-h-[48px] bg-purple-600/30 hover:bg-purple-600/50 border border-purple-500 text-white rounded-xl text-sm font-bold transition flex items-center gap-3 px-4">
                        <i class="fas fa-language text-purple-300"></i> Simplificar vocabulario
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: Evaluaciones y Quizzes -->
    <div id="tab-evaluaciones" class="tab-content hidden space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Creador de Quizzes -->
            <div class="lg:col-span-2 glass-panel p-6 rounded-2xl border border-slate-700/50">
                <div class="flex justify-between items-center mb-6 border-b border-slate-700/50 pb-4">
                    <h2 class="font-heading text-xl font-bold text-white">Creador de Evaluaciones</h2>
                    <button class="min-h-[48px] px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Añadir Pregunta
                    </button>
                </div>
                
                <!-- Mock Pregunta 1 -->
                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-5 mb-4">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-blue-400 font-bold">Pregunta 1</span>
                        <button class="text-red-400 hover:text-red-300 p-2"><i class="fas fa-trash"></i></button>
                    </div>
                    <input type="text" class="w-full min-h-[48px] bg-slate-800 border border-slate-600 rounded-xl px-4 text-white mb-4 focus:outline-none focus:border-blue-500" value="¿Cuál es la capital de Francia?">
                    
                    <div class="space-y-2 pl-4 border-l-2 border-slate-700">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="q1" class="w-5 h-5 text-blue-500 bg-slate-800 border-slate-600">
                            <input type="text" class="w-full min-h-[40px] bg-slate-800/50 border border-slate-700 rounded-lg px-3 text-sm text-slate-300 focus:outline-none" value="Madrid">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="radio" name="q1" checked class="w-5 h-5 text-blue-500 bg-slate-800 border-slate-600">
                            <input type="text" class="w-full min-h-[40px] bg-slate-800/50 border border-green-500/50 rounded-lg px-3 text-sm text-white focus:outline-none" value="París">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="radio" name="q1" class="w-5 h-5 text-blue-500 bg-slate-800 border-slate-600">
                            <input type="text" class="w-full min-h-[40px] bg-slate-800/50 border border-slate-700 rounded-lg px-3 text-sm text-slate-300 focus:outline-none" value="Berlín">
                        </div>
                    </div>
                </div>
                
                <button type="button" class="w-full min-h-[48px] bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition shadow-[0_0_15px_rgba(59,130,246,0.3)] mt-4">
                    Guardar Quiz
                </button>
            </div>
            
            <!-- AI Quiz Generator -->
            <div class="glass-panel p-6 rounded-2xl border border-orange-500/40 relative overflow-hidden bg-gradient-to-b from-orange-900/20 to-transparent">
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <i class="fas fa-brain text-6xl text-orange-400"></i>
                </div>
                <h2 class="font-heading text-xl font-bold text-orange-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-magic"></i> Auto-Generador IA
                </h2>
                <p class="text-sm text-slate-300 mb-6 relative z-10">Genera preguntas de opción múltiple automáticamente basadas en el temario o un texto proporcionado.</p>
                
                <div class="space-y-4 relative z-10">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Tema o Texto Fuente</label>
                        <textarea rows="3" class="w-full text-sm bg-slate-900/80 border border-orange-500/30 rounded-xl p-3 text-white focus:outline-none focus:border-orange-500" placeholder="Ej: La revolución industrial y sus consecuencias..."></textarea>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-400 mb-1">Cantidad</label>
                            <input type="number" value="5" min="1" max="20" class="w-full min-h-[40px] bg-slate-900/80 border border-orange-500/30 rounded-xl px-3 text-white">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-400 mb-1">Dificultad</label>
                            <select class="w-full min-h-[40px] bg-slate-900/80 border border-orange-500/30 rounded-xl px-2 text-white">
                                <option>Fácil</option>
                                <option selected>Media</option>
                                <option>Difícil</option>
                            </select>
                        </div>
                    </div>
                    <button class="w-full min-h-[48px] bg-orange-600 hover:bg-orange-500 text-white rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 mt-2 shadow-[0_0_10px_rgba(249,115,22,0.3)]">
                        <i class="fas fa-cogs"></i> Generar Preguntas
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODALS -->
<!-- ========================================== -->

<!-- Modal: Nueva Aula -->
<div id="modal-nueva-aula" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/80 backdrop-blur-sm px-4">
    <div class="glass-panel w-full max-w-lg rounded-3xl border border-blue-500/40 p-8 shadow-2xl relative overflow-hidden animate-[fadeIn_0.2s_ease-out]">
        <!-- Deco -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600/20 rounded-full blur-2xl"></div>
        
        <div class="flex justify-between items-center mb-6 relative z-10">
            <h2 class="font-heading text-2xl font-bold text-white">Crear Nueva Aula</h2>
            <button onclick="closeModal('modal-nueva-aula')" class="text-slate-400 hover:text-white p-2 min-h-[48px] min-w-[48px] flex items-center justify-center transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form class="space-y-5 relative z-10" onsubmit="event.preventDefault(); closeModal('modal-nueva-aula'); /* Aquí iría el submit real */">
            <div>
                <label class="block text-slate-300 font-bold mb-2">Nombre del Aula</label>
                <input type="text" required class="w-full min-h-[48px] bg-slate-900/70 border border-slate-600 rounded-xl px-4 text-white focus:outline-none focus:border-blue-500 transition text-lg" placeholder="Ej: Matemáticas Avanzadas 101">
            </div>
            <div>
                <label class="block text-slate-300 font-bold mb-2">Descripción (Opcional)</label>
                <textarea rows="3" class="w-full bg-slate-900/70 border border-slate-600 rounded-xl p-4 text-white focus:outline-none focus:border-blue-500 transition" placeholder="Breve descripción del curso..."></textarea>
            </div>
            <div>
                <label class="block text-slate-300 font-bold mb-2">Nivel / Grado</label>
                <select class="w-full min-h-[48px] bg-slate-900/70 border border-slate-600 rounded-xl px-4 text-white focus:outline-none focus:border-blue-500 transition text-lg appearance-none">
                    <option>Básico</option>
                    <option>Intermedio</option>
                    <option>Avanzado</option>
                </select>
            </div>
            
            <div class="pt-4 border-t border-slate-700/50 flex gap-4">
                <button type="button" onclick="closeModal('modal-nueva-aula')" class="flex-1 min-h-[48px] bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white rounded-xl font-bold transition">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 min-h-[48px] bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition shadow-[0_0_15px_rgba(59,130,246,0.4)]">
                    Crear Aula
                </button>
            </div>
        </form>
    </div>
</div>
