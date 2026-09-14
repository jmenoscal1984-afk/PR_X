<?php
$require_auth = false;
require_once '../includes/auth_middleware.php';
$base_dir = '../';
$page_title = 'Crear Cuenta — EduQuest Bachillerato';
require_once '../includes/head.php';
?>
<!-- Asegúrate de tener Tailwind CSS cargado en tu proyecto. Si no, usa un CDN en head.php -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen flex items-center justify-center bg-gray-950 relative overflow-hidden">
  <!-- Orbes de fondo (Efecto de luz) -->
  <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob"></div>
  <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-2000"></div>

  <div class="relative w-full max-w-lg z-10">
    <!-- Contenedor Glassmorphism -->
    <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-8 sm:p-10 shadow-[0_0_40px_rgba(0,0,0,0.5)]">
      
      <!-- Cabecera del formulario -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 mb-4 shadow-lg shadow-purple-500/30">
          <span class="text-2xl">🎓</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">¡Crea tu cuenta!</h1>
        <p class="text-gray-400 mt-2 text-sm">Únete a la élite y comienza a ganar XP desde hoy.</p>
      </div>

      <!-- Errores PHP (Si existen, pasados por sesión o GET) -->
      <?php if (isset($_GET['error'])): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center">
          <?= htmlspecialchars($_GET['error']) ?>
        </div>
      <?php endif; ?>

      <form action="register_process.php" method="POST" id="register-form" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= escape(generate_csrf_token()) ?>">
        
        <!-- Identidad (Avatar) -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-3 flex items-center">
            <i class="fas fa-user-circle mr-2 text-purple-400"></i> Elige tu avatar de héroe
          </label>
          <div class="flex flex-wrap justify-center gap-4" id="avatar-selector">
            <!-- Avatares generados por JS -->
          </div>
          <input type="hidden" name="avatar" id="selected_avatar" value="👨‍🎓" required>
        </div>

        <!-- Campos de Texto -->
        <div class="space-y-5">
          <!-- Nombre Completo -->
          <div class="relative">
            <label for="nombre" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nombre Completo</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-500"></i>
              </div>
              <input type="text" name="nombre" id="nombre" required
                class="block w-full pl-10 pr-3 py-3 border border-gray-700 rounded-xl leading-5 bg-gray-800/50 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                placeholder="Ej. Alex Héroe">
            </div>
          </div>

          <!-- Correo -->
          <div class="relative">
            <label for="correo" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Correo Electrónico</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-500"></i>
              </div>
              <input type="email" name="correo" id="correo" required
                class="block w-full pl-10 pr-3 py-3 border border-gray-700 rounded-xl leading-5 bg-gray-800/50 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                placeholder="tu@correo.com">
            </div>
          </div>

          <!-- Contraseñas (Grid) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Contraseña -->
            <div class="relative">
              <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Contraseña</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-lock text-gray-500"></i>
                </div>
                <input type="password" name="password" id="password" required minlength="6"
                  class="block w-full pl-10 pr-3 py-3 border border-gray-700 rounded-xl leading-5 bg-gray-800/50 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                  placeholder="Mín. 6 caracteres">
              </div>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="relative">
              <label for="confirm_password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Confirmar</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-check-circle text-gray-500"></i>
                </div>
                <input type="password" id="confirm_password" required minlength="6"
                  class="block w-full pl-10 pr-3 py-3 border border-gray-700 rounded-xl leading-5 bg-gray-800/50 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                  placeholder="Repite la clave">
              </div>
            </div>
          </div>
          <span id="password-error" class="text-red-400 text-xs hidden mt-1">Las contraseñas no coinciden.</span>
        </div>

        <!-- Botón de Envío -->
        <button type="submit" class="w-full flex items-center justify-center py-3.5 px-4 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-purple-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_0_25px_rgba(147,51,234,0.6)]">
          <i class="fas fa-rocket mr-2"></i> Crear Cuenta Épica
        </button>

      </form>

      <!-- Enlaces Inferiores -->
      <div class="mt-8 pt-6 border-t border-gray-700/50 flex flex-col items-center space-y-3">
        <p class="text-sm text-gray-400">
          ¿Ya eres leyenda? <a href="login.php" class="font-medium text-purple-400 hover:text-purple-300 transition-colors">Inicia sesión</a>
        </p>
        <a href="../index.php" class="text-xs text-gray-500 hover:text-gray-300 transition-colors">
          <i class="fas fa-arrow-left mr-1"></i> Volver al inicio
        </a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Avatares ilustrados usando la API de DiceBear (alta calidad SVG)
  const avatars = [
    { id: 'mago', url: 'https://api.dicebear.com/9.x/adventurer/svg?seed=Mage&backgroundColor=0f172a' },
    { id: 'ninja', url: 'https://api.dicebear.com/9.x/adventurer/svg?seed=Ninja&backgroundColor=0f172a' },
    { id: 'robot', url: 'https://api.dicebear.com/9.x/bottts/svg?seed=Buster&backgroundColor=0f172a' },
    { id: 'heroe', url: 'https://api.dicebear.com/9.x/adventurer/svg?seed=Hero&backgroundColor=0f172a' },
    { id: 'bruja', url: 'https://api.dicebear.com/9.x/adventurer/svg?seed=Lilith&backgroundColor=0f172a' },
    { id: 'cyborg', url: 'https://api.dicebear.com/9.x/bottts/svg?seed=Cyborg&backgroundColor=0f172a' }
  ];
  
  const selectorContainer = document.getElementById('avatar-selector');
  const hiddenInput = document.getElementById('selected_avatar');

  // Asegurarnos de que el contenedor tenga un buen diseño grid/flex
  selectorContainer.className = 'grid grid-cols-3 sm:grid-cols-6 gap-4 justify-items-center';

  // Renderizar avatares interactivos
  avatars.forEach((avatar, index) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    // Clases base para el avatar: círculo oscuro, borde sutil, animación hover
    btn.className = 'relative w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-900 border-2 border-gray-700 overflow-hidden hover:scale-110 hover:border-purple-400 transition-all duration-300 focus:outline-none group shadow-lg';
    
    const img = document.createElement('img');
    img.src = avatar.url;
    img.alt = avatar.id;
    img.className = 'w-full h-full object-cover';
    btn.appendChild(img);
    
    // Seleccionar el primero por defecto
    if (index === 0) {
      btn.classList.add('ring-4', 'ring-purple-500', 'ring-offset-2', 'ring-offset-gray-900', 'shadow-[0_0_20px_rgba(168,85,247,0.7)]', 'border-transparent');
      btn.classList.remove('border-gray-700');
      hiddenInput.value = avatar.url;
    }

    btn.addEventListener('click', () => {
      // Limpiar selección previa
      document.querySelectorAll('#avatar-selector button').forEach(b => {
        b.classList.remove('ring-4', 'ring-purple-500', 'ring-offset-2', 'ring-offset-gray-900', 'shadow-[0_0_20px_rgba(168,85,247,0.7)]', 'border-transparent');
        b.classList.add('border-gray-700');
      });
      // Aplicar estado activo al seleccionado (borde brillante estilo gaming)
      btn.classList.remove('border-gray-700');
      btn.classList.add('ring-4', 'ring-purple-500', 'ring-offset-2', 'ring-offset-gray-900', 'shadow-[0_0_20px_rgba(168,85,247,0.7)]', 'border-transparent');
      hiddenInput.value = avatar.url;
    });

    selectorContainer.appendChild(btn);
  });

  // Validación de Contraseñas antes de enviar
  const form = document.getElementById('register-form');
  form.addEventListener('submit', (e) => {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('confirm_password').value;
    const errorMsg = document.getElementById('password-error');
    
    if (p1 !== p2) {
      e.preventDefault();
      errorMsg.classList.remove('hidden');
      document.getElementById('confirm_password').classList.add('border-red-500', 'focus:ring-red-500');
    } else {
      errorMsg.classList.add('hidden');
      document.getElementById('confirm_password').classList.remove('border-red-500', 'focus:ring-red-500');
    }
  });
});
</script>
</body>
</html>
