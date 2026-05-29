-- ============================================================
-- Gamification Schema — Fase 5
-- Run this AFTER the main schema.sql is applied
-- ============================================================

-- ── 1. Puntos de Usuario ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS user_points (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    points        INT          NOT NULL DEFAULT 0,
    reason        VARCHAR(120) NOT NULL,  -- 'lesson_complete', 'quiz_pass', 'course_complete', 'streak_bonus'
    reference_id  INT DEFAULT NULL, -- ID del curso / lección / quiz relacionado
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 2. Racha Diaria (Streak) ─────────────────────────────────
CREATE TABLE IF NOT EXISTS user_streaks (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL UNIQUE,
    current_streak  INT NOT NULL DEFAULT 0,
    longest_streak  INT NOT NULL DEFAULT 0,
    last_activity   DATE DEFAULT NULL,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 3. Catálogo de Badges / Logros ───────────────────────────
CREATE TABLE IF NOT EXISTS badges (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(80)  NOT NULL UNIQUE,
    name        VARCHAR(100) NOT NULL,
    description TEXT         NOT NULL,
    icon        VARCHAR(80)  NOT NULL DEFAULT 'fa-trophy',  -- Font Awesome icon class
    color       VARCHAR(20)  NOT NULL DEFAULT '#fbbf24',    -- CSS color
    points_cost INT          NOT NULL DEFAULT 0,            -- 0 = automatic award
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 4. Badges otorgados a Usuarios ───────────────────────────
CREATE TABLE IF NOT EXISTS user_badges (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    badge_id   INT NOT NULL,
    awarded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_badge (user_id, badge_id),
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 5. API Keys ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS api_keys (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    api_key    VARCHAR(64)  NOT NULL UNIQUE,
    label      VARCHAR(80)  NOT NULL DEFAULT 'Mi API Key',
    is_active  TINYINT(1)   NOT NULL DEFAULT 1,
    last_used  TIMESTAMP    NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 6. Seed — Badges por defecto ─────────────────────────────
INSERT IGNORE INTO badges (slug, name, description, icon, color) VALUES
('first_login',       'Primer Paso',         'Iniciaste sesión por primera vez.',                             'fa-door-open',     '#10b981'),
('first_lesson',      'Curiosidad Activa',   'Completaste tu primera lección.',                               'fa-book-open',     '#38bdf8'),
('first_course',      'Graduado',            'Completaste tu primer curso completo.',                         'fa-graduation-cap','#fbbf24'),
('streak_7',          'Semana de Fuego',     'Mantuviste una racha de 7 días consecutivos de estudio.',       'fa-fire',          '#f97316'),
('streak_30',         'Imparable',           'Mantuviste una racha de 30 días consecutivos de estudio.',      'fa-bolt',          '#a855f7'),
('quiz_ace',          'Mente Brillante',     'Obtuviste 100% en un examen.',                                  'fa-brain',         '#ec4899'),
('five_courses',      'Coleccionista',       'Completaste 5 cursos diferentes.',                              'fa-layer-group',   '#14b8a6'),
('top_leaderboard',   'Leyenda',             'Alcanzaste el top 3 del leaderboard global.',                   'fa-crown',         '#fbbf24'),
('night_owl',         'Búho Nocturno',       'Completaste una lección entre las 00:00 y las 05:00.',          'fa-moon',          '#818cf8'),
('speed_learner',     'Aprendiz Veloz',      'Completaste un curso en menos de 3 días.',                      'fa-gauge-high',    '#f43f5e');
