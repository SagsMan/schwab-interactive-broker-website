-- ============================================================
--  Schwab Interactive Broker — Complete Database Schema
--  Platform: schwabinteractivebroker.com
--  Generated: 2026-06-12
--  PostgreSQL (Replit default) + MySQL equivalent at bottom
-- ============================================================

-- ─── Users ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id              SERIAL PRIMARY KEY,
    full_name       VARCHAR(200) NOT NULL,
    email           VARCHAR(200) UNIQUE NOT NULL,
    phone           VARCHAR(30),
    country         VARCHAR(100),
    gender          VARCHAR(10),
    address         TEXT,
    password_hash   VARCHAR(255) NOT NULL,
    role            VARCHAR(20)  NOT NULL DEFAULT 'user',
    balance         NUMERIC(15,2) NOT NULL DEFAULT 0.00,
    total_profit    NUMERIC(15,2) NOT NULL DEFAULT 0.00,
    bonus           NUMERIC(15,2) NOT NULL DEFAULT 0.00,
    referral_bonus  NUMERIC(15,2) NOT NULL DEFAULT 0.00,
    withdrawals     NUMERIC(15,2) NOT NULL DEFAULT 0.00,
    referral_code   VARCHAR(20)   UNIQUE,
    referred_by     INT REFERENCES users(id) ON DELETE SET NULL,
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    is_restricted   BOOLEAN NOT NULL DEFAULT FALSE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ─── Transactions ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS transactions (
    id          SERIAL PRIMARY KEY,
    user_id     INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type        VARCHAR(30) NOT NULL,
    amount      NUMERIC(15,2) NOT NULL,
    status      VARCHAR(20) NOT NULL DEFAULT 'pending',
    description TEXT,
    coin        VARCHAR(20),
    wallet      VARCHAR(255),
    created_at  TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ─── Trading Plans ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS trading_plans (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    min_amount    NUMERIC(15,2) NOT NULL,
    max_amount    NUMERIC(15,2) NOT NULL,
    daily_return  NUMERIC(5,2) NOT NULL,
    duration_days INT NOT NULL,
    description   TEXT,
    is_active     BOOLEAN NOT NULL DEFAULT TRUE
);

-- ─── User Plans ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS user_plans (
    id            SERIAL PRIMARY KEY,
    user_id       INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    plan_id       INT NOT NULL REFERENCES trading_plans(id),
    amount        NUMERIC(15,2) NOT NULL,
    profit_earned NUMERIC(15,2) NOT NULL DEFAULT 0.00,
    status        VARCHAR(20) NOT NULL DEFAULT 'active',
    started_at    TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    ends_at       TIMESTAMPTZ NOT NULL
);

-- ─── Notifications ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS notifications (
    id         SERIAL PRIMARY KEY,
    user_id    INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title      VARCHAR(200) NOT NULL,
    message    TEXT NOT NULL,
    is_read    BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ─── Support Tickets ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS support_tickets (
    id         SERIAL PRIMARY KEY,
    user_id    INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    subject    VARCHAR(200) NOT NULL,
    message    TEXT NOT NULL,
    status     VARCHAR(20) NOT NULL DEFAULT 'open',
    reply      TEXT,
    replied_at TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ─── Email Logs ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS email_logs (
    id         SERIAL PRIMARY KEY,
    user_id    INT REFERENCES users(id) ON DELETE SET NULL,
    to_email   VARCHAR(200) NOT NULL,
    subject    VARCHAR(200) NOT NULL,
    body       TEXT,
    sent_at    TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ─── Mining Sessions ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS mining_sessions (
    id          SERIAL PRIMARY KEY,
    user_id     INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type        VARCHAR(50) NOT NULL,
    status      VARCHAR(20) NOT NULL DEFAULT 'active',
    started_at  TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ─── Indexes ─────────────────────────────────────────────────
CREATE INDEX IF NOT EXISTS idx_tx_user_id   ON transactions(user_id);
CREATE INDEX IF NOT EXISTS idx_tx_status    ON transactions(status);
CREATE INDEX IF NOT EXISTS idx_notif_user   ON notifications(user_id);
CREATE INDEX IF NOT EXISTS idx_notif_read   ON notifications(is_read);
CREATE INDEX IF NOT EXISTS idx_ticket_user  ON support_tickets(user_id);
CREATE INDEX IF NOT EXISTS idx_ticket_stat  ON support_tickets(status);
CREATE INDEX IF NOT EXISTS idx_mining_user  ON mining_sessions(user_id);
CREATE INDEX IF NOT EXISTS idx_uplan_user   ON user_plans(user_id);
CREATE INDEX IF NOT EXISTS idx_users_email  ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_ref    ON users(referral_code);

-- ─── Seed: Admin User ────────────────────────────────────────
-- Email:    admin@schwabbroker.com
-- Password: Admin@2026Schwab
-- Generate fresh hash: php -r "echo password_hash('Admin@2026Schwab', PASSWORD_BCRYPT);"
INSERT INTO users (full_name, email, password_hash, role, referral_code)
SELECT 'Administrator','admin@schwabbroker.com',
       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
       'admin','ADMIN001'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='admin@schwabbroker.com');

-- ─── Seed: Trading Plans ─────────────────────────────────────
INSERT INTO trading_plans (name, min_amount, max_amount, daily_return, duration_days, description)
SELECT * FROM (VALUES
  ('Starter Plan',  100.00,   999.99,  1.5::numeric, 7,  'Perfect for beginners. Low risk, steady daily returns.'),
  ('Silver Plan',  1000.00,  4999.99,  2.5::numeric, 14, 'Balanced plan for intermediate investors.'),
  ('Gold Plan',    5000.00, 19999.99,  3.5::numeric, 21, 'High-yield plan for serious investors.'),
  ('Platinum Plan',20000.00,999999.99, 5.0::numeric, 30, 'Elite plan with maximum daily returns.')
) AS v(name, min_amount, max_amount, daily_return, duration_days, description)
WHERE NOT EXISTS (SELECT 1 FROM trading_plans LIMIT 1);

-- ============================================================
--  CRYPTO DEPOSIT WALLET ADDRESSES
-- ============================================================
-- Bitcoin  (BTC):  bc1qk9dxqh9yv2cvymvfnj0stndcjnzch3a28ffe2t
-- Ethereum (ETH):  0xa60843feE63458c292cecA5569a83c3F7125a084
-- Solana   (SOL):  CsrdQNRw2PpNNUnjo53sQorx7UVRyxnhZaV3sAzEh2v
-- BNB:             0xa60843feE63458c292cecA5569a83c3F7125a084
-- USDT (ERC20):    0xa60843feE63458c292cecA5569a83c3F7125a084
-- USDC:            0xa60843feE63458c292cecA5569a83c3F7125a084
-- TWT:             0xa60843feE63458c292cecA5569a83c3F7125a084

-- ============================================================
--  ADMIN LOGIN CREDENTIALS
--  Email:    admin@schwabbroker.com
--  Password: Admin@2026Schwab
--  !! CHANGE THIS IMMEDIATELY AFTER DEPLOYMENT !!
-- ============================================================

-- ============================================================
--  MySQL / MariaDB EQUIVALENT (for cPanel shared hosting)
--  Use this section if your host uses MySQL, not PostgreSQL
-- ============================================================
/*

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(200) UNIQUE NOT NULL,
    phone VARCHAR(30),
    country VARCHAR(100),
    gender VARCHAR(10),
    address TEXT,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    balance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total_profit DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    bonus DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    referral_bonus DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    withdrawals DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    referral_code VARCHAR(20) UNIQUE,
    referred_by INT DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_restricted TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (referred_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(30) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    description TEXT,
    coin VARCHAR(20),
    wallet VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE trading_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    min_amount DECIMAL(15,2) NOT NULL,
    max_amount DECIMAL(15,2) NOT NULL,
    daily_return DECIMAL(5,2) NOT NULL,
    duration_days INT NOT NULL,
    description TEXT,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    profit_earned DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    started_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ends_at TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES trading_plans(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'open',
    reply TEXT,
    replied_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    to_email VARCHAR(200) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    body TEXT,
    sent_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE mining_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    started_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Admin (replace hash with: php -r "echo password_hash('Admin@2026Schwab', PASSWORD_BCRYPT);")
INSERT INTO users (full_name, email, password_hash, role, referral_code)
VALUES ('Administrator', 'admin@schwabbroker.com', 'REPLACE_WITH_BCRYPT_HASH', 'admin', 'ADMIN001');

-- Seed Plans
INSERT INTO trading_plans (name, min_amount, max_amount, daily_return, duration_days, description) VALUES
('Starter Plan',  100.00,   999.99,  1.5, 7,  'Perfect for beginners. Low risk, steady daily returns.'),
('Silver Plan',  1000.00,  4999.99,  2.5, 14, 'Balanced plan for intermediate investors.'),
('Gold Plan',    5000.00, 19999.99,  3.5, 21, 'High-yield plan for serious investors.'),
('Platinum Plan',20000.00,999999.99, 5.0, 30, 'Elite plan with maximum daily returns.');

*/
-- ============================================================
--  END OF SCHEMA
-- ============================================================
