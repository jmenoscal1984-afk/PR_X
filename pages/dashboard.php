<?php
// pages/dashboard.php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'Usuario';
$userRole = $_SESSION['user_role'] ?? 'alumno';

// Simulated fetch from DB for initial structure
$userAvatar = $_SESSION['user_avatar'] ?? '👨‍🎓';
$userXP = 1500;
$userLevel = 'Explorador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Gestión - PRX Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        theme_bg: 'var(--bg-main)',
                        theme_panel: 'var(--bg-panel)',
                        theme_text: 'var(--text-primary)',
                        theme_text_muted: 'var(--text-secondary)',
                        theme_border: 'var(--border-color)',
                        theme_accent: 'var(--accent)'
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root, .theme-space {
            --bg-main: #0B1120;
            --bg-panel: rgba(15, 23, 42, 0.7);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --border-color: rgba(51, 65, 85, 0.5);
            --accent: #a855f7;
        }
        .theme-contrast {
            --bg-main: #000000;
            --bg-panel: #000000;
            --text-primary: #ffff00;
            --text-secondary: #ffcc00;
            --border-color: #ffff00;
            --accent: #ffff00;
        }
        .theme-pastel {
            --bg-main: #f0fdf4;
            --bg-panel: rgba(255, 255, 255, 0.8);
            --text-primary: #166534;
            --text-secondary: #15803d;
            --border-color: #bbf7d0;
            --accent: #22c55e;
        }
        
        body { font-family: 'Inter', sans-serif; transition: background-color 0.5s, color 0.5s; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }
        .glass-panel { background: var(--bg-panel); backdrop-filter: blur(16px); border: 2px solid var(--border-color); }
        .neon-border { border: 2px solid var(--accent); box-shadow: 0 0 15px var(--accent); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-theme_bg text-theme_text min-h-screen overflow-x-hidden flex transition-colors duration-500" 
      x-data="{ sidebarOpen: true, currentTheme: 'theme-space' }" :class="currentTheme">
    
    <!-- Fondo animado (visible solo en theme-space) -->
    <div x-show="currentTheme === 'theme-space'" class="fixed inset-0 z-[-1] bg-gradient-to-br from-[#020617] via-[#0f172a] to-[#1e1b4b] bg-[length:200%_200%] animate-[pulse_10s_ease-in-out_infinite]"></div>

    <!-- Sidebar -->
    <aside class="w-72 glass-panel flex flex-col transition-all duration-300 shadow-xl" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full fixed h-full z-50'">
        
        <!-- Botón cerrar (Móvil) -->
        <div class="lg:hidden p-4 flex justify-end border-b border-theme_border">
            <button @click="sidebarOpen = false" class="text-theme_text_muted hover:text-theme_text p-2 rounded-lg focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- 1. Cabecera del Perfil -->
        <div class="px-6 py-8 border-b border-theme_border flex flex-col items-center text-center relative">
            <div class="w-24 h-24 rounded-full bg-theme_panel border-4 border-theme_border shadow-lg flex items-center justify-center text-5xl mb-4 relative overflow-hidden">
                <?php if (strpos($userAvatar, 'http') === 0): ?>
                    <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <?= htmlspecialchars($userAvatar) ?>
                <?php endif; ?>
                <div class="absolute -bottom-2 -right-2 bg-theme_accent text-white text-xs font-bold px-3 py-1 rounded-full border-2 border-theme_bg shadow-sm z-10">
                    <?= $userXP ?> XP
                </div>
            </div>
            <h3 class="font-heading font-bold text-theme_text text-2xl mb-1"><?= htmlspecialchars($userName) ?></h3>
            <p class="text-xs font-bold text-theme_text_muted bg-theme_bg px-3 py-1 rounded-full border border-theme_border uppercase tracking-wide mt-2"><?= htmlspecialchars($userLevel) ?></p>
        </div>

        <!-- Navegación Principal -->
        <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto">
            
            <!-- 2. Sección PRINCIPAL -->
            <div>
                <p class="px-4 text-xs font-extrabold text-theme_text_muted uppercase tracking-wider mb-3">Principal</p>
                <div class="space-y-1">
                    <a href="#" class="flex items-center gap-4 px-4 py-3 bg-theme_accent text-white rounded-2xl font-bold shadow-[0_0_15px_rgba(var(--accent),0.4)] focus-visible:ring-4 focus-visible:ring-theme_text focus-visible:outline-none transition group">
                        <i class="fas fa-border-all text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Dashboard
                    </a>
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-user text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Mi Perfil
                    </a>
                </div>
            </div>

            <!-- 3. Sección APRENDER -->
            <div>
                <p class="px-4 text-xs font-extrabold text-theme_text_muted uppercase tracking-wider mb-3">Aprender</p>
                <div class="space-y-1">
                    <?php if ($userRole === 'profesor'): ?>
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-users text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Mis Aulas
                    </a>
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-tasks text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Crear Misión
                    </a>
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-chart-line text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Progreso General
                    </a>
                    <?php else: ?>
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-book text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Materias
                    </a>
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-gamepad text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Juegos Demo
                    </a>
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-medal text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Logros
                    </a>
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-trophy text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Ranking
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 4. Sección SISTEMA -->
            <div>
                <p class="px-4 text-xs font-extrabold text-theme_text_muted uppercase tracking-wider mb-3">Sistema</p>
                <div class="space-y-1">
                    <a href="#" class="flex items-center gap-4 px-4 py-3 text-theme_text_muted hover:text-theme_text hover:bg-theme_panel rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition group">
                        <i class="fas fa-cog text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Configuración
                    </a>
                    <a href="logout.php" class="flex items-center gap-4 px-4 py-3 text-red-500 hover:text-white hover:bg-red-500 hover:border-red-500 rounded-2xl font-bold focus-visible:ring-4 focus-visible:ring-red-500 focus-visible:outline-none transition group mt-2 border border-transparent">
                        <i class="fas fa-sign-out-alt text-xl w-6 text-center group-hover:scale-110 transition-transform"></i> Cerrar Sesión
                    </a>
                </div>
            </div>

        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-h-screen transition-all duration-300" :class="!sidebarOpen ? 'w-full' : ''">
        <!-- Topbar -->
        <header class="h-24 glass-panel border-b border-theme_border flex items-center justify-between px-8 lg:px-12 sticky top-0 z-40 shadow-sm">
            <button @click="sidebarOpen = !sidebarOpen" aria-label="Alternar menú lateral" class="text-theme_text_muted hover:text-theme_text text-2xl p-3 rounded-xl focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none bg-theme_panel border-2 border-theme_border">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex items-center gap-6">
                
                <!-- Selector de Temas Visuales -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" aria-label="Cambiar tema visual" class="flex items-center gap-3 px-5 py-3 rounded-xl bg-theme_panel border-2 border-theme_border text-theme_text font-bold hover:border-theme_accent focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition">
                        <i class="fas fa-palette text-xl text-theme_accent"></i>
                        <span class="hidden md:inline">Tema Visual</span>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-3 w-64 glass-panel rounded-2xl shadow-2xl border-2 border-theme_border p-3 flex flex-col gap-2 z-50">
                        <button @click="currentTheme = 'theme-space'; open = false" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-theme_accent/20 hover:text-theme_accent text-left font-bold text-lg focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none">
                            <i class="fas fa-meteor text-purple-500"></i> Espacial (Oscuro)
                        </button>
                        <button @click="currentTheme = 'theme-contrast'; open = false" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-yellow-500/20 hover:text-yellow-500 text-left font-bold text-lg focus-visible:ring-4 focus-visible:ring-yellow-500 focus-visible:outline-none">
                            <i class="fas fa-eye text-yellow-500"></i> Alto Contraste
                        </button>
                        <button @click="currentTheme = 'theme-pastel'; open = false" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-green-500/20 hover:text-green-600 text-left font-bold text-lg focus-visible:ring-4 focus-visible:ring-green-500 focus-visible:outline-none">
                            <i class="fas fa-leaf text-green-500"></i> Pastel Relajante
                        </button>
                    </div>
                </div>

                <button aria-label="Notificaciones" class="w-14 h-14 rounded-xl bg-theme_panel border-2 border-theme_border flex items-center justify-center hover:border-theme_accent focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition relative">
                    <i class="fas fa-bell text-theme_text_muted text-xl"></i>
                    <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full border-2 border-theme_bg animate-bounce"></span>
                </button>
            </div>
        </header>

        <!-- View Content -->
        <div class="p-8 lg:p-12">
            <?php 
                if ($userRole === 'profesor') {
                    include 'teacher_view.php';
                } else {
                    include 'student_view.php';
                }
            ?>
        </div>
    </main>

</body>
</html>
