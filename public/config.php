<?php
/**
 * ╔══════════════════════════════════════════════════════════╗
 *  Schwab Interactive Broker — cPanel Configuration File
 *  Copy this file to the server's public_html/ and edit it.
 * ╚══════════════════════════════════════════════════════════╝
 *
 * SETUP STEPS FOR cPANEL:
 *  1. Create a MySQL database via cPanel → MySQL Databases
 *  2. Create a database user and assign full privileges
 *  3. Import database.sql (MySQL section) via phpMyAdmin
 *  4. Update the values below with your cPanel DB credentials
 *  5. Uncomment the putenv() lines
 *  6. Done! Visit your domain.
 */

// ── Uncomment and fill in for MySQL (cPanel) ─────────────────────────────
// putenv('MYSQL_HOST=localhost');
// putenv('MYSQL_PORT=3306');
// putenv('MYSQL_DATABASE=YOUR_CPANEL_DB_NAME');    // e.g. sugarmum_schwab
// putenv('MYSQL_USER=YOUR_CPANEL_DB_USER');        // e.g. sugarmum_admin
// putenv('MYSQL_PASSWORD=YOUR_DB_PASSWORD');

// ── App Settings ──────────────────────────────────────────────────────────
// putenv('APP_ENV=production');
// putenv('APP_URL=https://schwabinteractivebroker.com');

// ── Session Secret ────────────────────────────────────────────────────────
// ini_set('session.cookie_secure', '1');  // Enable on HTTPS
// ini_set('session.cookie_httponly', '1');
