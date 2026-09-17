<?php
// includes/tailwind_header.php
require_once __DIR__ . '/auth_middleware.php';


// Función para generar estrellas para el fondo cósmico
if (!function_exists('generateStars')) {
    function generateStars($count, $color) {
        $stars = [];
        for ($i = 0; $i < $count; $i++) {
            $x = rand(0, 2000);
            $y = rand(0, 2000);
            $stars[] = "{$x}px {$y}px {$color}";
        }
        return implode(', ', $stars);
    }
}
$starfield1 = generateStars(350, '#FFFFFF'); // Blancas pequeñas
$starfield2 = generateStars(100, '#FEF08A'); // Amarillas estelares medianas
$starfield3 = generateStars(50, '#A5F3FC');  // Cian grandes

$userId = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['user_name'] ?? 'Usuario';
$userRole = $_SESSION['user_role'] ?? 'alumno';
$userAvatar = $_SESSION['user_avatar'] ?? '👨‍🎓';
$userXP = $_SESSION['user_xp'] ?? 1500;
$userLevel = $_SESSION['user_level'] ?? 'Explorador';
$current_page = $current_page ?? 'dashboard.php';
$page_title = $page_title ?? 'PRX Academy';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
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
            --bg-main: #050814;
            --bg-panel: rgba(11, 19, 41, 0.85);
            --text-primary: #ffffff;
            --text-secondary: #93c5fd;
            --border-color: rgba(165, 243, 252, 0.15);
            --accent: #FACC15;
            --success: #34d399;
            --error: #f87171;
            --warning: #fbbf24;
            --info: #38bdf8;
        }
        .theme-contrast {
            --bg-main: #000000;
            --bg-panel: #000000;
            --text-primary: #ffffff;
            --text-secondary: #ffff00;
            --border-color: #ffffff;
            --accent: #ffff00;
            --success: #00ff00;
            --error: #ff0000;
            --warning: #ffaa00;
            --info: #00ffff;
        }
        .theme-pastel {
            --bg-main: #fdfbf7;
            --bg-panel: rgba(242, 239, 233, 0.9);
            --text-primary: #374151;
            --text-secondary: #6b7280;
            --border-color: #d1d5db;
            --accent: #6ee7b7;
            --success: #34d399;
            --error: #fca5a5;
            --warning: #fcd34d;
            --info: #93c5fd;
        }
        
        body { background-color: var(--bg-main, #0f172a); font-family: 'Inter', sans-serif; transition: background-color 0.5s, color 0.5s; line-height: 1.5; font-weight: 400; color: var(--text-primary, #ffffff); }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; font-weight: 700; letter-spacing: -0.02em; }
        .font-subheading { font-family: 'Outfit', sans-serif; font-weight: 500; letter-spacing: -0.01em; }
        
        /* Elevation & Glassmorphism */
        .glass-panel { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); }
        .elevation-1 { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); }
        .elevation-2 { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); }
        .elevation-3 { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2), 0 10px 10px -5px rgba(0,0,0,0.1); }
        .neon-border { border: 2px solid var(--accent); box-shadow: 0 0 15px var(--accent); }
        [x-cloak] { display: none !important; }
        
        /* Micro-interactions & Polish */
        a, button, .card, .glass-card, .mode-btn, .avatar-chip, .btn { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important; }
        .card:hover, .glass-panel:hover { box-shadow: 0 15px 30px rgba(0,0,0,0.5), 0 0 25px rgba(168,85,247,0.3); transform: translateY(-4px); }
        button:hover:not(.tab-btn), .btn:hover { box-shadow: 0 0 20px rgba(168,85,247,0.4); transform: translateY(-2px); }
        aside a.group:hover, aside button.group:hover { transform: translateX(4px); box-shadow: none; }
        
        /* Skeleton Loaders (Shimmer) */
        @keyframes shimmer { 0% { background-position: -1000px 0; } 100% { background-position: 1000px 0; } }
        .skeleton { background: var(--bg-panel); background-image: linear-gradient(90deg, rgba(255,255,255,0) 0, rgba(255,255,255,0.05) 20%, rgba(255,255,255,0) 40%); background-size: 1000px 100%; animation: shimmer 2.5s infinite linear; border-radius: 0.5rem; }
        
        /* Badges / Pills */
        .pill { padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid transparent; display: inline-flex; align-items: center; gap: 4px; }
        .pill-success { background: rgba(34, 197, 94, 0.15); color: var(--success); border-color: rgba(34, 197, 94, 0.3); }
        .pill-warning { background: rgba(234, 179, 8, 0.15); color: var(--warning); border-color: rgba(234, 179, 8, 0.3); }
        .pill-info { background: rgba(59, 130, 246, 0.15); color: var(--info); border-color: rgba(59, 130, 246, 0.3); }
        .pill-accent { background: rgba(168, 85, 247, 0.15); color: var(--accent); border-color: rgba(168, 85, 247, 0.3); }
        
        /* Visual Validation */
        .form-input { transition: all 0.3s ease-in-out; }
        .form-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(168,85,247,0.3); }
        .input-success { border-color: var(--success) !important; box-shadow: 0 0 0 2px rgba(34,197,94,0.2) !important; }
        .input-error { border-color: var(--error) !important; box-shadow: 0 0 0 2px rgba(239,68,68,0.2) !important; }
        
        /* Empty States */
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 2rem; text-align: center; background: var(--bg-panel); border: 2px dashed var(--border-color); border-radius: 1.5rem; transition: all 0.3s ease; }
        .empty-state-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.5; filter: grayscale(100%); transition: all 0.4s ease; }
        .empty-state:hover .empty-state-icon { transform: scale(1.1); filter: grayscale(0%); opacity: 1; text-shadow: 0 0 15px var(--accent); }
        .empty-state-title { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; font-family: 'Outfit', sans-serif; letter-spacing: -0.01em; }
        .empty-state-desc { font-size: 0.95rem; color: var(--text-secondary); max-width: 400px; margin-bottom: 1.5rem; line-height: 1.6; }
        
        /* Accesibilidad: Áreas Táctiles (Hit Targets) */
        button, a, .btn, .mode-btn, .quiz-option, .tf-btn { min-height: 48px; min-width: 48px; display: inline-flex; align-items: center; justify-content: center; }
        
        /* Accesibilidad: Modo Enfoque */
        body.focus-mode-active .focus-hide { display: none !important; }
        body.focus-mode-active .focus-dim { opacity: 0.05 !important; filter: grayscale(100%); pointer-events: none; }
        body.focus-mode-active .glass-panel { background: var(--bg-main) !important; border-color: transparent !important; box-shadow: none !important; }
        body.focus-mode-active aside { opacity: 0.3; filter: grayscale(100%); transition: all 0.3s; }
        body.focus-mode-active aside:hover { opacity: 1; filter: grayscale(0%); }

        @keyframes moveBg { 0% { background-position: 0 0; } 100% { background-position: 100px 100px; } }
        
        /* Real Starfield Effect */
        .starfield-container { position: absolute; inset: 0; overflow: hidden; opacity: 0.4; pointer-events: none; }
        .starfield-1 { width: 1px; height: 1px; background: transparent; box-shadow: <?= $starfield1 ?>; animation: driftStars 150s linear infinite; }
        .starfield-1::after { content: " "; position: absolute; top: 2000px; width: 1px; height: 1px; background: transparent; box-shadow: <?= $starfield1 ?>; }
        
        .starfield-2 { width: 2px; height: 2px; background: transparent; box-shadow: <?= $starfield2 ?>; animation: driftStars 200s linear infinite; }
        .starfield-2::after { content: " "; position: absolute; top: 2000px; width: 2px; height: 2px; background: transparent; box-shadow: <?= $starfield2 ?>; }
        
        .starfield-3 { width: 3px; height: 3px; background: transparent; box-shadow: <?= $starfield3 ?>; animation: driftStars 250s linear infinite; }
        .starfield-3::after { content: " "; position: absolute; top: 2000px; width: 3px; height: 3px; background: transparent; box-shadow: <?= $starfield3 ?>; }
        
        @keyframes driftStars { 0% { transform: translateY(0); } 100% { transform: translateY(-2000px); } }
        
        <?php if (isset($extra_css)) echo $extra_css; ?>
    </style>
</head>
<body class="bg-slate-950 bg-theme_bg text-theme_text min-h-screen overflow-x-hidden flex transition-colors duration-500 theme-space" 
      x-data="{ sidebarOpen: true, currentTheme: 'theme-space', focusMode: localStorage.getItem('focusMode') === 'true' }" 
      :class="[currentTheme, focusMode ? 'focus-mode-active' : '']">
    
    <!-- Fondo Cósmico Profundo (visible solo en theme-space) -->
    <div x-show="currentTheme === 'theme-space'" class="fixed inset-0 z-[-1] overflow-hidden bg-[radial-gradient(ellipse_at_center,_#111c42_0%,_#0b1329_40%,_#050814_100%)] focus-dim">
        
        <!-- Nebulosas Orgánicas Suaves -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-cyan-900/30 blur-[80px] pointer-events-none focus-hide"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] rounded-full bg-purple-900/20 blur-[80px] pointer-events-none focus-hide"></div>
        
        <!-- Estrellas (Generadas en PHP vía CSS) -->
        <div class="starfield-container focus-hide">
            <div class="starfield-1"></div>
            <div class="starfield-2"></div>
            <div class="starfield-3"></div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="w-72 glass-panel flex flex-col transition-all duration-300 shadow-[4px_0_24px_rgba(0,0,0,0.4)] border-r border-[rgba(255,255,255,0.05)]" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full fixed h-full z-50'">
        
        <!-- Botón cerrar (Móvil) -->
        <div class="lg:hidden p-4 flex justify-end">
            <button @click="sidebarOpen = false" class="text-theme_text_muted hover:text-theme_text p-2 rounded-lg focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- 1. Cabecera del Perfil (User Pill Inmersiva) -->
        <div class="p-4 mx-4 mt-6 mb-2 rounded-2xl bg-gradient-to-br from-[rgba(255,255,255,0.05)] to-[rgba(0,0,0,0.2)] border border-[rgba(255,255,255,0.05)] shadow-[0_4px_12px_rgba(0,0,0,0.3)] flex items-center gap-3 relative overflow-hidden group hover:border-[rgba(168,85,247,0.4)] transition-all duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.15)] cursor-pointer">
            <div class="w-12 h-12 rounded-xl bg-theme_bg border border-[rgba(255,255,255,0.1)] flex-shrink-0 flex items-center justify-center text-2xl relative overflow-hidden shadow-inner">
                <?php if (strpos($userAvatar, 'http') === 0): ?>
                    <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <?= htmlspecialchars($userAvatar) ?>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-heading font-bold text-theme_text text-sm truncate group-hover:text-theme_accent transition-colors"><?= htmlspecialchars($userName) ?></h3>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-[9px] font-bold text-theme_accent uppercase tracking-widest bg-[rgba(168,85,247,0.1)] px-1.5 py-0.5 rounded-md border border-[rgba(168,85,247,0.2)]"><?= htmlspecialchars($userLevel) ?></span>
                    <span class="text-[10px] text-theme_text_muted font-medium"><?= $userXP ?> XP</span>
                </div>
            </div>
        </div>

        <?php
        // Active state glow con barra lateral luminosa y fondo degradado
        // Borde izquierdo interno para no desplazar el contenido (usando box-shadow o border-transparent en inactivo)
        $activeClass = "bg-gradient-to-r from-[rgba(168,85,247,0.15)] to-[rgba(168,85,247,0.02)] text-white border-l-[3px] border-theme_accent shadow-[inset_1px_0_10px_rgba(168,85,247,0.1)] relative before:absolute before:left-[-3px] before:top-0 before:h-full before:w-[3px] before:bg-theme_accent before:shadow-[0_0_12px_rgba(168,85,247,0.8)]";
        $inactiveClass = "text-theme_text_muted hover:text-white hover:bg-[rgba(255,255,255,0.05)] border-l-[3px] border-transparent";
        ?>

        <!-- Navegación Principal -->
        <nav class="flex-1 py-6 space-y-8 overflow-y-auto custom-scrollbar">
            
            <!-- 2. Sección PRINCIPAL -->
            <div>
                <p class="pl-[27px] text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">Principal</p>
                <div class="space-y-1">
                    <a href="dashboard.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'dashboard.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-border-all text-lg group-hover:scale-110 transition-transform <?= $current_page == 'dashboard.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Dashboard</span>
                    </a>
                    <a href="profile.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'profile.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-user text-lg group-hover:scale-110 transition-transform <?= $current_page == 'profile.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Mi Perfil</span>
                    </a>
                </div>
            </div>

            <!-- 3. Sección APRENDER -->
            <div>
                <p class="pl-[27px] text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">Aprender</p>
                <div class="space-y-1">
                    <?php if ($userRole === 'profesor'): ?>
                    <a href="subjects.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'subjects.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-users text-lg group-hover:scale-110 transition-transform <?= $current_page == 'subjects.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Mis Aulas</span>
                    </a>
                    <a href="games.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'games.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-tasks text-lg group-hover:scale-110 transition-transform <?= $current_page == 'games.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Crear Misión</span>
                    </a>
                    <a href="ranking.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'ranking.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-chart-line text-lg group-hover:scale-110 transition-transform <?= $current_page == 'ranking.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Progreso General</span>
                    </a>
                    <?php else: ?>
                    <a href="subjects.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'subjects.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-book text-lg group-hover:scale-110 transition-transform <?= $current_page == 'subjects.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Materias</span>
                    </a>
                    <a href="games.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'games.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-gamepad text-lg group-hover:scale-110 transition-transform <?= $current_page == 'games.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Juegos Demo</span>
                    </a>
                    <a href="achievements.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'achievements.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-briefcase text-lg group-hover:scale-110 transition-transform <?= $current_page == 'achievements.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Mochila de Calcomanías</span>
                    </a>
                    <a href="ranking.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'ranking.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-trophy text-lg group-hover:scale-110 transition-transform <?= $current_page == 'ranking.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Ranking</span>
                    </a>
                    <a href="calm.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'calm.php' ? 'bg-gradient-to-r from-[rgba(16,185,129,0.15)] to-transparent text-emerald-400 border-l-[3px] border-emerald-500 shadow-[inset_1px_0_10px_rgba(16,185,129,0.1)] relative before:absolute before:left-[-3px] before:top-0 before:h-full before:w-[3px] before:bg-emerald-500 before:shadow-[0_0_12px_rgba(16,185,129,0.8)]' : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-spa text-lg group-hover:scale-110 transition-transform <?= $current_page == 'calm.php' ? 'text-emerald-500 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-bold">Mi Rincón Seguro</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 4. Sección SISTEMA -->
            <div>
                <p class="pl-[27px] text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">Sistema</p>
                <div class="space-y-1">
                    <a href="settings.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium focus-visible:ring-2 focus-visible:ring-theme_accent focus-visible:outline-none transition-colors duration-200 group <?= $current_page == 'settings.php' ? $activeClass : $inactiveClass ?>">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-cog text-lg group-hover:scale-110 transition-transform <?= $current_page == 'settings.php' ? 'text-theme_accent drop-shadow-[0_0_8px_rgba(250,204,21,0.8)]' : 'opacity-70' ?>"></i> 
                        </div>
                        <span class="text-base font-medium">Configuración</span>
                    </a>
                    <a href="logout.php" class="flex items-center gap-4 pl-6 pr-4 py-3 min-h-[48px] font-medium text-[rgba(239,68,68,0.8)] hover:text-white hover:bg-[rgba(239,68,68,0.2)] border-l-[3px] border-transparent hover:border-red-500 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:outline-none transition-colors duration-200 group">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-sign-out-alt text-lg group-hover:scale-110 transition-transform opacity-80 group-hover:text-red-400"></i> 
                        </div>
                        <span class="text-base font-medium">Cerrar Sesión</span>
                    </a>
                </div>
            </div>

        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-h-screen transition-all duration-300" :class="!sidebarOpen ? 'w-full' : ''">
        <!-- Topbar -->
        <header class="h-24 sticky top-0 z-50 bg-slate-900/80 backdrop-blur-md border-b border-white/10 flex items-center justify-between px-8 lg:px-12 shadow-md">
            <button @click="sidebarOpen = !sidebarOpen" aria-label="Alternar menú lateral" class="text-theme_text_muted hover:text-theme_text text-2xl p-3 rounded-xl focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none bg-theme_panel border-2 border-theme_border">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex items-center gap-6">
                
                <!-- Selector de Temas Visuales (Accesible) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" aria-label="Cambiar tema visual" class="flex items-center gap-3 px-6 py-4 rounded-xl bg-theme_panel border-[3px] border-theme_border text-theme_text font-bold hover:border-theme_accent focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition">
                        <i class="fas fa-palette text-2xl text-theme_accent"></i>
                        <span class="hidden md:inline text-lg">Tema Visual</span>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-3 w-80 glass-panel rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.5)] border-[3px] border-theme_border p-4 flex flex-col gap-3 z-50">
                        <button @click="currentTheme = 'theme-space'; open = false" class="flex items-center gap-4 px-5 py-4 rounded-xl border-[3px] border-transparent hover:border-theme_accent hover:bg-theme_panel text-left font-extrabold text-xl focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition-all">
                            <i class="fas fa-meteor text-[#38bdf8] text-2xl w-8 text-center"></i> Cosmos Azul
                        </button>
                        <button @click="currentTheme = 'theme-contrast'; open = false" class="flex items-center gap-4 px-5 py-4 rounded-xl border-[3px] border-transparent hover:border-yellow-500 hover:bg-theme_panel text-left font-extrabold text-xl focus-visible:ring-4 focus-visible:ring-yellow-500 focus-visible:outline-none transition-all">
                            <i class="fas fa-eye text-yellow-500 text-2xl w-8 text-center"></i> Alto Contraste
                        </button>
                        <button @click="currentTheme = 'theme-pastel'; open = false" class="flex items-center gap-4 px-5 py-4 rounded-xl border-[3px] border-transparent hover:border-green-500 hover:bg-theme_panel text-left font-extrabold text-xl focus-visible:ring-4 focus-visible:ring-green-500 focus-visible:outline-none transition-all">
                            <i class="fas fa-leaf text-green-500 text-2xl w-8 text-center"></i> Pastel Relajante
                        </button>
                    </div>
                </div>

                <!-- Botón Modo Enfoque -->
                <button @click="focusMode = !focusMode; localStorage.setItem('focusMode', focusMode)" 
                        aria-label="Alternar Modo Enfoque" 
                        title="Modo Enfoque (Reduce distracciones)"
                        class="flex items-center gap-3 px-5 py-3 rounded-xl border-[3px] font-bold focus-visible:ring-4 focus-visible:outline-none transition"
                        :class="focusMode ? 'bg-theme_accent text-white border-theme_accent ring-4 ring-theme_accent/30 shadow-[0_0_15px_rgba(var(--accent),0.5)]' : 'bg-theme_panel border-theme_border text-theme_text hover:border-theme_accent'">
                    <i class="fas fa-brain text-xl"></i>
                    <span class="hidden md:inline">Enfoque</span>
                </button>

                <button aria-label="Notificaciones" class="w-14 h-14 rounded-xl bg-theme_panel border-2 border-theme_border flex items-center justify-center hover:border-theme_accent focus-visible:ring-4 focus-visible:ring-theme_accent focus-visible:outline-none transition relative focus-hide">
                    <i class="fas fa-bell text-theme_text_muted text-xl"></i>
                    <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full border-2 border-theme_bg animate-bounce"></span>
                </button>
            </div>
        </header>

        <!-- View Content Wrapper -->
        <div class="p-8 lg:p-12">
