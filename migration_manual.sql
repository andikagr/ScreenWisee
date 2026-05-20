-- Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP,
    password VARCHAR(255),
    role VARCHAR(255) DEFAULT 'siswa' NOT NULL,
    google_id VARCHAR(255) UNIQUE,
    google_token VARCHAR(255),
    profile_photo_path VARCHAR(255),
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions (user_id);
CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions (last_activity);

-- Tabel Cache
CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS cache_expiration_index ON cache (expiration);

CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS cache_locks_expiration_index ON cache_locks (expiration);

-- Tabel Jobs
CREATE TABLE IF NOT EXISTS jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS jobs_queue_index ON jobs (queue);

CREATE TABLE IF NOT EXISTS job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT,
    cancelled_at INTEGER,
    created_at INTEGER NOT NULL,
    finished_at INTEGER
);

CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- Tabel Pretests
CREATE TABLE IF NOT EXISTS pretests (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    avg_screen_time DOUBLE PRECISION NOT NULL,
    sleep_time VARCHAR(255) NOT NULL,
    wake_time VARCHAR(255) NOT NULL,
    gadget_habits JSONB,
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pretests_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Tabel Posttests
CREATE TABLE IF NOT EXISTS posttests (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    avg_screen_time DOUBLE PRECISION NOT NULL,
    sleep_time VARCHAR(255) NOT NULL,
    wake_time VARCHAR(255) NOT NULL,
    gadget_habits JSONB,
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT posttests_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Tabel Challenges
CREATE TABLE IF NOT EXISTS challenges (
    id BIGSERIAL PRIMARY KEY,
    day_number INTEGER NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    challenge_date DATE,
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT challenges_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE
);

-- Tabel Daily Trackings
CREATE TABLE IF NOT EXISTS daily_trackings (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    tracking_date DATE NOT NULL,
    screen_time_hours DOUBLE PRECISION NOT NULL,
    activities JSONB,
    challenge_checklist JSONB,
    screenshot_path VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT daily_trackings_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT daily_trackings_user_id_tracking_date_unique UNIQUE (user_id, tracking_date)
);

-- Tabel Migrations history (Menandai bahwa semua ini sudah dijalankan)
CREATE TABLE IF NOT EXISTS migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

INSERT INTO migrations (migration, batch) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('0001_01_02_000000_create_pretests_table', 1),
('0001_01_02_000001_create_posttests_table', 1),
('0001_01_02_000002_create_challenges_table', 1),
('0001_01_02_000003_create_daily_trackings_table', 1),
('2026_05_01_185855_add_profile_photo_path_to_users_table', 1),
('2026_05_14_115339_add_google_id_to_users_table', 1);
