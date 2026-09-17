<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_dir = $base_dir ?? './';
?>
  <style>
    /* Estilos Premium para la Barra de Navegación */
    .landing-nav-wrapper {
      position: sticky;
      top: 0;
      z-index: 1000;
      padding: 12px 24px;
      background: rgba(11, 17, 32, 0.75);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .landing-nav {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-family: 'Inter', sans-serif;
    }
    
    @media (max-width: 900px) {
      .landing-nav-wrapper { top: 10px; padding: 0 16px; }
      .landing-nav { padding: 12px 20px; border-radius: 24px; }
      .landing-nav-links { display: none !important; }
    }
    
    .landing-nav-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      font-family: 'Outfit', sans-serif;
    }
    
    .landing-nav-logo:hover .landing-nav-logo-icon {
      transform: scale(1.05) rotate(5deg);
      box-shadow: 0 0 15px rgba(34, 211, 238, 0.4);
      border-color: rgba(34, 211, 238, 0.6);
    }
    
    .landing-nav-logo-icon {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(139,92,246,0.1));
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(59, 130, 246, 0.3);
      box-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .landing-nav-logo-icon svg {
      width: 24px;
      height: 24px;
    }
    
    .logo-text-pr {
      font-weight: 900;
      color: #FFFFFF;
      letter-spacing: -0.5px;
      font-size: 1.6rem;
    }
    
    .logo-text-academy {
      font-weight: 600;
      background: linear-gradient(to right, #60A5FA, #A78BFA);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-left: 4px;
      font-size: 1.5rem;
    }
    
    .landing-nav-links {
      display: flex;
      gap: 36px;
    }
    
    .landing-nav-links a {
      color: #94A3B8;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.95rem;
      position: relative;
      transition: color 0.3s ease;
      padding: 8px 0;
    }
    
    .landing-nav-links a::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 50%;
      background: linear-gradient(90deg, #22D3EE, #8B5CF6);
      transition: all 0.3s ease;
      transform: translateX(-50%);
      border-radius: 2px;
    }
    
    .landing-nav-links a:hover {
      color: #FFF;
    }
    
    .landing-nav-links a:hover::after {
      width: 100%;
    }
    
    .landing-nav-actions {
      display: flex;
      gap: 16px;
      align-items: center;
    }

    /* Botones Específicos del Header */
    .nav-btn {
      padding: 10px 24px;
      border-radius: 50px;
      font-weight: 600;
      font-size: 0.95rem;
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .nav-btn-ghost {
      color: #F8FAFC;
      border: 1px solid rgba(255, 255, 255, 0.15);
      background: rgba(255, 255, 255, 0.05);
    }

    .nav-btn-ghost:hover {
      background: rgba(59, 130, 246, 0.1);
      border-color: rgba(59, 130, 246, 0.5);
      box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
      color: #60A5FA;
    }

    .nav-btn-primary {
      color: #FFF;
      background: linear-gradient(135deg, #3B82F6, #8B5CF6);
      border: none;
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    .nav-btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(139, 92, 246, 0.5);
      filter: brightness(1.1);
    }
  </style>

  <!-- ── NAVBAR ── -->
  <div class="landing-nav-wrapper">
    <nav class="landing-nav" id="landing-nav">
      <!-- Enlace fluido de retorno al inicio con el nuevo Logo -->
      <a href="<?= htmlspecialchars($base_dir) ?>index.php" class="flex items-center gap-3 text-white no-underline group">
        <!-- Logo SVG Minimalista (Círculo, X y Órbita con Estrella) -->
        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-9 w-auto text-white transition-transform duration-500 group-hover:scale-105 group-hover:drop-shadow-[0_0_8px_rgba(255,255,255,0.6)]">
          <!-- Círculo exterior -->
          <circle cx="50" cy="50" r="42" stroke="currentColor" stroke-width="4"/>
          <!-- Letra X -->
          <path d="M30 30 L70 70 M30 70 L70 30" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
          <!-- Órbita curvada -->
          <path d="M35 65 Q 60 70, 80 20" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/>
          <!-- Estrella superior derecha -->
          <path d="M80 10 L82 17 L89 19 L82 21 L80 28 L78 21 L71 19 L78 17 Z" fill="currentColor"/>
        </svg>
        <div class="flex items-center font-bold font-['Outfit'] text-2xl tracking-tight">
          <span class="text-white">PRX</span>
          <span class="text-gray-300 ml-1.5 font-medium text-xl">Academy</span>
        </div>
      </a>
      
      <div class="landing-nav-links">
        <a href="<?= htmlspecialchars($base_dir) ?>index.php#hero">Inicio</a>
        <a href="<?= htmlspecialchars($base_dir) ?>index.php#about">Quiénes Somos</a>
        <a href="<?= htmlspecialchars($base_dir) ?>index.php#features">Funciones</a>
        <a href="<?= htmlspecialchars($base_dir) ?>index.php#subjects">Materias</a>
        <a href="<?= htmlspecialchars($base_dir) ?>index.php#cta">Comunidad</a>
      </div>
      
      <div class="landing-nav-actions">
        <?php if(isset($_SESSION['user_id'])): ?>
          <a href="<?= htmlspecialchars($base_dir) ?>pages/dashboard.php" class="nav-btn nav-btn-primary">
            Mi Dashboard 🚀
          </a>
        <?php else: ?>
          <a href="<?= htmlspecialchars($base_dir) ?>pages/login.php" class="nav-btn nav-btn-ghost">
            Iniciar Sesión
          </a>
          <a href="<?= htmlspecialchars($base_dir) ?>pages/register.php" class="nav-btn nav-btn-primary">
            Registrarse 🚀
          </a>
        <?php endif; ?>
      </div>
    </nav>
  </div>
