<?php
$require_auth = true;
$require_teacher = true;
require_once __DIR__ . '/../includes/auth_middleware.php';

$page_title = 'Panel Avanzado de Profesor — EduQuest';
require_once __DIR__ . '/../includes/tailwind_header.php';
?>

<!-- Estilos Deep Space Específicos para el Teacher View -->
<style>
  body {
    background-color: #0f172a;
    background-image: radial-gradient(circle at top right, #1e1b4b 0%, #0f172a 100%);
    color: #f8fafc;
  }
  .glass-panel {
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(168, 85, 247, 0.3);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
  }
  .tab-btn {
    transition: all 0.3s ease;
  }
  .tab-btn.active {
    background: rgba(168, 85, 247, 0.2);
    border-bottom: 2px solid #c084fc;
    color: #e879f9;
    box-shadow: inset 0 -4px 10px rgba(168, 85, 247, 0.2);
  }
</style>

<script>
function switchTab(tabId) {
    // Ocultar todos los contenidos
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('animate-[fadeIn_0.3s_ease-out]');
    });
    // Quitar clase active de los botones
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active', 'text-purple-300');
        el.classList.add('text-slate-400', 'border-transparent');
    });
    
    // Mostrar tab activo
    const content = document.getElementById(tabId);
    content.classList.remove('hidden');
    content.classList.add('animate-[fadeIn_0.3s_ease-out]');
    
    // Marcar botón activo
    const btn = document.querySelector(`[onclick="switchTab('${tabId}')"]`);
    btn.classList.add('active', 'text-purple-300');
    btn.classList.remove('text-slate-400', 'border-transparent');
}
</script>

<div class="max-w-[1400px] mx-auto p-4 md:p-8 space-y-8 animate-[fadeIn_0.5s_ease-out] relative z-10">
    
    <!-- Hero Section Profesor Deep Space -->
    <div class="glass-panel rounded-3xl p-8 relative overflow-hidden border border-purple-500/40 shadow-[0_0_20px_rgba(168,85,247,0.2)]">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-purple-600/30 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 text-purple-400 mb-2">
                    <i class="fas fa-user-astronaut text-3xl"></i>
                    <h1 class="font-heading text-3xl md:text-4xl font-bold text-white tracking-wide">Comando Central</h1>
                </div>
                <p class="text-slate-300 text-lg">Bienvenido de vuelta, <?= htmlspecialchars($_SESSION['user_full_name'] ?? 'Comandante') ?>. Dirige tus flotas de aprendizaje.</p>
            </div>
        </div>
    </div>

    <!-- NAVEGACIÓN TABBED UI -->
    <div class="flex flex-wrap gap-2 border-b border-slate-700/50 pb-0">
        <button onclick="switchTab('tab-monitoreo')" class="tab-btn active text-purple-300 px-6 py-4 rounded-t-xl font-bold text-sm md:text-base flex items-center gap-2 border-b-2">
            <i class="fas fa-chart-pie"></i> Monitoreo del Curso
        </button>
        <button onclick="switchTab('tab-misiones')" class="tab-btn text-slate-400 border-transparent px-6 py-4 rounded-t-xl font-bold text-sm md:text-base flex items-center gap-2 hover:bg-slate-800/50 border-b-2">
            <i class="fas fa-rocket"></i> Crear Misiones
        </button>
        <button onclick="switchTab('tab-quizzes')" class="tab-btn text-slate-400 border-transparent px-6 py-4 rounded-t-xl font-bold text-sm md:text-base flex items-center gap-2 hover:bg-slate-800/50 border-b-2">
            <i class="fas fa-gamepad"></i> Creador Quizzes
        </button>
        <button onclick="switchTab('tab-tareas')" class="tab-btn text-slate-400 border-transparent px-6 py-4 rounded-t-xl font-bold text-sm md:text-base flex items-center gap-2 hover:bg-slate-800/50 border-b-2">
            <i class="fas fa-calendar-check"></i> Gestión de Tareas
        </button>
    </div>

    <!-- ========================================== -->
    <!-- CONTENIDO DE LAS PESTAÑAS -->
    <!-- ========================================== -->

    <!-- TAB 1: 📊 Monitoreo del Curso -->
    <div id="tab-monitoreo" class="tab-content block space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-panel p-6 rounded-2xl flex items-center justify-between border-l-4 border-blue-500 hover:scale-105 transition transform">
                <div>
                    <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Cadetes Activos</p>
                    <h3 class="text-3xl font-bold text-white mt-1">128</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            
            <div class="glass-panel p-6 rounded-2xl flex items-center justify-between border-l-4 border-green-500 hover:scale-105 transition transform">
                <div>
                    <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Misiones Completadas</p>
                    <h3 class="text-3xl font-bold text-white mt-1">45</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center text-xl">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            
            <div class="glass-panel p-6 rounded-2xl flex items-center justify-between border-l-4 border-yellow-500 hover:scale-105 transition transform relative overflow-hidden">
                <div class="absolute inset-0 bg-yellow-500/5 animate-pulse"></div>
                <div class="relative z-10">
                    <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Promedio de Puntos (En Vivo)</p>
                    <h3 id="live-avg-points" class="text-3xl font-bold text-yellow-400 mt-1">Calculando...</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-500/20 text-yellow-400 flex items-center justify-center text-xl relative z-10">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-panel p-6 rounded-2xl">
            <h3 class="text-xl font-bold text-white mb-4 border-b border-slate-700/50 pb-2">Últimas Actividades (Tiempo Real)</h3>
            <ul id="live-activity-feed" class="space-y-3">
                <li class="text-slate-400 text-sm italic">Esperando actualizaciones del escuadrón...</li>
            </ul>
        </div>
    </div>

    <!-- TAB 2: 🚀 Crear y Editar Misiones -->
    <div id="tab-misiones" class="tab-content hidden space-y-6">
        <div class="glass-panel p-8 rounded-2xl">
            <h2 class="text-2xl font-bold text-purple-300 mb-6 border-b border-slate-700/50 pb-2 flex items-center gap-2">
                <i class="fas fa-rocket"></i> Nueva Misión Teórica
            </h2>
            <form class="space-y-6" onsubmit="event.preventDefault();">
                <div>
                    <label class="block text-slate-300 font-bold mb-2">Título de la Misión</label>
                    <input type="text" class="w-full bg-slate-900/50 border border-slate-600 rounded-xl p-4 text-white focus:outline-none focus:border-purple-500 transition" placeholder="Ej: Operación: Sistema Solar">
                </div>
                <div>
                    <label class="block text-slate-300 font-bold mb-2">Contenido Teórico</label>
                    <textarea rows="6" class="w-full bg-slate-900/50 border border-slate-600 rounded-xl p-4 text-white focus:outline-none focus:border-purple-500 transition" placeholder="Redacta la historia y la teoría aquí..."></textarea>
                </div>
                <div>
                    <label class="block text-slate-300 font-bold mb-2">Recursos Multimedia</label>
                    <div class="w-full border-2 border-dashed border-slate-600 rounded-xl p-8 flex flex-col items-center justify-center text-slate-500 hover:border-purple-500 hover:text-purple-400 transition cursor-pointer bg-slate-900/30">
                        <i class="fas fa-cloud-upload-alt text-4xl mb-3"></i>
                        <span class="font-bold">Arrastra imágenes o videos estelares aquí</span>
                    </div>
                </div>
                <button type="button" class="w-full py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white rounded-xl font-bold transition shadow-[0_0_15px_rgba(168,85,247,0.4)] text-lg">
                    Lanzar Misión
                </button>
            </form>
        </div>
    </div>

    <!-- TAB 3: 🎮 Creador Avanzado de Quizzes -->
    <div id="tab-quizzes" class="tab-content hidden space-y-6">
        <div class="glass-panel p-8 rounded-2xl">
            <div class="flex justify-between items-center mb-6 border-b border-slate-700/50 pb-4">
                <h2 class="text-2xl font-bold text-green-400 flex items-center gap-2">
                    <i class="fas fa-gamepad"></i> Creador de Desafíos
                </h2>
                <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white rounded-lg text-sm font-bold transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nueva Pregunta
                </button>
            </div>
            
            <!-- Configurador de Pregunta -->
            <div class="bg-slate-900/80 border border-slate-700 rounded-2xl p-6 mb-6 shadow-inner">
                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Desafío (Pregunta)</label>
                        <input type="text" class="w-full bg-slate-800 border border-slate-600 rounded-xl p-3 text-white focus:outline-none focus:border-green-500" placeholder="Ej: ¿Qué planeta es rojo?">
                    </div>
                    <div class="w-1/4">
                        <label class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Puntos</label>
                        <input type="number" value="10" class="w-full bg-slate-800 border border-slate-600 rounded-xl p-3 text-white text-center focus:outline-none focus:border-green-500">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Dificultad</label>
                    <div class="flex gap-2">
                        <label class="flex-1 text-center py-2 bg-slate-800 border border-slate-600 rounded-lg cursor-pointer hover:border-green-500">
                            <input type="radio" name="dif" class="hidden"> <span class="text-green-400">Fácil</span>
                        </label>
                        <label class="flex-1 text-center py-2 bg-slate-800 border border-slate-600 rounded-lg cursor-pointer hover:border-yellow-500 border-yellow-500">
                            <input type="radio" name="dif" class="hidden" checked> <span class="text-yellow-400">Medio</span>
                        </label>
                        <label class="flex-1 text-center py-2 bg-slate-800 border border-slate-600 rounded-lg cursor-pointer hover:border-red-500">
                            <input type="radio" name="dif" class="hidden"> <span class="text-red-400">Experto</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">Opciones (Marca la correcta)</label>
                    <div class="space-y-3 pl-4 border-l-2 border-slate-700">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="q_correct" checked class="w-6 h-6 accent-green-500 cursor-pointer">
                            <input type="text" class="w-full bg-slate-800/50 border border-green-500/50 rounded-lg p-2 text-white focus:outline-none" value="Marte">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="radio" name="q_correct" class="w-6 h-6 accent-green-500 cursor-pointer">
                            <input type="text" class="w-full bg-slate-800/50 border border-slate-700 rounded-lg p-2 text-slate-300 focus:outline-none" value="Venus">
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="button" class="w-full py-4 bg-green-600 hover:bg-green-500 text-white rounded-xl font-bold transition shadow-[0_0_15px_rgba(34,197,94,0.4)] text-lg">
                Guardar Configuración del Juego
            </button>
        </div>
    </div>

    <!-- TAB 4: 📅 Gestión de Tareas y Fechas Límite -->
    <div id="tab-tareas" class="tab-content hidden space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Asignar Tarea -->
            <div class="glass-panel p-8 rounded-2xl">
                <h2 class="text-2xl font-bold text-blue-400 mb-6 flex items-center gap-2">
                    <i class="fas fa-tasks"></i> Asignar Tarea Especial
                </h2>
                <form class="space-y-5" onsubmit="event.preventDefault();">
                    <div>
                        <label class="block text-slate-300 font-bold mb-2">Título de la Tarea</label>
                        <input type="text" class="w-full bg-slate-900/50 border border-slate-600 rounded-xl p-3 text-white focus:outline-none focus:border-blue-500" placeholder="Ej: Reporte del Campo Asteroidal">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-bold mb-2">Descripción</label>
                        <textarea rows="4" class="w-full bg-slate-900/50 border border-slate-600 rounded-xl p-3 text-white focus:outline-none focus:border-blue-500" placeholder="Instrucciones detalladas..."></textarea>
                    </div>
                    <div>
                        <label class="block text-pink-400 font-bold mb-2"><i class="fas fa-clock"></i> Fecha y Hora Límite (Deadline)</label>
                        <input type="datetime-local" class="w-full bg-slate-900/50 border border-pink-500/50 rounded-xl p-3 text-white focus:outline-none focus:border-pink-500 shadow-[0_0_10px_rgba(236,72,153,0.2)] custom-datetime" required>
                        <style>
                            .custom-datetime::-webkit-calendar-picker-indicator {
                                filter: invert(1);
                                cursor: pointer;
                            }
                        </style>
                    </div>
                    <button type="button" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition shadow-[0_0_15px_rgba(59,130,246,0.4)] text-lg mt-4">
                        Desplegar Tarea
                    </button>
                </form>
            </div>

            <!-- Tareas Activas -->
            <div class="glass-panel p-8 rounded-2xl">
                <h2 class="text-xl font-bold text-white mb-6 border-b border-slate-700/50 pb-2">Tareas Activas</h2>
                
                <div class="space-y-4">
                    <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-white font-bold">Mapeo de Constelaciones</h4>
                            <span class="px-2 py-1 bg-pink-500/20 text-pink-400 text-xs rounded-md font-bold border border-pink-500/30">Vence: Mañana 23:59</span>
                        </div>
                        <p class="text-sm text-slate-400 mb-3">Entregas: 12 / 24</p>
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-slate-700 border-2 border-slate-900 flex justify-center items-center text-xs">👩‍🚀</div>
                            <div class="w-8 h-8 rounded-full bg-slate-700 border-2 border-slate-900 flex justify-center items-center text-xs">👨‍🚀</div>
                            <div class="w-8 h-8 rounded-full bg-slate-700 border-2 border-slate-900 flex justify-center items-center text-xs">👽</div>
                            <div class="w-8 h-8 rounded-full bg-slate-800 border-2 border-slate-900 flex justify-center items-center text-xs text-slate-400">+9</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- LÓGICA SUPABASE REALTIME (SIMULACIÓN E INTEGRACIÓN) -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Referencias DOM
    const liveAvgDisplay = document.getElementById('live-avg-points');
    const activityFeed = document.getElementById('live-activity-feed');
    
    // Simulación del promedio inicial
    let currentTotal = 15400;
    let studentCount = 128;
    
    function updateAvg() {
        const avg = Math.round(currentTotal / studentCount);
        liveAvgDisplay.innerText = avg.toLocaleString();
        
        // Animación breve al actualizar
        liveAvgDisplay.classList.add('scale-110', 'text-white');
        setTimeout(() => liveAvgDisplay.classList.remove('scale-110', 'text-white'), 300);
    }
    
    updateAvg();

    // Integración Supabase Realtime para la tabla 'scores'
    if (typeof supabase !== 'undefined') {
        const adminScoresChannel = supabase.channel('teacher_scores_monitor')
            .on('postgres_changes', { event: '*', schema: 'public', table: 'scores' }, payload => {
                console.log('Actividad detectada (Teacher Monitor):', payload);
                
                if (payload.eventType === 'INSERT' || payload.eventType === 'UPDATE') {
                    // Actualizar promedio
                    currentTotal += 10; // Ejemplo lógico: sumar puntaje o recalcular
                    updateAvg();
                    
                    // Añadir feed de actividad
                    const li = document.createElement('li');
                    li.className = 'text-sm text-green-400 animate-[fadeIn_0.5s_ease-out] border-l-2 border-green-500 pl-3';
                    const time = new Date().toLocaleTimeString();
                    li.innerHTML = `<strong>${time}</strong>: Un cadete acaba de sumar puntos en una misión.`;
                    
                    activityFeed.prepend(li);
                    
                    // Mantener lista corta
                    if (activityFeed.children.length > 5) {
                        activityFeed.lastChild.remove();
                    }
                }
            })
            .subscribe();
    } else {
        console.warn('Supabase no definido. Ejecutando simulador local de monitoreo...');
        // Simulador visual si no hay DB
        setInterval(() => {
            currentTotal += Math.floor(Math.random() * 50);
            updateAvg();
        }, 8000);
    }
});
</script>

<?php require_once __DIR__ . '/../includes/tailwind_footer.php'; ?>
