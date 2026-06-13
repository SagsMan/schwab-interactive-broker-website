<?php
/**
 * Database Configuration — Schwab Interactive Broker
 * Supports: PostgreSQL (Replit/dev), MySQL/MariaDB (cPanel/production)
 *
 * FOR cPANEL / MYSQL SETUP:
 *   Set these environment variables (or edit the fallback values below):
 *   MYSQL_HOST     = localhost
 *   MYSQL_PORT     = 3306
 *   MYSQL_DATABASE = your_db_name
 *   MYSQL_USER     = your_db_user
 *   MYSQL_PASSWORD = your_db_password
 *
 *   Create MySQL DB from cPanel → MySQL Databases
 *   Then run database.sql (MySQL section) via phpMyAdmin
 */

function getDbConnection(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    // ── PostgreSQL via DATABASE_URL (Replit dev) ──────────────────────────
    $pgUrl = getenv('DATABASE_URL');
    if ($pgUrl && str_starts_with($pgUrl, 'postgres')) {
        $p = parse_url($pgUrl);
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $p['host'],
            $p['port'] ?? 5432,
            ltrim($p['path'], '/')
        );
        $pdo = new PDO($dsn, $p['user'], $p['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    }

    // ── PostgreSQL via individual env vars (Replit dev fallback) ─────────
    if (getenv('PGHOST')) {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            getenv('PGHOST'),
            getenv('PGPORT')     ?: '5432',
            getenv('PGDATABASE') ?: 'postgres'
        );
        $pdo = new PDO($dsn, getenv('PGUSER') ?: 'postgres', getenv('PGPASSWORD') ?: '', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    }

    // ── MySQL / MariaDB (cPanel production) ──────────────────────────────
    $mysqlHost = getenv('MYSQL_HOST')     ?: 'localhost';
    $mysqlPort = getenv('MYSQL_PORT')     ?: '3306';
    $mysqlDb   = getenv('MYSQL_DATABASE') ?: 'schwab_db';
    $mysqlUser = getenv('MYSQL_USER')     ?: 'schwab_user';
    $mysqlPass = getenv('MYSQL_PASSWORD') ?: '';

    // Alternatively, hardcode for cPanel (only if env vars not available):
    // $mysqlHost = 'localhost';
    // $mysqlDb   = 'sugarmum_schwab';   // <-- cPanel DB name
    // $mysqlUser = 'sugarmum_admin';    // <-- cPanel DB user
    // $mysqlPass = 'YourDBPassword';    // <-- cPanel DB password

    $dsn = "mysql:host=$mysqlHost;port=$mysqlPort;dbname=$mysqlDb;charset=utf8mb4";
    $pdo = new PDO($dsn, $mysqlUser, $mysqlPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $pdo;
}
