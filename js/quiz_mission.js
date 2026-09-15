document.addEventListener('DOMContentLoaded', () => {
    
    // --- DATOS DE LA MISIÓN ESPACIAL ---
    const missionQuestions = [
        {
            question: "¿Cuál es la distancia aproximada de la Tierra a la Luna?",
            options: ["384,400 km", "150,000 km", "1,000,000 km", "90,000 km"],
            correctIndex: 0,
            points: 10
        },
        {
            question: "Para que una nave escape de la gravedad de la Tierra, necesita alcanzar la...",
            options: ["Velocidad de la luz", "Velocidad de escape (11.2 km/s)", "Órbita geoestacionaria", "Velocidad del sonido"],
            correctIndex: 1,
            points: 15
        },
        {
            question: "¿Qué planeta es conocido como el gigante gaseoso con los anillos más prominentes?",
            options: ["Júpiter", "Urano", "Saturno", "Neptuno"],
            correctIndex: 2,
            points: 10
        },
        {
            question: "Un año luz mide...",
            options: ["Tiempo", "Velocidad", "Masa", "Distancia"],
            correctIndex: 3,
            points: 20
        },
        {
            question: "¿Cuál es el nombre de nuestra galaxia?",
            options: ["Andrómeda", "Vía Láctea", "Sombrero", "Triángulo"],
            correctIndex: 1,
            points: 10
        }
    ];

    let currentQuestionIndex = 0;
    let totalScore = 0;
    
    // Referencias al DOM
    const questionText = document.getElementById('question-text');
    const optionsGrid = document.getElementById('options-grid');
    const scoreCounter = document.getElementById('score-counter');
    const progressText = document.getElementById('progress-text');
    const quizContainer = document.getElementById('quiz-container');
    const celebrationModal = document.getElementById('celebration-modal');
    const celebrationContent = document.getElementById('celebration-content');

    // Validación de Supabase y User ID
    const errorModal = document.getElementById('error-modal');
    const errorMessageEl = document.getElementById('error-modal-message');

    function showErrorModal(details) {
        if (errorMessageEl && details) {
            errorMessageEl.innerText = details;
        }
        if (errorModal) {
            errorModal.classList.remove('hidden');
            setTimeout(() => {
                errorModal.classList.remove('opacity-0');
            }, 50);
        }
    }

    if (!window.CURRENT_USER_ID) {
        console.error("No se encontró el ID del usuario. Asegúrate de estar autenticado.");
    }
    if (typeof supabase === 'undefined') {
        const err = "Cliente de Supabase no encontrado de forma global. Verifica que el script de Supabase esté cargado y las variables de entorno configuradas.";
        console.error(err);
        showErrorModal(err);
    }

    // --- LÓGICA DEL JUEGO ---

    function loadQuestion(index) {
        if (index >= missionQuestions.length) {
            finishMission();
            return;
        }

        const q = missionQuestions[index];
        questionText.innerText = q.question;
        progressText.innerText = `${index + 1} / ${missionQuestions.length}`;
        optionsGrid.innerHTML = '';

        q.options.forEach((optionText, i) => {
            const btn = document.createElement('button');
            btn.className = 'neon-btn w-full text-lg font-bold py-6 px-4 rounded-2xl glass-card text-white hover:text-purple-300';
            btn.innerText = optionText;
            
            btn.addEventListener('click', () => handleAnswer(btn, i, q.correctIndex, q.points));
            optionsGrid.appendChild(btn);
        });

        // Pequeña animación de entrada
        quizContainer.classList.remove('opacity-0', 'scale-95');
    }

    function handleAnswer(buttonElement, selectedIndex, correctIndex, pointsEarned) {
        // Deshabilitar botones para evitar múltiples clics
        const allButtons = optionsGrid.querySelectorAll('button');
        allButtons.forEach(btn => btn.disabled = true);

        const isCorrect = (selectedIndex === correctIndex);

        if (isCorrect) {
            buttonElement.classList.add('correct', 'text-green-300');
            totalScore += pointsEarned;
            animateScoreUpdate();
            playSound('correct');
        } else {
            buttonElement.classList.add('incorrect');
            // Marcar también la correcta
            allButtons[correctIndex].classList.add('correct', 'opacity-75');
            playSound('wrong');
        }

        // Transición a la siguiente pregunta
        setTimeout(() => {
            quizContainer.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                currentQuestionIndex++;
                loadQuestion(currentQuestionIndex);
            }, 300);
        }, 1500);
    }

    function animateScoreUpdate() {
        scoreCounter.innerText = totalScore;
        scoreCounter.classList.add('scale-150', 'text-white');
        setTimeout(() => {
            scoreCounter.classList.remove('scale-150', 'text-white');
        }, 300);
    }

    async function finishMission() {
        quizContainer.classList.add('hidden');
        
        // Sincronización con Supabase (Actualiza puntos acumulados)
        // Se asume que en "scores" se guardará o sumará. Como requerimos upsert,
        // necesitamos obtener los puntos actuales o sobrescribir, 
        // aquí guardamos los de esta sesión para ilustrar, o sumamos si ya existen (vía RPC idealmente)
        // Pero usaremos el upsert solicitado por el usuario.
        
        if (window.CURRENT_USER_ID && typeof supabase !== 'undefined') {
            try {
                // Primero obtenemos el puntaje actual para sumar (Opcional, pero recomendado)
                let currentPoints = 0;
                const { data, error: fetchError } = await supabase
                    .from('scores')
                    .select('points')
                    .eq('user_id', window.CURRENT_USER_ID)
                    .single();
                
                if (fetchError && fetchError.code !== 'PGRST116') { // PGRST116 es 'No rows found', lo cual es normal si es nuevo
                    console.error("[Supabase Fetch Error]: Fallo al leer puntos actuales.", fetchError);
                    // No detenemos el flujo, asumimos 0 puntos actuales o puede que falle el upsert después
                }
                
                if (data && data.points) {
                    currentPoints = data.points;
                }

                const newTotal = currentPoints + totalScore;

                // Upsert del nuevo puntaje
                const { error: upsertError } = await supabase
                    .from('scores')
                    .upsert({ 
                        user_id: window.CURRENT_USER_ID, 
                        points: newTotal,
                        updated_at: new Date().toISOString()
                    }, { onConflict: 'user_id' });

                if (upsertError) {
                    console.error("[Supabase Upsert Error]: Error guardando el puntaje. Posible bloqueo por RLS (Row Level Security) o credenciales inválidas:", upsertError);
                    showErrorModal(`Error de guardado: ${upsertError.message || 'Bloqueo RLS o red'}`);
                    return; // Detenemos la celebración
                } else {
                    console.log("Puntaje guardado con éxito. Total:", newTotal);
                }
            } catch (err) {
                console.error("[Excepción de Red/JS]: Error crítico en la sincronización de Supabase:", err);
                showErrorModal("Ocurrió un error inesperado de red al intentar sincronizar tus puntos.");
                return; // Detenemos la celebración
            }
        } else {
            console.error("No se puede sincronizar el puntaje: Supabase no está definido o falta CURRENT_USER_ID.");
            showErrorModal("Sistema desconectado. Falta el cliente de base de datos o sesión de usuario.");
            return;
        }

        // Mostrar Modal de Celebración
        celebrationModal.classList.remove('hidden');
        // Pequeño delay para permitir que el display:block tome efecto antes de la transición de opacidad
        setTimeout(() => {
            celebrationModal.classList.remove('opacity-0');
            celebrationContent.classList.remove('scale-90');
        }, 50);
        
        playSound('celebration');
    }

    // Funciones mock de sonido si no existen globalmente
    function playSound(type) {
        if (typeof window.playSound === 'function') {
            window.playSound(type);
        } else {
            console.log(`[Audio Simulado] Reproduciendo sonido: ${type}`);
        }
    }

    // --- INICIAR MISIÓN ---
    loadQuestion(0);

});
