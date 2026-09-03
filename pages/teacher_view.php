<?php
// pages/teacher_view.php
if (!isset($userRole) || $userRole !== 'profesor') exit;
?>
<!-- Panel de Profesor - Analíticas y Gestión -->
<div class="space-y-8 animate-[fadeIn_0.5s_ease-out]">
    
    <!-- Hero Section Profesor -->
    <div class="glass-panel rounded-3xl p-8 relative overflow-hidden border border-blue-500/30">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white mb-2">Panel de Control, <?= htmlspecialchars($userName) ?></h1>
                <p class="text-slate-400">Resumen de actividad de tus aulas y alumnos en tiempo real.</p>
            </div>
            <button class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold shadow-[0_0_15px_rgba(59,130,246,0.4)] transition transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-plus"></i> Nueva Misión
            </button>
        </div>
    </div>

    <!-- Métricas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass-panel p-6 rounded-2xl border border-slate-700/50 hover:border-slate-500 transition">
            <div class="text-slate-400 text-sm font-semibold mb-1">Alumnos Totales</div>
            <div class="text-3xl font-heading font-bold text-white">124</div>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-slate-700/50 hover:border-slate-500 transition">
            <div class="text-slate-400 text-sm font-semibold mb-1">Misiones Activas</div>
            <div class="text-3xl font-heading font-bold text-white">8</div>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-slate-700/50 hover:border-slate-500 transition">
            <div class="text-slate-400 text-sm font-semibold mb-1">Tasa de Aprobación</div>
            <div class="text-3xl font-heading font-bold text-green-400">92%</div>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-red-500/30 bg-red-900/10 hover:border-red-500/50 transition">
            <div class="text-slate-400 text-sm font-semibold mb-1">Requieren Atención</div>
            <div class="text-3xl font-heading font-bold text-red-400">3</div>
        </div>
    </div>

    <!-- Lista de Alumnos Recientes -->
    <div class="glass-panel rounded-2xl border border-slate-700/50 overflow-hidden">
        <div class="p-6 border-b border-slate-700/50 flex justify-between items-center">
            <h2 class="font-heading text-xl font-bold text-white">Rendimiento Reciente de Alumnos</h2>
            <a href="#" class="text-sm text-blue-400 hover:text-blue-300 font-medium">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-slate-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-medium">Alumno</th>
                        <th class="p-4 font-medium">Clase / Materia</th>
                        <th class="p-4 font-medium">Progreso Última Misión</th>
                        <th class="p-4 font-medium text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-300 flex items-center justify-center text-lg">👨‍🎓</div>
                            <div>
                                <div class="text-white font-bold">Carlos Ruiz</div>
                                <div class="text-xs text-slate-500">ID: #4092</div>
                            </div>
                        </td>
                        <td class="p-4 text-slate-300 font-medium">Matemáticas Básicas</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-32 h-2 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="bg-green-500 h-full w-[100%]"></div>
                                </div>
                                <span class="text-sm font-bold text-green-400">100%</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <button class="text-slate-400 hover:text-blue-400 transition p-2"><i class="fas fa-envelope"></i></button>
                            <button class="text-slate-400 hover:text-white transition p-2"><i class="fas fa-chart-pie"></i></button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-500/20 text-pink-300 flex items-center justify-center text-lg">👩‍🎓</div>
                            <div>
                                <div class="text-white font-bold">Ana Gómez</div>
                                <div class="text-xs text-slate-500">ID: #4105</div>
                            </div>
                        </td>
                        <td class="p-4 text-slate-300 font-medium">Historia Universal</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-32 h-2 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="bg-orange-500 h-full w-[40%]"></div>
                                </div>
                                <span class="text-sm font-bold text-orange-400">40%</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <button class="text-slate-400 hover:text-blue-400 transition p-2"><i class="fas fa-envelope"></i></button>
                            <button class="text-slate-400 hover:text-white transition p-2"><i class="fas fa-chart-pie"></i></button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-500/20 text-green-300 flex items-center justify-center text-lg">🥷</div>
                            <div>
                                <div class="text-white font-bold">Miguel Torres</div>
                                <div class="text-xs text-slate-500">ID: #4112</div>
                            </div>
                        </td>
                        <td class="p-4 text-slate-300 font-medium">Ciencias Naturales</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-32 h-2 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="bg-red-500 h-full w-[15%]"></div>
                                </div>
                                <span class="text-sm font-bold text-red-400">15%</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <button class="text-red-400 hover:text-red-300 transition p-2" title="Enviar Alerta"><i class="fas fa-exclamation-triangle"></i></button>
                            <button class="text-slate-400 hover:text-white transition p-2"><i class="fas fa-chart-pie"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
