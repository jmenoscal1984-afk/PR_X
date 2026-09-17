<?php
// pages/login.php
session_start();

// Si ya existe una sesión activa válida, redirige a dashboard.php
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
}

require_once '../includes/db_connect.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        try {
            if (!$pdo) {
                die("Error 500: La conexión a la base de datos (PDO) es nula.");
            }

            $stmt = $pdo->prepare("SELECT id, nombre_completo, rol, password FROM usuarios WHERE correo = :correo LIMIT 1");
            $stmt->execute([':correo' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['password'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                header("Location: dashboard.php");
                exit; 
            } else {
                $error_message = "Credenciales inválidas. Verifica tu correo y contraseña.";
            }
        } catch (PDOException $e) {
            $error_message = "Error de base de datos. Intenta nuevamente.";
        }
    } else {
        $error_message = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión — EduQuest</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome para iconos base -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @keyframes blob {
      0% { transform: translate(0px, 0px) scale(1); }
      33% { transform: translate(30px, -50px) scale(1.1); }
      66% { transform: translate(-20px, 20px) scale(0.9); }
      100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    /* Scrollbar del chat */
    .chat-scroll::-webkit-scrollbar { width: 6px; }
    .chat-scroll::-webkit-scrollbar-track { background: #1f2937; border-radius: 4px;}
    .chat-scroll::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px;}
  </style>
</head>
<body class="bg-gray-950 text-white min-h-screen relative font-sans">
  
  <div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4">
    <!-- Orbes de fondo (Efecto de luz espacial) -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-2000"></div>

    <div class="relative w-full max-w-md z-10">
      <!-- Contenedor Glassmorphism -->
      <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-8 sm:p-10 shadow-[0_0_40px_rgba(0,0,0,0.5)]">
        
        <!-- Cabecera del formulario -->
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 mb-4 shadow-lg shadow-purple-500/30">
            <i class="fas fa-rocket text-2xl text-white"></i>
          </div>
          <h1 class="text-3xl font-extrabold text-white tracking-tight">¡Bienvenido de nuevo, Héroe!</h1>
          <p class="text-gray-400 mt-2 text-sm">Ingresa a tu nave para continuar acumulando XP.</p>
        </div>

        <?php if (!empty($error_message)): ?>
          <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center flex items-center justify-center space-x-2">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?= htmlspecialchars($error_message) ?></span>
          </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6">
          <!-- Correo -->
          <div class="relative">
            <label for="correo" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Correo Electrónico</label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- SVG Icono Correo -->
                <svg class="w-5 h-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
              </div>
              <input type="email" id="correo" name="correo" required 
                class="block w-full pl-10 pr-3 py-3 border border-gray-700 rounded-xl leading-5 bg-slate-900/80 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                placeholder="capitan@nave.com">
            </div>
          </div>

          <!-- Contraseña -->
          <div class="relative">
            <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Contraseña de Acceso</label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- SVG Icono Candado -->
                <svg class="w-5 h-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
              </div>
              <input type="password" id="password" name="password" required 
                class="block w-full pl-10 pr-3 py-3 border border-gray-700 rounded-xl leading-5 bg-slate-900/80 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                placeholder="••••••••">
            </div>
          </div>

          <!-- Botón de Envío -->
          <button type="submit" class="w-full flex items-center justify-center py-3.5 px-4 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-purple-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_0_25px_rgba(147,51,234,0.6)]">
            <i class="fas fa-bolt mr-2"></i> Iniciar Misión
          </button>
        </form>

        <!-- Separador -->
        <div class="mt-8 flex items-center justify-center space-x-4">
          <div class="h-px bg-gray-700 w-full"></div>
          <span class="text-xs text-gray-500 uppercase tracking-widest whitespace-nowrap">o continúa con</span>
          <div class="h-px bg-gray-700 w-full"></div>
        </div>

        <!-- Botón Google -->
        <div class="mt-6">
          <button type="button" class="w-full flex items-center justify-center py-3 px-4 border border-gray-700 rounded-xl bg-gray-800/80 hover:bg-gray-700 text-sm font-medium text-white transition-all duration-200 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-900">
            <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
              <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
              <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
              <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Iniciar sesión con Google
          </button>
        </div>

        <!-- Enlaces Inferiores -->
        <div class="mt-8 pt-6 border-t border-gray-700/50 flex flex-col items-center space-y-3">
          <p class="text-sm text-gray-400">
            ¿Aún no eres un héroe? <a href="register.php" class="font-medium text-blue-400 hover:text-blue-300 transition-colors">Regístrate gratis</a>
          </p>
          <a href="../index.php" class="text-xs text-gray-500 hover:text-gray-300 transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Volver a la base
          </a>
        </div>

      </div>
    </div>
  </div>

  <!-- Chatbot Widget -->
  <div id="chatbot-widget" class="fixed bottom-6 right-6 z-50">
    <!-- Botón Flotante -->
    <button id="chatbot-btn" class="relative bg-gradient-to-br from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 w-16 h-16 rounded-full shadow-[0_0_20px_rgba(147,51,234,0.5)] flex items-center justify-center transition-transform hover:scale-110 focus:outline-none">
      <i class="fas fa-robot text-2xl text-white"></i>
      <!-- Badge Estado -->
      <span class="absolute bottom-1 right-1 flex h-4 w-4">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500 border-2 border-gray-900"></span>
      </span>
    </button>

    <!-- Panel de Chat -->
    <div id="chatbot-panel" class="hidden absolute bottom-20 right-0 w-80 bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl overflow-hidden flex flex-col transform transition-all duration-300 origin-bottom-right">
      <!-- Header Bot -->
      <div class="bg-gradient-to-r from-gray-800 to-gray-900 border-b border-gray-700 p-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 rounded-full bg-purple-900 flex items-center justify-center overflow-hidden border border-purple-500/50">
             <img src="https://api.dicebear.com/9.x/bottts/svg?seed=Astro&backgroundColor=0f172a" alt="Bot Avatar" class="w-full h-full object-cover">
          </div>
          <div>
            <h3 class="text-white font-bold text-sm">EduBot Alpha</h3>
            <p class="text-xs text-green-400 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-1"></span>En línea</p>
          </div>
        </div>
        <button id="chatbot-close" class="text-gray-400 hover:text-white transition-colors focus:outline-none">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <!-- Mensajes -->
      <div id="chat-messages" class="h-64 overflow-y-auto p-4 space-y-4 chat-scroll bg-gray-950/50">
        <!-- Mensaje Bot (Inicial) -->
        <div class="flex items-start space-x-2">
          <div class="bg-gray-800 border border-gray-700 text-sm text-gray-200 p-3 rounded-tr-xl rounded-br-xl rounded-bl-xl shadow-md max-w-[85%]">
            ¡Hola, futuro Héroe! 🚀 Soy EduBot. ¿Tienes dudas de cómo acceder o recuperar tu XP?
          </div>
        </div>
      </div>
      
      <!-- Opciones Rápidas -->
      <div class="px-4 py-2 bg-gray-900/80 flex flex-wrap gap-2 text-xs">
        <button class="chat-faq-btn bg-gray-800 hover:bg-gray-700 border border-gray-600 rounded-full px-3 py-1 text-purple-300 transition-colors">¿Recuperar clave?</button>
        <button class="chat-faq-btn bg-gray-800 hover:bg-gray-700 border border-gray-600 rounded-full px-3 py-1 text-purple-300 transition-colors">¿Cómo gano XP?</button>
      </div>

      <!-- Input Área -->
      <div class="p-3 bg-gray-800 border-t border-gray-700 flex items-center space-x-2">
        <input type="text" id="chat-input" placeholder="Escribe tu mensaje..." class="flex-1 bg-gray-900 border border-gray-700 text-sm rounded-full px-4 py-2 text-white focus:outline-none focus:border-purple-500">
        <button id="chat-send" class="w-9 h-9 rounded-full bg-purple-600 hover:bg-purple-500 text-white flex items-center justify-center focus:outline-none shadow-lg">
          <i class="fas fa-paper-plane text-xs"></i>
        </button>
      </div>
    </div>
  </div>

  <script>
    // Lógica del Chatbot
    document.addEventListener('DOMContentLoaded', () => {
      const chatBtn = document.getElementById('chatbot-btn');
      const chatPanel = document.getElementById('chatbot-panel');
      const chatClose = document.getElementById('chatbot-close');
      const chatMessages = document.getElementById('chat-messages');
      const chatInput = document.getElementById('chat-input');
      const chatSend = document.getElementById('chat-send');
      const faqBtns = document.querySelectorAll('.chat-faq-btn');

      // Respuestas predefinidas
      const botResponses = {
        "¿Recuperar clave?": "Si olvidaste tu clave, contacta al profesor de tu materia para que te asigne un código de recuperación temporal. 🔐",
        "¿Cómo gano XP?": "¡Completando misiones y respondiendo cuestionarios correctamente! Cada acierto suma puntos a tu perfil de Héroe. ⭐",
        "default": "Actualmente soy un bot en fase Beta. No entiendo todo, pero estoy aquí para guiarte en tu aventura espacial. 🌌"
      };

      // Toggle Chat
      chatBtn.addEventListener('click', () => {
        chatPanel.classList.toggle('hidden');
        if (!chatPanel.classList.contains('hidden')) {
          chatInput.focus();
        }
      });

      chatClose.addEventListener('click', () => {
        chatPanel.classList.add('hidden');
      });

      // Añadir mensaje a la UI
      function appendMessage(text, sender = 'user') {
        const msgDiv = document.createElement('div');
        msgDiv.className = `flex items-start ${sender === 'user' ? 'justify-end' : ''} space-x-2`;
        
        const innerDiv = document.createElement('div');
        innerDiv.className = `text-sm p-3 shadow-md max-w-[85%] ${
          sender === 'user' 
          ? 'bg-purple-600 text-white rounded-tl-xl rounded-bl-xl rounded-br-xl' 
          : 'bg-gray-800 border border-gray-700 text-gray-200 rounded-tr-xl rounded-br-xl rounded-bl-xl'
        }`;
        innerDiv.innerText = text;
        
        msgDiv.appendChild(innerDiv);
        chatMessages.appendChild(msgDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll
      }

      // Respuesta del bot simulada
      function processBotReply(userText) {
        // Simular retraso natural
        setTimeout(() => {
          let reply = botResponses[userText] || botResponses["default"];
          // Buscar palabras clave básicas
          if (userText.toLowerCase().includes('xp') || userText.toLowerCase().includes('experiencia')) {
            reply = botResponses["¿Cómo gano XP?"];
          }
          if (userText.toLowerCase().includes('clave') || userText.toLowerCase().includes('password')) {
            reply = botResponses["¿Recuperar clave?"];
          }
          appendMessage(reply, 'bot');
        }, 600);
      }

      // Enviar mensaje manual
      const handleSend = () => {
        const text = chatInput.value.trim();
        if (text) {
          appendMessage(text, 'user');
          chatInput.value = '';
          processBotReply(text);
        }
      };

      chatSend.addEventListener('click', handleSend);
      chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') handleSend();
      });

      // Botones rápidos FAQ
      faqBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const text = btn.innerText;
          appendMessage(text, 'user');
          processBotReply(text);
        });
      });
    });
  </script>
</body>
</html>
