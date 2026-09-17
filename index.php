<?php
$require_auth = false;
require_once 'includes/auth_middleware.php';
$base_dir = './';
$page_title = 'PR_X Academy — Aprende Jugando, Avanza Aprendiendo';

// Lógica de redirección por sesión para el botón principal
$hero_btn_url = '#'; // Cambiado a '#' por defecto para el modal
$hero_btn_text = 'Comenzar Ahora';
$hero_btn_icon = 'fas fa-rocket';
$is_logged_in = isset($_SESSION['user_role']);

if ($is_logged_in) {
    if ($_SESSION['user_role'] === 'profesor') {
        $hero_btn_url = $base_dir . 'pages/dashboard-profesor.php';
        $hero_btn_text = 'Ir a Gestor de Aulas';
        $hero_btn_icon = 'fas fa-chalkboard-teacher';
    } else {
        $hero_btn_url = $base_dir . 'pages/dashboard-alumno.php';
        $hero_btn_text = 'Ir a mi Dashboard';
        $hero_btn_icon = 'fas fa-gamepad';
    }
}

// Lógica de Errores de Login/Registro
$login_error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid_credentials') {
        $login_error = 'Credenciales incorrectas o el usuario no existe.';
    } elseif ($_GET['error'] === 'db_error') {
        $login_error = 'Error de conexión. Verifica la base de datos local.';
    } else {
        $login_error = htmlspecialchars($_GET['error']);
    }
}


$extra_head = <<<HTML
<!-- Alpine & Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700;800;900&display=swap');

  /* ── Diseño Accesible, Neutro y Premium ── */
  :root {
    --bg-main: #0B1120; /* Deep Space Blue */
    --card-bg: #1E293B; /* Gris grafito elegante */
    --text-primary: #F8FAFC;
    --text-secondary: #94A3B8;
    --accent-blue: #3B82F6;
    --accent-green: #10B981;
    --border-color: rgba(255, 255, 255, 0.1);
    --font-heading: 'Outfit', sans-serif;
    --font-body: 'Inter', sans-serif;
  }
  
  html, body {
    scroll-behavior: smooth;
    overflow-x: hidden;
    width: 100%;
    position: relative;
  }
  
  body {
    background-color: var(--bg-main);
    background-image: linear-gradient(-45deg, rgba(15, 23, 42, 0.2), rgba(2, 6, 23, 0.8), rgba(30, 15, 45, 0.2), rgba(2, 6, 23, 0.8));
    background-size: 400% 400%;
    animation: gradientBG 20s ease infinite;
    color: var(--text-primary);
    font-family: var(--font-body);
    margin: 0;
  }
  
  @keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  /* ── Typografía Global ── */
  h1, h2, h3, h4, .font-heading {
    font-family: var(--font-heading);
  }

  /* ── Landing específico ── */
  section {
    padding: 80px 0;
    width: 100%;
  }

  .container {
    width: 100%;
    max-width: 1280px; /* max-w-7xl */
    margin: 0 auto;
    padding: 0 16px; /* px-4 */
  }
  @media(min-width: 640px) {
    .container { padding: 0 24px; } /* sm:px-6 */
  }
  @media(min-width: 1024px) {
    .container { padding: 0 32px; } /* lg:px-8 */
  }

  .features-section { 
    background: var(--bg-main); 
    position: relative;
  }
  .features-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.05), transparent 50%);
    pointer-events: none;
  }
  
  .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; margin-top: 60px; position: relative; z-index: 1; }
  
  /* Tarjetas Estilo Premium */
  .feature-card {
    background: rgba(30, 41, 59, 0.6);
    backdrop-filter: blur(12px);
    border-radius: 24px;
    padding: 40px 32px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    outline: none;
  }
  .feature-card:focus-visible {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.5);
  }
  .feature-card:hover { 
    transform: scale(1.02); 
    box-shadow: 0 15px 30px rgba(59, 130, 246, 0.2), 0 0 20px rgba(59, 130, 246, 0.3); 
    border-color: rgba(255,255,255,0.2);
  }
  
  .feature-icon { font-size: 3rem; margin-bottom: 20px; display: block; color: var(--text-primary); transition: transform 0.3s ease; }
  .feature-card:hover .feature-icon { transform: scale(1.1); }
  .feature-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 12px; color: #ffffff; }
  .feature-desc { font-size: 1rem; color: #cbd5e1; line-height: 1.7; }

  .subjects-section { background: #0F172A; }
  .section-header { text-align: center; margin-bottom: 16px; position: relative; z-index: 1; }
  .section-header h2 { font-size: 2.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 12px; }
  .section-header p { color: var(--text-secondary); font-size: 1.1rem; }

  .landing-subjects-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 48px; }
  .landing-subject-card {
    background: var(--card-bg);
    border-radius: 24px;
    padding: 32px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
  }
  .landing-subject-card:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.1); 
    border-color: rgba(255,255,255,0.2); 
  }
  .landing-subject-icon { font-size: 3.5rem; display: block; margin-bottom: 20px; }
  .landing-subject-name { font-size: 1.1rem; font-weight: 700; font-family: var(--font-heading); color: #ffffff; }

  /* ── Nueva Sección About ── */
  .about-section {
    background: var(--bg-main);
    position: relative;
    padding: 120px 0;
    overflow: hidden;
  }
  .about-section::before {
    content: '';
    position: absolute;
    top: -30%; right: -10%;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(45, 212, 191, 0.05), transparent 60%);
    pointer-events: none;
  }
  .about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 64px;
    align-items: center;
    position: relative;
    z-index: 1;
  }
  .about-title {
    font-size: 2.8rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 24px;
  }
  .about-desc {
    font-size: 1.1rem;
    color: var(--text-secondary);
    line-height: 1.8;
    margin-bottom: 20px;
  }
  .about-cards {
    display: flex;
    gap: 24px;
  }
  .about-card {
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    padding: 32px 24px;
    backdrop-filter: blur(12px);
    transition: all 0.3s ease;
  }
  .about-card:hover {
    background: rgba(30, 41, 59, 0.6);
    border-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-5px) !important;
  }
  .about-card-icon {
    font-size: 2rem;
    color: var(--accent-blue);
    margin-bottom: 16px;
  }
  .about-card h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #FFF;
    margin-bottom: 12px;
  }
  .about-card p {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.5;
  }
  @media(max-width: 900px) {
    .about-grid { grid-template-columns: 1fr; gap: 48px; }
    .about-cards { flex-direction: column; }
    .about-card { transform: none !important; }
  }

  .cta-section {
    background: #0B1120;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .cta-section h2 { font-size: 3rem; font-weight: 900; color: var(--text-primary); margin-bottom: 16px; }
  .cta-section p { color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 40px; }

  /* Botones Premium */
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 28px;
    border-radius: 12px;
    font-size: 1.05rem;
    font-weight: 600;
    font-family: var(--font-body);
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    outline: none;
  }
  .btn:focus-visible {
    box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.6) !important;
  }
  .btn-xl { padding: 16px 36px; font-size: 1.15rem; }
  .btn-primary { background: var(--accent-blue); color: #fff; border: none; box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4); }
  .btn-primary:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 25px rgba(59, 130, 246, 0.6), 0 0 20px rgba(59, 130, 246, 0.4); }
  .btn-ghost { background: rgba(255, 255, 255, 0.05); color: var(--text-primary); border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); }
  .btn-ghost:hover { border-color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.15); transform: translateY(-2px); box-shadow: 0 10px 25px rgba(255, 255, 255, 0.1); }
  
  /* Hero Section Restaurada */
  .hero {
    position: relative;
    padding-top: 160px;
    padding-bottom: 120px;
    text-align: center;
    overflow: hidden;
  }
  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    margin-bottom: 32px;
    color: var(--text-secondary);
    font-size: 0.95rem;
    font-weight: 500;
  }
  .hero-title { 
    font-size: clamp(3rem, 9vw, 6.5rem); 
    line-height: 1.1;
    margin-bottom: 24px; 
    font-weight: 900;
    color: var(--text-primary);
    padding: 0 10px;
  }
  .hero-title .gradient { 
    background: linear-gradient(135deg, #3B82F6, #10B981);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  } 
  .hero-slogan {
    color: var(--text-secondary);
    font-size: clamp(1rem, 3vw, 1.25rem);
    max-width: 650px;
    margin: 0 auto 40px;
    line-height: 1.6;
    padding: 0 16px;
  }
  .hero-stats {
    margin-top: 60px;
    padding-top: 40px;
    border-top: 1px solid var(--border-color);
    width: 100%;
    margin-left: auto;
    margin-right: auto;
  }
  .hero-stat-item {
    text-align: center;
  }
  .hero-stat-val {
    font-size: 2.5rem;
    font-weight: 800;
    font-family: var(--font-heading);
    color: var(--text-primary);
    margin-bottom: 4px;
  }
  .hero-stat-lbl {
    color: var(--text-secondary);
    font-size: 0.95rem;
    font-weight: 500;
  }
  .hero-stat-divider {
    width: 1px;
    height: 40px;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
  }

  /* ── Fondo Espacial con Glow Blobs ── */
  body {
    background: #050510; /* Espacio Profundo */
    position: relative;
  }
  body::before, body::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    z-index: -1;
    pointer-events: none;
    animation: drift 20s infinite alternate linear;
  }
  body::before {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.2), transparent 70%);
    top: -10%; left: -10%;
  }
  body::after {
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.2), transparent 70%);
    bottom: -10%; right: -10%;
  }
  @keyframes drift {
    0% { transform: translate(0, 0); }
    100% { transform: translate(50px, 50px); }
  }

  /* Partículas animadas (Estrellas en movimiento) - Tema Espacial */
  .particles-container {
    position: absolute;
    inset: -30%; /* Más grande para que al rotar no se vean los bordes */
    pointer-events: none;
    z-index: 0;
    background: transparent;
    animation: rotateGalaxy 150s linear infinite;
    transform-origin: center center;
  }
  @keyframes rotateGalaxy {
    0% { transform: rotate(0deg) scale(1); }
    50% { transform: rotate(180deg) scale(1.1); }
    100% { transform: rotate(360deg) scale(1); }
  }
  .particle-star {
    position: absolute;
    background: white;
    border-radius: 50%;
    opacity: 0.5;
    animation: twinkle 3s infinite alternate;
  }
  @keyframes twinkle {
    0% { opacity: 0.1; transform: scale(0.8); }
    100% { opacity: 0.9; transform: scale(1.3); box-shadow: 0 0 5px rgba(255,255,255,0.8); }
  }

  /* ── Animaciones Espaciales y Lúdicas ── */
  @keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
    100% { transform: translateY(0px); }
  }
  .animate-float { animation: float 6s ease-in-out infinite; }
  .animate-fade-in { animation: fadeIn 1.2s ease-out forwards; opacity: 0; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

  .stat-card-glow {
    transition: all 0.3s ease;
  }
  .stat-card-glow:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 0 0 25px rgba(59, 130, 246, 0.6);
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(96, 165, 250, 0.5);
  }

  /* ── Modos de Accesibilidad (A11y) ── */
  .a11y-high-contrast {
    --bg-main: #000000;
    --card-bg: #111111;
    --text-primary: #FFFF00 !important;
    --text-secondary: #FFFFFF !important;
    --accent-blue: #0055FF;
    --border-color: #FFFF00;
  }
  .a11y-high-contrast .hero-title .gradient {
    background: none !important;
    -webkit-text-fill-color: #FFFF00 !important;
    color: #FFFF00 !important;
  }
  .a11y-large-text { font-size: 110%; }
  .a11y-large-text .hero-title { font-size: 5rem; }
  .a11y-large-text .hero-slogan { font-size: 1.5rem; }
  
  .a11y-simplify * {
    animation: none !important;
    transition: none !important;
    backdrop-filter: none !important;
  }
  .a11y-simplify .particles-container { background: #0B1120; }
  .a11y-simplify .particle-star { display: none; }

  /* ── CSS del Modal ── */
  .modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 2000;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
  }
  .modal-overlay.active {
    opacity: 1;
    visibility: visible;
  }
  .modal-content {
    background: rgba(11, 17, 32, 0.95); /* Deep Space Blue 95% */
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 40px;
    width: 90%;
    max-width: 450px;
    position: relative;
    transform: translateY(20px) scale(0.95);
    transition: all 0.3s ease;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    text-align: center;
  }
  .modal-overlay.active .modal-content {
    transform: translateY(0) scale(1);
  }
  .modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: transparent;
    border: none;
    color: #94A3B8;
    font-size: 1.5rem;
    cursor: pointer;
    transition: color 0.2s;
  }
  .modal-close:hover {
    color: #FFF;
  }
  .modal-title {
    font-family: var(--font-heading);
    font-size: 1.8rem;
    color: #FFF;
    margin-bottom: 24px;
    font-weight: 800;
  }
  .modal-form .form-group {
    margin-bottom: 20px;
    text-align: left;
    position: relative;
  }
  .modal-form label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
  }
  .modal-form .input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }
  .modal-form .input-wrapper i.left-icon {
    position: absolute;
    left: 14px;
    color: #94A3B8;
    font-size: 1rem;
  }
  .modal-form .input-wrapper button.right-icon {
    position: absolute;
    right: 14px;
    background: none;
    border: none;
    color: #94A3B8;
    font-size: 1rem;
    cursor: pointer;
    padding: 0;
  }
  .modal-content.register-premium {
    background: rgba(11, 17, 32, 0.95);
    border: 1px solid rgba(59, 130, 246, 0.3); /* Borde sutil brillante */
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.9), 0 0 40px rgba(59, 130, 246, 0.15); /* Sombra difusa e imponente */
    max-width: 480px;
  }
  
  .register-header {
    margin-bottom: 24px;
  }
  
  .register-subtitle {
    color: var(--text-secondary);
    font-size: 0.95rem;
    margin-top: 8px;
  }

  .role-selector-premium {
    display: flex;
    gap: 12px;
    background: rgba(2, 6, 23, 0.6);
    padding: 8px;
    border-radius: 16px;
    margin-bottom: 24px;
    border: 1px solid rgba(255, 255, 255, 0.05);
  }
  .role-tab {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 12px;
    background: transparent;
    color: #94A3B8;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  .role-tab:hover {
    color: #FFF;
    background: rgba(255, 255, 255, 0.05);
  }
  .role-tab.active {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(45, 212, 191, 0.2));
    color: #FFF;
    border: 1px solid rgba(59, 130, 246, 0.4);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
  }
  
  /* Inputs Premium */
  .modal-form input[type="email"],
  .modal-form input[type="password"],
  .modal-form input[type="text"] {
    width: 100%;
    padding: 16px 42px; /* Padding amplio */
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: #020617; /* Negro-azulado profundo */
    color: #FFF;
    font-family: var(--font-body);
    box-sizing: border-box;
    transition: all 0.3s ease;
  }
  .modal-form input::placeholder {
    color: #64748B;
  }
  .modal-form input:focus {
    outline: none;
    border-color: #22D3EE; /* Cian */
    box-shadow: 0 0 15px rgba(59, 130, 246, 0.25);
  }

  .modal-form .form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    font-size: 0.85rem;
  }
  .modal-form .checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-secondary);
    cursor: pointer;
  }
  .modal-form .checkbox-group input {
    width: 16px;
    height: 16px;
    accent-color: #3B82F6;
    cursor: pointer;
  }
  .modal-form .forgot-link {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.3s;
  }
  .modal-form .forgot-link:hover {
    color: #3B82F6;
  }

  .btn-gradient {
    width: 100%;
    padding: 14px;
    background: linear-gradient(to right, #3B82F6, #2563EB) !important;
    color: #FFF;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
    filter: brightness(1.1);
  }
  
  .modal-switch {
    margin-top: 24px;
    font-size: 0.95rem;
    color: var(--text-secondary);
  }
  .modal-switch a {
    color: var(--accent-blue);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
  }
  .modal-switch a:hover {
    text-decoration: underline;
    color: #60A5FA;
  }
</style>
HTML;

$extra_scripts = <<<HTML
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Generar un starfield (campo de estrellas) puro en CSS para movimiento continuo
    function generateStars(count) {
      let value = `\${Math.floor(Math.random() * 2500)}px \${Math.floor(Math.random() * 2500)}px #FFF`;
      for(let i = 2; i <= count; i++) {
        value += `, \${Math.floor(Math.random() * 2500)}px \${Math.floor(Math.random() * 2500)}px #FFF`;
      }
      return value;
    }
    const style = document.createElement('style');
    style.innerHTML = `
      #stars { width: 1px; height: 1px; background: transparent; box-shadow: \${generateStars(400)}; animation: animStar 40s linear infinite; }
      #stars:after { content: " "; position: absolute; top: 2500px; width: 1px; height: 1px; background: transparent; box-shadow: inherit; }
      #stars2 { width: 2px; height: 2px; background: transparent; box-shadow: \${generateStars(150)}; animation: animStar 80s linear infinite; }
      #stars2:after { content: " "; position: absolute; top: 2500px; width: 2px; height: 2px; background: transparent; box-shadow: inherit; }
      #stars3 { width: 3px; height: 3px; background: transparent; box-shadow: \${generateStars(50)}; animation: animStar 120s linear infinite; }
      #stars3:after { content: " "; position: absolute; top: 2500px; width: 3px; height: 3px; background: transparent; box-shadow: inherit; }
      @keyframes animStar { from { transform: translateY(0px); } to { transform: translateY(-2500px); } }
    `;
    document.head.appendChild(style);

    // --- LÓGICA DE MODALES (Alpine.js Events) ---
    window.openLoginModal = (e) => {
      if (e) e.preventDefault();
      window.dispatchEvent(new CustomEvent('close-register'));
      window.dispatchEvent(new CustomEvent('open-login'));
    };

    window.openRegisterModal = (e) => {
      if (e) e.preventDefault();
      window.dispatchEvent(new CustomEvent('close-login'));
      window.dispatchEvent(new CustomEvent('open-register'));
    };

    window.closeModal = (id) => {
      document.getElementById(id).classList.remove('active');
      document.body.style.overflow = '';
    };

    window.togglePassword = (inputId, btn) => {
      const input = document.getElementById(inputId);
      if (input) {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        btn.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
      }
    };

    window.selectRole = (btn, role) => {
      document.querySelectorAll('.role-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('modal-role-input').value = role;
      document.getElementById('reg-subtitle').textContent = role === 'profesor' 
        ? 'Únete como Profesor para gestionar tus aulas' 
        : 'Únete como Estudiante a nuestra comunidad';
    };

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
          overlay.classList.remove('active');
          document.body.style.overflow = '';
        }
      });
    });

    // Auto-abrir modal si hay un error
    <?php if (!empty($login_error)): ?>
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('open-login'));
        }, 300);
    <?php endif; ?>
  });
</script>
HTML;

require_once 'includes/db_connect.php';
require_once 'includes/head.php';
require_once 'includes/header.php';

// Consultas dinámicas
$features = [];
$subjects = [];

if (isset($pdo) && $pdo) {
    try {
        $stmtF = $pdo->query("SELECT * FROM features");
        $features = $stmtF->fetchAll();
        
        $stmtS = $pdo->query("SELECT * FROM subjects");
        $subjects = $stmtS->fetchAll();
    } catch (PDOException $e) {
        // Ignorar si la tabla no existe
    }
}

// Fallback estático
if (empty($features)) {
    $features = [
        ['icon' => '🎮', 'title' => 'Cuestionarios Interactivos', 'desc' => 'Preguntas de opción múltiple, Verdadero/Falso y asociación. Ideal para aprender a tu ritmo.'],
        ['icon' => '⭐', 'title' => 'Progreso Constante', 'desc' => 'Gana experiencia al completar tus actividades y observa cómo avanzas día a día.'],
        ['icon' => '🏆', 'title' => 'Logros Desbloqueables', 'desc' => 'Obtén reconocimientos por tu constancia y esfuerzo en las diferentes materias.'],
        ['icon' => '📊', 'title' => 'Gestor de Aulas', 'desc' => 'Herramientas dedicadas para profesores: asigna misiones y revisa el progreso de tus alumnos.'],
        ['icon' => '🧠', 'title' => 'Cultura y Trivias', 'desc' => 'Aprende sobre historia, cultura nacional y conocimientos generales de forma entretenida.'],
        ['icon' => '📅', 'title' => 'Hábitos de Estudio', 'desc' => 'Mantén una racha de días estudiando y forma un hábito saludable sin sobreestimulación.'],
    ];
}

if (empty($subjects)) {
    $subjects = [
        ['name' => 'Juegos de Trivia', 'icon' => 'fas fa-gamepad text-purple-400', 'glow' => 'rgba(168, 85, 247, 0.5)'],
        ['name' => 'Historia Universal', 'icon' => 'fas fa-globe-americas text-blue-400', 'glow' => 'rgba(59, 130, 246, 0.5)'],
        ['name' => 'Cultura Nacional', 'icon' => 'fas fa-landmark text-yellow-400', 'glow' => 'rgba(250, 204, 21, 0.5)'],
        ['name' => 'Cuestionarios', 'icon' => 'fas fa-file-alt text-green-400', 'glow' => 'rgba(74, 222, 128, 0.5)'],
    ];
}
?>

<!-- Contenedor Maestro de Accesibilidad -->
<div x-data="{ 
       a11y: { highContrast: false, largeText: false, simplify: false }, 
       toggleContrast() { this.a11y.highContrast = !this.a11y.highContrast; },
       toggleText() { this.a11y.largeText = !this.a11y.largeText; },
       toggleSimplify() { this.a11y.simplify = !this.a11y.simplify; }
     }"
     :class="{ 
       'a11y-high-contrast': a11y.highContrast, 
       'a11y-large-text': a11y.largeText,
       'a11y-simplify': a11y.simplify 
     }">

  <!-- Panel Flotante de Accesibilidad -->
  <div class="fixed top-24 right-4 z-[3000] flex flex-col gap-2" x-data="{ openA11y: false }">
    <button @click="openA11y = !openA11y" 
            class="absolute top-0 right-0 w-14 h-14 bg-blue-600 hover:bg-blue-500 text-white rounded-full flex items-center justify-center shadow-[0_0_20px_rgba(37,99,235,0.8)] focus:outline-none focus:ring-4 focus:ring-yellow-400 transition-transform hover:scale-110" 
            aria-label="Menú de Accesibilidad">
      <i class="fas fa-universal-access text-3xl"></i>
    </button>
    <div x-show="openA11y" @click.away="openA11y = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="mt-16 bg-gray-900 border-2 border-blue-500 rounded-xl p-4 shadow-2xl flex flex-col gap-3 text-white w-64 origin-top-right">
      <h4 class="font-bold border-b border-gray-700 pb-2 mb-1 text-lg flex items-center"><i class="fas fa-cog mr-2"></i> Accesibilidad</h4>
      <button @click="toggleContrast" class="flex items-center gap-3 px-3 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-left focus:ring-2 focus:ring-yellow-400">
        <i class="fas fa-adjust text-yellow-400 text-lg"></i> <span x-text="a11y.highContrast ? 'Desactivar Contraste' : 'Alto Contraste'"></span>
      </button>
      <button @click="toggleText" class="flex items-center gap-3 px-3 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-left focus:ring-2 focus:ring-yellow-400">
        <i class="fas fa-search-plus text-blue-400 text-lg"></i> <span x-text="a11y.largeText ? 'Texto Normal' : 'Texto Grande'"></span>
      </button>
      <button @click="toggleSimplify" class="flex items-center gap-3 px-3 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-left focus:ring-2 focus:ring-yellow-400">
        <i class="fas fa-eye-slash text-green-400 text-lg"></i> <span x-text="a11y.simplify ? 'Vista Completa' : 'Simplificar Vista'"></span>
      </button>
    </div>
  </div>

  <!-- ── HERO ── -->
  <section class="hero relative min-h-screen flex flex-col items-center justify-center pt-20 pb-10 overflow-hidden" id="hero">
    
    <!-- Fondo animado negro cósmico y Estrellas en Movimiento Infinito -->
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden bg-gradient-to-br from-[#020617] via-[#0f172a] to-[#1e1b4b] bg-[length:300%_300%] animate-[gradientBG_12s_ease_infinite]">
      <div id="stars"></div>
      <div id="stars2"></div>
      <div id="stars3"></div>
    </div>
    
    <!-- Animated background elements (Orbes y Figuras) -->
    <div class="absolute inset-0 pointer-events-none z-10">
      <!-- Orbe central gigante -->
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-tr from-blue-600/15 via-purple-600/15 to-transparent rounded-full blur-[100px] animate-pulse"></div>
      
      <!-- Orbes flotantes -->
      <div class="absolute top-1/4 left-[15%] w-48 h-48 bg-gradient-to-br from-cyan-500/30 to-blue-500/10 rounded-full blur-2xl animate-[float_8s_ease-in-out_infinite]"></div>
      <div class="absolute bottom-1/4 right-[15%] w-64 h-64 bg-gradient-to-tr from-purple-500/30 to-pink-500/10 rounded-full blur-2xl animate-[float_12s_ease-in-out_infinite_reverse]"></div>
      
      <!-- Figuras geométricas animadas -->
      <div class="absolute top-1/3 right-[20%] w-32 h-32 border border-cyan-400/30 rounded-full animate-[spin_15s_linear_infinite]"></div>
      <div class="absolute bottom-[30%] left-[20%] w-24 h-24 border-2 border-purple-400/30 rotate-45 animate-[spin_20s_linear_infinite_reverse]"></div>
      <div class="absolute top-[20%] left-[40%] w-16 h-16 border-t-2 border-r-2 border-pink-400/40 rounded-full animate-[spin_10s_linear_infinite]"></div>
    </div>

    <div class="hero-content container relative z-10 animate-fade-in w-full text-center">
      
      <div class="hero-badge animate-float bg-gray-900/80 border border-blue-400/40 backdrop-blur-md shadow-[0_0_20px_rgba(59,130,246,0.3)] text-white rounded-full px-8 py-2.5 mb-8 inline-flex items-center">
        <span class="text-2xl mr-2 animate-bounce">🚀</span>
        <span class="text-lg font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-200">Plataforma Educativa de Nueva Generación</span>
      </div>
      
      <h1 class="hero-title mt-4">
        Explora tu<br>
        <span class="gradient relative inline-block">
          Universo de Aprendizaje
        </span>
      </h1>
      
      <div class="hero-slogan text-lg text-gray-300 max-w-4xl mx-auto mt-6 mb-10 space-y-6">
        <p class="text-2xl md:text-3xl leading-relaxed text-white font-medium">Una aventura espacial gamificada donde aprender es fácil, divertido y sin barreras. ¡Conquista nuevos conocimientos a tu propio ritmo!</p>
        
        <p class="text-lg md:text-xl text-gray-300 bg-gray-900/40 p-6 rounded-2xl border border-gray-700/50 backdrop-blur-sm shadow-inner text-left sm:text-center leading-relaxed">
          En PR_X Academy, revolucionamos la educación tradicional integrando misiones interactivas, recompensas y un sistema de progreso que mantiene a los estudiantes siempre motivados. Una herramienta ideal y accesible tanto para el autoaprendizaje como para la gestión en el aula.
        </p>
        
        <div class="flex flex-wrap justify-center gap-6 text-base md:text-lg font-bold pt-4 text-blue-300">
          <div class="flex items-center transform transition hover:scale-110 hover:text-white cursor-default bg-gray-800/60 px-5 py-2.5 rounded-full border border-gray-700">
            <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center mr-3"><i class="fas fa-universal-access text-blue-400 text-lg"></i></div> 
            Accesibilidad 100%
          </div>
          <div class="flex items-center transform transition hover:scale-110 hover:text-white cursor-default bg-gray-800/60 px-5 py-2.5 rounded-full border border-gray-700">
            <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center mr-3"><i class="fas fa-gamepad text-purple-400 text-lg"></i></div> 
            Gamificación
          </div>
          <div class="flex items-center transform transition hover:scale-110 hover:text-white cursor-default bg-gray-800/60 px-5 py-2.5 rounded-full border border-gray-700">
            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center mr-3"><i class="fas fa-chart-line text-green-400 text-lg"></i></div> 
            Métricas de Progreso
          </div>
        </div>
      </div>
      
      <div class="hero-actions mt-12 flex gap-6 justify-center flex-wrap">
        <?php if($is_logged_in): ?>
        <a href="<?= htmlspecialchars($hero_btn_url) ?>" class="btn btn-primary btn-xl text-lg font-bold rounded-full shadow-[0_0_25px_rgba(59,130,246,0.5)] focus:ring-4 focus:ring-yellow-400 transition-all hover:-translate-y-1 hover:scale-105">
          <i class="<?= htmlspecialchars($hero_btn_icon) ?> text-xl mr-2"></i> <?= htmlspecialchars($hero_btn_text) ?>
        </a>
        <?php else: ?>
        <a href="pages/register.php" class="inline-block btn btn-primary btn-xl text-lg font-bold rounded-full shadow-[0_0_25px_rgba(59,130,246,0.6)] focus:ring-4 focus:ring-yellow-400 transition-all hover:-translate-y-1 hover:scale-105 relative z-50">
          <i class="fas fa-rocket text-xl mr-2 animate-pulse"></i> Comenzar Aventura
        </a>
        <a href="pages/login.php" class="inline-block btn btn-ghost btn-xl text-lg font-bold rounded-full border-2 border-gray-400 hover:border-white focus:ring-4 focus:ring-yellow-400 transition-all hover:bg-white/10 hover:-translate-y-1 hover:scale-105 relative z-50">
          <i class="fas fa-sign-in-alt text-xl mr-2"></i> Ya soy Héroe
        </a>
        <?php endif; ?>
      </div>

      <div class="hero-stats grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-16 w-full mx-auto border-t-0 p-0 px-4">
        <div class="hero-stat-item stat-card-glow bg-gray-900/60 border border-gray-600/30 rounded-3xl p-6 backdrop-blur-md cursor-default shadow-lg">
          <div class="text-4xl mb-3 animate-float">📚</div>
          <div class="hero-stat-val text-blue-400 font-extrabold text-3xl">4</div>
          <div class="hero-stat-lbl text-gray-300">Materias Clave</div>
        </div>
        <div class="hero-stat-item stat-card-glow bg-gray-900/60 border border-gray-600/30 rounded-3xl p-6 backdrop-blur-md cursor-default shadow-lg" style="animation-delay: 0.2s">
          <div class="text-4xl mb-3 animate-float">⭐</div>
          <div class="hero-stat-val text-yellow-400 font-extrabold text-3xl">100+</div>
          <div class="hero-stat-lbl text-gray-300">Misiones</div>
        </div>
        <div class="hero-stat-item stat-card-glow bg-gray-900/60 border border-gray-600/30 rounded-3xl p-6 backdrop-blur-md cursor-default shadow-lg" style="animation-delay: 0.4s">
          <div class="text-4xl mb-3 animate-float">🛡️</div>
          <div class="hero-stat-val text-green-400 font-extrabold text-3xl">100%</div>
          <div class="hero-stat-lbl text-gray-300">Seguro y Fácil</div>
        </div>
        <div class="hero-stat-item stat-card-glow bg-gray-900/60 border border-gray-600/30 rounded-3xl p-6 backdrop-blur-md cursor-default shadow-lg" style="animation-delay: 0.6s">
          <div class="text-4xl mb-3 animate-float">🏆</div>
          <div class="hero-stat-val text-purple-400 font-extrabold text-3xl">8</div>
          <div class="hero-stat-lbl text-gray-300">Logros Épicos</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── ABOUT (BENTO GRID & GLASSMORPHISM) ── -->
  <section class="py-24 relative z-10 w-full overflow-hidden" id="about">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl w-full">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[250px]">
        
        <!-- Tarjeta Principal (Ocupa 2 columnas) -->
        <div class="md:col-span-2 bg-gray-900/40 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-10 flex flex-col justify-center transition-all duration-300 hover:shadow-[0_10px_40px_rgba(124,58,237,0.2)] hover:border-purple-500/50 hover:-translate-y-1">
          <h2 class="text-4xl font-extrabold text-white mb-4">Nuestra <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Metodología</span></h2>
          <p class="text-gray-300 text-lg leading-relaxed">
            Hemos diseñado un entorno educativo inclusivo y libre de sobreestimulación. Nuestro enfoque estructurado y gamificado está pensado específicamente para mantener la concentración y potenciar las capacidades de todos los estudiantes, avanzando a su propio ritmo.
          </p>
        </div>

        <!-- Tarjeta Secundaria 1 -->
        <div class="bg-gray-900/40 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-8 flex flex-col items-center justify-center text-center transition-all duration-300 hover:shadow-[0_10px_40px_rgba(59,130,246,0.2)] hover:border-blue-500/50 hover:-translate-y-1">
          <div class="w-16 h-16 rounded-full bg-blue-500/20 flex items-center justify-center mb-4 text-3xl text-blue-400 animate-float">
            <i class="fas fa-brain"></i>
          </div>
          <h4 class="text-xl font-bold text-white mb-2">Entorno Inclusivo</h4>
          <p class="text-sm text-gray-400">Diseño sin distracciones para estudiantes neurodivergentes.</p>
        </div>

        <!-- Tarjeta Secundaria 2 -->
        <div class="bg-gray-900/40 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-8 flex flex-col items-center justify-center text-center transition-all duration-300 hover:shadow-[0_10px_40px_rgba(16,185,129,0.2)] hover:border-green-500/50 hover:-translate-y-1">
          <div class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center mb-4 text-3xl text-green-400 animate-float" style="animation-delay: 1s;">
            <i class="fas fa-chart-line"></i>
          </div>
          <h4 class="text-xl font-bold text-white mb-2">Aprendizaje Guiado</h4>
          <p class="text-sm text-gray-400">Rutas paso a paso con logros y recompensas constantes.</p>
        </div>

        <!-- Tarjeta Secundaria 3 (Ocupa 2 columnas) -->
        <div class="md:col-span-2 bg-gradient-to-br from-gray-900/60 to-purple-900/30 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-8 flex items-center gap-8 transition-all duration-300 hover:shadow-[0_10px_40px_rgba(168,85,247,0.2)] hover:border-pink-500/50 hover:-translate-y-1">
          <div class="flex-1">
            <h4 class="text-2xl font-bold text-white mb-2">Poder para Profesores</h4>
            <p class="text-gray-300">
              Herramientas potentes para monitorear el progreso, crear misiones y personalizar la experiencia de cada alumno en tiempo real.
            </p>
          </div>
          <div class="w-24 h-24 hidden sm:flex rounded-full bg-pink-500/20 items-center justify-center text-5xl text-pink-400 animate-float" style="animation-delay: 2s;">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ── FEATURES (GLASSMORPHISM) ── -->
  <section class="py-24 relative z-10 w-full overflow-hidden" id="features">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl w-full">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-extrabold text-white mb-4">Características <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">Principales</span></h2>
        <p class="text-gray-400 text-lg">Un espacio adaptado para el aprendizaje estructurado y amigable.</p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <?php foreach ($features as $index => $feature): ?>
        <a href="#" class="bg-[rgba(30,41,59,0.6)] backdrop-blur-xl border border-[rgba(255,255,255,0.08)] rounded-3xl p-8 transition-all duration-300 hover:bg-[rgba(30,41,59,0.8)] hover:shadow-[0_15px_30px_rgba(59,130,246,0.2),_0_0_20px_rgba(59,130,246,0.3)] hover:border-[rgba(255,255,255,0.2)] hover:scale-[1.02] group w-full block focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-400">
          <div class="text-5xl mb-6 transform transition-transform duration-300 group-hover:scale-110 group-hover:-translate-y-2 animate-float drop-shadow-[0_0_10px_rgba(255,255,255,0.3)]" style="animation-delay: <?= $index * 0.5 ?>s;">
            <?= $feature['icon'] ?>
          </div>
          <h3 class="text-xl font-bold text-white mb-3"><?= htmlspecialchars($feature['title']) ?></h3>
          <p class="text-gray-300 text-[1rem] leading-[1.7]"><?= htmlspecialchars($feature['desc']) ?></p>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── SUBJECTS (GLASSMORPHISM) ── -->
  <section class="py-24 relative z-10 w-full overflow-hidden" id="subjects">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl w-full">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Áreas de <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Aprendizaje</span></h2>
        <p class="text-gray-400 text-lg">Explora nuestras temáticas disponibles y expande tus conocimientos.</p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <?php foreach ($subjects as $index => $subject): ?>
        <a href="#" class="bg-[rgba(15,23,42,0.6)] backdrop-blur-xl border border-[rgba(255,255,255,0.08)] rounded-3xl p-8 text-center transition-all duration-300 hover:shadow-[0_15px_30px_rgba(0,0,0,0.5),_0_0_25px_<?= $subject['glow'] ?? 'rgba(236,72,153,0.3)' ?>] hover:border-[rgba(255,255,255,0.2)] hover:scale-[1.02] cursor-pointer group w-full flex flex-col items-center justify-center focus:outline-none focus-visible:ring-4 focus-visible:ring-purple-400 block">
          <div class="text-5xl mb-6 transform transition-all duration-300 group-hover:scale-125 group-hover:-translate-y-2 animate-float drop-shadow-[0_0_15px_<?= $subject['glow'] ?? 'rgba(236,72,153,0.5)' ?>]" style="animation-delay: <?= $index * 0.3 ?>s;">
            <?php if (strpos($subject['icon'], 'fa') !== false): ?>
              <i class="<?= $subject['icon'] ?>"></i>
            <?php else: ?>
              <?= $subject['icon'] ?>
            <?php endif; ?>
          </div>
          <div class="text-[1.1rem] font-bold text-white group-hover:text-white transition-colors tracking-wide leading-snug"><?= htmlspecialchars($subject['name']) ?></div>
          <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-sm font-semibold tracking-wider uppercase text-gray-400">
            Explorar <i class="fas fa-arrow-right ml-1"></i>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── CTA LUMINOUS ── -->
  <section class="py-24 relative z-10 w-full overflow-hidden" id="cta">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-blue-900/20 pointer-events-none"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl text-center relative z-10 w-full">
      <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">¿Listo para integrarte?</h2>
      <p class="text-lg md:text-xl text-gray-300 mb-10">Comienza ahora y explora todas las herramientas que PR_X Academy tiene para ti.</p>
      <div class="flex flex-col sm:flex-row gap-6 justify-center items-center w-full">
        <?php if($is_logged_in): ?>
        <a href="<?= htmlspecialchars($hero_btn_url) ?>" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white rounded-full bg-gradient-to-r from-blue-600 to-purple-600 shadow-[0_0_30px_rgba(59,130,246,0.6)] hover:shadow-[0_0_50px_rgba(168,85,247,0.8)] transform hover:scale-105 transition-all duration-300 animate-float">
          <i class="<?= htmlspecialchars($hero_btn_icon) ?> mr-3 text-xl"></i> <?= htmlspecialchars($hero_btn_text) ?>
        </a>
        <?php else: ?>
        <a href="pages/register.php" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white rounded-full bg-gradient-to-r from-purple-600 to-pink-600 shadow-[0_0_30px_rgba(168,85,247,0.6)] hover:shadow-[0_0_50px_rgba(236,72,153,0.8)] transform hover:scale-105 transition-all duration-300 animate-float relative z-50">
          <i class="fas fa-rocket mr-3 text-xl"></i> Crear Cuenta Gratis
        </a>
        <a href="pages/login.php" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white rounded-full bg-gray-800/80 border border-gray-600 backdrop-blur-sm hover:bg-gray-700 hover:border-blue-400 hover:shadow-[0_0_20px_rgba(59,130,246,0.4)] transform hover:scale-105 transition-all duration-300 relative z-50">
          <i class="fas fa-sign-in-alt mr-3 text-xl"></i> Iniciar Sesión
        </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ── MODAL DE LOGIN CON ALPINE.JS ── -->
  <div x-data="{ loginOpen: false, showPassword: false }" 
       @open-login.window="loginOpen = true"
       @close-login.window="loginOpen = false"
       class="relative z-[2000]">
    <div x-show="loginOpen" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 backdrop-blur-none"
         x-transition:enter-end="opacity-100 backdrop-blur-md"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 backdrop-blur-md"
         x-transition:leave-end="opacity-0 backdrop-blur-none"
         class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-md p-4">
      
      <div @click.away="loginOpen = false" 
           x-show="loginOpen"
           x-transition:enter="transition ease-out duration-300 transform"
           x-transition:enter-start="opacity-0 translate-y-8 scale-95"
           x-transition:enter-end="opacity-100 translate-y-0 scale-100"
           x-transition:leave="transition ease-in duration-200 transform"
           x-transition:leave-start="opacity-100 translate-y-0 scale-100"
           x-transition:leave-end="opacity-0 translate-y-8 scale-95"
           class="relative w-full max-w-md bg-gray-900/80 border border-purple-500/30 rounded-3xl p-8 shadow-[0_0_50px_rgba(124,58,237,0.2)] max-h-[95vh] overflow-y-auto">
        
        <button type="button" @click="loginOpen = false" class="absolute top-5 right-5 text-gray-400 hover:text-white text-2xl transition-colors">
          <i class="fas fa-times"></i>
        </button>

        <div class="text-center mb-6">
          <div class="flex justify-center mb-4">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-12 w-auto text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.4)]">
              <circle cx="50" cy="50" r="42" stroke="currentColor" stroke-width="4"/>
              <path d="M30 30 L70 70 M30 70 L70 30" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
              <path d="M35 65 Q 60 70, 80 20" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/>
              <path d="M80 10 L82 17 L89 19 L82 21 L80 28 L78 21 L71 19 L78 17 Z" fill="currentColor"/>
            </svg>
          </div>
          <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Bienvenido de Vuelta</h2>
          <p class="text-gray-400 text-sm mt-1">Ingresa tus credenciales para continuar tu misión.</p>
        </div>

        <?php if (!empty($login_error)): ?>
        <div class="bg-red-500/20 border border-red-500/50 rounded-xl p-4 mb-6 flex items-start gap-3 text-left">
          <i class="fas fa-exclamation-circle text-red-400 text-xl mt-0.5"></i>
          <div>
            <h4 class="text-red-300 font-bold text-sm">Error de Autenticación</h4>
            <p class="text-red-200/80 text-xs mt-1"><?= $login_error ?></p>
            <?php if ($login_error === 'Credenciales incorrectas o el usuario no existe.'): ?>
            <p class="text-purple-300 text-xs mt-2 font-medium"><i class="fas fa-info-circle"></i> Tip: Puedes usar <b>admin@prx.com</b> con clave <b>admin123</b> (se creará automáticamente si no existe).</p>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <form action="pages/login_process.php" method="POST" class="space-y-4">
          <input type="hidden" name="csrf_token" value="<?= escape(generate_csrf_token()) ?>">
          
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <i class="fas fa-envelope text-gray-500"></i>
            </div>
            <input type="email" name="correo" required placeholder="Correo Electrónico" 
                   class="w-full pl-11 pr-4 py-3 bg-gray-800/60 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-colors">
          </div>
          
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-500"></i>
            </div>
            <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Contraseña" 
                   class="w-full pl-11 pr-10 py-3 bg-gray-800/60 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-colors">
            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white focus:outline-none">
              <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
          </div>

          <div class="flex items-center justify-between text-sm pt-2">
            <label class="flex items-center text-gray-400 cursor-pointer hover:text-gray-300">
              <input type="checkbox" name="remember" class="mr-2 rounded border-gray-700 bg-gray-800 text-purple-500 focus:ring-purple-500 focus:ring-offset-gray-900">
              Recordarme
            </label>
            <a href="#" class="text-purple-400 hover:text-purple-300 font-bold">¿Olvidaste tu clave?</a>
          </div>

          <button type="submit" class="w-full py-3.5 mt-2 text-white font-bold rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 hover:shadow-[0_0_20px_rgba(168,85,247,0.5)] transform hover:-translate-y-1 transition-all duration-300">
            Iniciar Misión
          </button>
        </form>
        
        <div class="mt-6 text-center text-sm text-gray-400">
          <span>¿Aún no eres miembro? </span>
          <a href="#" @click.prevent="openRegisterModal(event)" class="text-purple-400 hover:text-purple-300 font-bold">Únete a la Élite</a>
        </div>
      </div>
    </div>
  </div>

  <!-- ── MODAL DE REGISTRO CON ALPINE.JS ── -->
  <div x-data="{ 
         registerOpen: false, 
         selectedRole: 'alumno',
         selectedAvatar: '👨‍🎓', 
         avatars: ['👨‍🎓', '👩‍🎓', '🧙‍♂️', '🥷', '🦸‍♀️', '🤖'],
         password: '',
         confirmPassword: '',
         showPassword: false,
         showConfirmPassword: false,
         get passwordStrength() {
           let score = 0;
           if(this.password.length > 5) score++;
           if(this.password.length > 8) score++;
           if(/[A-Z]/.test(this.password)) score++;
           if(/[0-9]/.test(this.password)) score++;
           if(/[^A-Za-z0-9]/.test(this.password)) score++;
           return score;
         },
         get strengthColor() {
           const colors = ['bg-gray-700', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
           return colors[this.passwordStrength] || 'bg-green-400';
         },
         get strengthWidth() {
           return (this.passwordStrength * 20) + '%';
         }
       }" 
       @open-register.window="registerOpen = true"
       @close-register.window="registerOpen = false"
       class="relative z-[2000]">
    
    <div x-show="registerOpen" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 backdrop-blur-none"
         x-transition:enter-end="opacity-100 backdrop-blur-md"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 backdrop-blur-md"
         x-transition:leave-end="opacity-0 backdrop-blur-none"
         class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-md p-4">
      
      <div @click.away="registerOpen = false" 
           x-show="registerOpen"
           x-transition:enter="transition ease-out duration-300 transform"
           x-transition:enter-start="opacity-0 translate-y-8 scale-95"
           x-transition:enter-end="opacity-100 translate-y-0 scale-100"
           x-transition:leave="transition ease-in duration-200 transform"
           x-transition:leave-start="opacity-100 translate-y-0 scale-100"
           x-transition:leave-end="opacity-0 translate-y-8 scale-95"
           class="relative w-full max-w-lg bg-gray-900/80 border border-purple-500/30 rounded-3xl p-8 shadow-[0_0_50px_rgba(124,58,237,0.2)] max-h-[95vh] overflow-y-auto">
        
        <button type="button" @click="registerOpen = false" class="absolute top-5 right-5 text-gray-400 hover:text-white text-2xl transition-colors">
          <i class="fas fa-times"></i>
        </button>

        <div class="text-center mb-6">
          <div class="flex justify-center mb-4">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-12 w-auto text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.4)]">
              <circle cx="50" cy="50" r="42" stroke="currentColor" stroke-width="4"/>
              <path d="M30 30 L70 70 M30 70 L70 30" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
              <path d="M35 65 Q 60 70, 80 20" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/>
              <path d="M80 10 L82 17 L89 19 L82 21 L80 28 L78 21 L71 19 L78 17 Z" fill="currentColor"/>
            </svg>
          </div>
          <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Únete a la Élite</h2>
          <p class="text-gray-400 text-sm mt-1">Selecciona tu perfil y comienza la aventura.</p>
        </div>

        <form action="pages/register_process.php" method="POST" class="space-y-4" @submit="if(password !== confirmPassword) { $event.preventDefault(); alert('Las contraseñas no coinciden.'); }">
          <input type="hidden" name="csrf_token" value="<?= escape(generate_csrf_token()) ?>">
          
          <!-- Role Selector -->
          <div class="flex gap-4 mb-2">
            <button type="button" @click="selectedRole = 'alumno'" :class="selectedRole === 'alumno' ? 'bg-purple-600/20 border-purple-500 text-purple-300 shadow-[0_0_15px_rgba(168,85,247,0.3)]' : 'bg-gray-800/50 border-gray-700 text-gray-400 hover:bg-gray-700'" class="flex-1 flex flex-col items-center justify-center py-3 border rounded-xl transition-all">
              <i class="fas fa-user-graduate text-2xl mb-1"></i>
              <span class="text-sm font-bold">Estudiante</span>
            </button>
            <button type="button" @click="selectedRole = 'profesor'" :class="selectedRole === 'profesor' ? 'bg-blue-600/20 border-blue-500 text-blue-300 shadow-[0_0_15px_rgba(59,130,246,0.3)]' : 'bg-gray-800/50 border-gray-700 text-gray-400 hover:bg-gray-700'" class="flex-1 flex flex-col items-center justify-center py-3 border rounded-xl transition-all">
              <i class="fas fa-chalkboard-teacher text-2xl mb-1"></i>
              <span class="text-sm font-bold">Profesor</span>
            </button>
          </div>
          <input type="hidden" name="rol" :value="selectedRole">

          <!-- Avatar Selector -->
          <div x-show="selectedRole === 'alumno'" x-transition class="mb-2">
            <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2 text-center">Elige tu Avatar</label>
            <div class="flex flex-wrap justify-center gap-3">
              <template x-for="avatar in avatars" :key="avatar">
                <button type="button" @click="selectedAvatar = avatar" :class="selectedAvatar === avatar ? 'ring-2 ring-purple-500 ring-offset-2 ring-offset-gray-900 bg-gray-700' : 'bg-gray-800 border-gray-700 hover:bg-gray-700'" class="w-12 h-12 flex items-center justify-center text-2xl rounded-full border transition-all focus:outline-none">
                  <span x-text="avatar"></span>
                </button>
              </template>
            </div>
            <input type="hidden" name="avatar" :value="selectedAvatar">
          </div>

          <!-- Text Inputs -->
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-user text-gray-500"></i></div>
            <input type="text" name="nombre" required placeholder="Nombre Completo" class="w-full pl-11 pr-4 py-3 bg-gray-800/60 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-colors">
          </div>
          
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-envelope text-gray-500"></i></div>
            <input type="email" name="correo" required placeholder="Correo Electrónico" class="w-full pl-11 pr-4 py-3 bg-gray-800/60 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-colors">
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-lock text-gray-500"></i></div>
              <input :type="showPassword ? 'text' : 'password'" x-model="password" name="password" required minlength="6" placeholder="Contraseña" class="w-full pl-11 pr-10 py-3 bg-gray-800/60 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-colors">
              <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white focus:outline-none"><i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i></button>
            </div>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-check-circle text-gray-500"></i></div>
              <input :type="showConfirmPassword ? 'text' : 'password'" x-model="confirmPassword" name="confirm_password" required minlength="6" placeholder="Confirmar" class="w-full pl-11 pr-10 py-3 bg-gray-800/60 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-colors" :class="confirmPassword && password !== confirmPassword ? 'border-red-500 focus:ring-red-500' : ''">
              <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white focus:outline-none"><i class="fas" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i></button>
            </div>
          </div>
          
          <!-- Password Strength -->
          <div x-show="password.length > 0" x-transition class="mt-2">
            <div class="h-1.5 w-full bg-gray-700 rounded-full overflow-hidden">
              <div class="h-full transition-all duration-300" :class="strengthColor" :style="'width: ' + strengthWidth"></div>
            </div>
            <div class="flex justify-between items-center mt-1">
              <span class="text-xs text-red-400" x-show="confirmPassword && password !== confirmPassword">Las contraseñas no coinciden</span>
              <span class="text-xs text-gray-400 ml-auto" x-text="['Muy Débil', 'Débil', 'Regular', 'Buena', 'Fuerte', 'Excelente'][passwordStrength]"></span>
            </div>
          </div>

          <button type="submit" class="w-full py-3.5 mt-2 text-white font-bold rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 hover:shadow-[0_0_20px_rgba(168,85,247,0.5)] transform hover:-translate-y-1 transition-all duration-300">
            Registrarme Ahora
          </button>
        </form>
        
        <div class="mt-6 text-center text-sm text-gray-400">
          <span>¿Ya tienes cuenta? </span>
          <a href="#" @click.prevent="openLoginModal(event)" class="text-blue-400 hover:text-blue-300 font-bold">Inicia sesión aquí</a>
        </div>
      </div>
    </div>
  </div>

<?php require_once 'includes/footer.php'; ?>
</div> <!-- Cierre del Contenedor Maestro de Accesibilidad -->

