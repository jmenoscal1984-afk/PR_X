-- ============================================================================
-- 1. TABLA DE USUARIOS (Perfiles)
-- Almacena la información base tanto para alumnos como para profesores.
-- ============================================================================
CREATE TABLE usuarios (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    rol VARCHAR(20) NOT NULL CHECK (rol IN ('alumno', 'profesor')),
    nombre_completo VARCHAR(255) NOT NULL,
    correo VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar TEXT,
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================================
-- 2. TABLA DE AULAS (Gestor para Profesores)
-- Administrada por los profesores para organizar grupos de alumnos.
-- ============================================================================
CREATE TABLE aulas (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    id_profesor UUID NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    nombre_aula VARCHAR(255) NOT NULL,
    codigo_acceso VARCHAR(50) UNIQUE NOT NULL,
    estado VARCHAR(20) DEFAULT 'activa' CHECK (estado IN ('activa', 'inactiva')),
    creado_en TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================================
-- 3. TABLA DE MATRÍCULAS (Relación Alumnos - Aulas)
-- Tabla intermedia para manejar la relación muchos a muchos entre alumnos y aulas.
-- ============================================================================
CREATE TABLE matriculas (
    id_alumno UUID NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    id_aula UUID NOT NULL REFERENCES aulas(id) ON DELETE CASCADE,
    fecha_inscripcion TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_alumno, id_aula)
);

-- ============================================================================
-- 4. TABLA DE PROGRESO Y GAMIFICACIÓN (Para Alumnos)
-- Registra el progreso, niveles y constancia (racha) de cada alumno en la plataforma.
-- ============================================================================
CREATE TABLE progreso (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    id_alumno UUID NOT NULL UNIQUE REFERENCES usuarios(id) ON DELETE CASCADE,
    experiencia_total INTEGER DEFAULT 0 NOT NULL,
    nivel_actual INTEGER DEFAULT 1 NOT NULL,
    racha_dias INTEGER DEFAULT 0 NOT NULL,
    ultima_conexion TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================================
-- 5. TABLA DE MISIONES/JUEGOS
-- Contiene las actividades interactivas. Si id_aula es NULL, el juego es global.
-- ============================================================================
CREATE TABLE misiones (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    id_aula UUID REFERENCES aulas(id) ON DELETE CASCADE,
    titulo_juego VARCHAR(255) NOT NULL,
    descripcion TEXT,
    puntos_recompensa INTEGER DEFAULT 0 NOT NULL,
    creado_en TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);
