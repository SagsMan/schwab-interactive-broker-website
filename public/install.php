<?php
/**
 * ╔══════════════════════════════════════════════════════════════════╗
 *  Schwab Interactive Broker — cPanel Self-Installer
 *
 *  HOW TO USE:
 *   1. Upload ONLY this file to public_html/ via cPanel File Manager
 *   2. Visit: https://yourdomain.com/install.php
 *   3. Enter your GitHub token (or leave blank for public repo)
 *   4. The installer clones the repo and sets up the platform
 *
 *  DELETE THIS FILE after setup is complete!
 * ╚══════════════════════════════════════════════════════════════════╝
 */

define('INSTALLER_PASSWORD', 'schwab2026install');
define('GITHUB_REPO',        'SagsMan/schwab-interactive-broker-website');
define('GITHUB_BRANCH',      'main');
define('HOME_DIR',           dirname(__DIR__));   // one above public_html

$errors = [];
$done   = [];

// ── Auth ─────────────────────────────────────────────────────────────────────
session_start();
if ($_POST['installer_pass'] ?? '' === INSTALLER_PASSWORD) {
    $_SESSION['installer_auth'] = true;
}
$authed = !empty($_SESSION['installer_auth']);

// ── Actions ──────────────────────────────────────────────────────────────────
if ($authed && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'fetch_files') {
        // Download all project files from GitHub API and place them
        $token = trim($_POST['github_token'] ?? '');
        $headers = ['User-Agent: SchwabInstaller/1.0'];
        if ($token) $headers[] = "Authorization: Bearer $token";

        function ghApi(string $path, array $headers): array {
            $ctx = stream_context_create(['http'=>['header'=>implode("\r\n",$headers),'timeout'=>30]]);
            $raw = @file_get_contents("https://api.github.com$path", false, $ctx);
            return $raw ? json_decode($raw, true) : [];
        }

        function downloadFiles(string $repoPath, string $localDir, array $headers, array &$done, array &$errors): void {
            $items = ghApi("/repos/".GITHUB_REPO."/contents/$repoPath?ref=".GITHUB_BRANCH, $headers);
            if (empty($items)) { $errors[] = "Cannot list $repoPath"; return; }
            foreach ($items as $item) {
                if ($item['type'] === 'dir') {
                    $subDir = $localDir.'/'.$item['name'];
                    if (!is_dir($subDir)) mkdir($subDir, 0755, true);
                    downloadFiles($item['path'], $subDir, $headers, $done, $errors);
                } elseif ($item['type'] === 'file') {
                    $content = base64_decode($item['content'] ?? '');
                    if (empty($content) && !empty($item['download_url'])) {
                        $ctx = stream_context_create(['http'=>['header'=>implode("\r\n",$headers),'timeout'=>30]]);
                        $content = @file_get_contents($item['download_url'], false, $ctx);
                    }
                    $dest = $localDir.'/'.$item['name'];
                    if (file_put_contents($dest, $content) !== false) {
                        $done[] = $dest;
                    } else {
                        $errors[] = "Cannot write: $dest";
                    }
                }
            }
        }

        // Download public/ files here (into public_html/)
        downloadFiles('public', __DIR__, $headers, $done, $errors);

        // Download app/ into HOME_DIR/app/
        $appDir = HOME_DIR.'/app';
        if (!is_dir($appDir)) mkdir($appDir, 0755, true);
        downloadFiles('app', $appDir, $headers, $done, $errors);

        // Download database.sql
        $sqlData = ghApi('/repos/'.GITHUB_REPO.'/contents/database.sql?ref='.GITHUB_BRANCH, $headers);
        if (!empty($sqlData['content'])) {
            file_put_contents(HOME_DIR.'/database.sql', base64_decode($sqlData['content']));
            $done[] = HOME_DIR.'/database.sql';
        }
    }

    if ($action === 'setup_db') {
        $host = trim($_POST['db_host'] ?? 'localhost');
        $name = trim($_POST['db_name'] ?? '');
        $user = trim($_POST['db_user'] ?? '');
        $pass = trim($_POST['db_pass'] ?? '');

        if (!$name || !$user) {
            $errors[] = 'Database name and user are required.';
        } else {
            // Write env config to app/config/env.php
            $envFile = HOME_DIR.'/app/config/env.php';
            $envContent = "<?php\nputenv('MYSQL_HOST=$host');\nputenv('MYSQL_PORT=3306');\nputenv('MYSQL_DATABASE=$name');\nputenv('MYSQL_USER=$user');\nputenv('MYSQL_PASSWORD=$pass');\n";
            if (file_put_contents($envFile, $envContent)) {
                $done[] = "Database config written to $envFile";
                // Verify connection
                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass);
                    $done[] = '✓ Database connection successful';
                } catch (PDOException $e) {
                    $errors[] = 'DB connection failed: '.$e->getMessage();
                }
            } else {
                $errors[] = "Cannot write $envFile";
            }
        }
    }
}

$step = isset($_POST['action']) ? $_POST['action'] : 'start';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Schwab Broker — Installer</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#0a0d1f;color:#e0e6f0;font-family:system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
.card{background:#0f1535;border:1px solid #1e2d5a;border-radius:16px;padding:2.5rem;width:100%;max-width:540px}
h1{font-size:1.5rem;color:#00d4c8;margin-bottom:.25rem}
h2{font-size:1rem;color:#7090c0;margin-bottom:2rem;font-weight:400}
.badge{display:inline-block;background:#00d4c8;color:#0a0d1f;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:.5rem;vertical-align:middle}
label{display:block;font-size:.8rem;color:#7090c0;margin-bottom:.4rem;margin-top:1.2rem}
input[type=text],input[type=password]{width:100%;background:#0a0d1f;border:1px solid #1e2d5a;border-radius:8px;padding:.7rem 1rem;color:#e0e6f0;font-size:.95rem}
input:focus{outline:none;border-color:#00d4c8}
.btn{display:block;width:100%;background:#00d4c8;color:#0a0d1f;border:none;border-radius:8px;padding:.85rem;font-size:1rem;font-weight:700;cursor:pointer;margin-top:1.5rem}
.btn:hover{background:#00b8ad}
.alert{border-radius:8px;padding:.8rem 1rem;font-size:.85rem;margin-bottom:1rem}
.alert-ok{background:#0d3b2e;border:1px solid #00d4c8;color:#00d4c8}
.alert-err{background:#3b0d0d;border:1px solid #ff6b6b;color:#ff6b6b}
.step-list{list-style:none;counter-reset:steps}
.step-list li{counter-increment:steps;padding:.6rem 0;border-bottom:1px solid #1e2d5a;font-size:.9rem;color:#a0b4d0}
.step-list li::before{content:counter(steps)". ";color:#00d4c8;font-weight:700}
.warning{background:#2d2000;border:1px solid #ffa500;color:#ffa500;border-radius:8px;padding:.8rem 1rem;font-size:.8rem;margin-top:1.5rem}
</style>
</head>
<body>
<div class="card">
  <h1>🐬 Schwab Interactive Broker</h1>
  <h2>cPanel Installer <span class="badge">SETUP</span></h2>

<?php if (!$authed): ?>
  <form method="POST">
    <label>Installer Password</label>
    <input type="password" name="installer_pass" placeholder="Enter installer password" autofocus>
    <button class="btn" type="submit">Unlock Installer</button>
  </form>

<?php else: ?>

  <?php foreach ($errors as $e): ?>
    <div class="alert alert-err">✗ <?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>
  <?php foreach ($done as $d): ?>
    <div class="alert alert-ok">✓ <?= htmlspecialchars($d) ?></div>
  <?php endforeach; ?>

  <ol class="step-list">
    <li>Download all platform files from GitHub</li>
    <li>Configure MySQL database connection</li>
    <li>Import database schema via phpMyAdmin</li>
    <li>Delete this installer file</li>
  </ol>

  <hr style="border-color:#1e2d5a;margin:1.5rem 0">

  <!-- Step 1: Fetch files -->
  <form method="POST">
    <input type="hidden" name="action" value="fetch_files">
    <strong style="color:#00d4c8">Step 1 — Download Files from GitHub</strong>
    <label>GitHub PAT (optional — only needed if repo is private)</label>
    <input type="text" name="github_token" placeholder="ghp_xxxxxxxxxxxx (leave blank for public repo)">
    <button class="btn" type="submit">📥 Download &amp; Install Files</button>
  </form>

  <hr style="border-color:#1e2d5a;margin:1.5rem 0">

  <!-- Step 2: DB Config -->
  <form method="POST">
    <input type="hidden" name="action" value="setup_db">
    <strong style="color:#00d4c8">Step 2 — Configure MySQL Database</strong>
    <p style="font-size:.8rem;color:#7090c0;margin-top:.5rem">Create the DB in cPanel → MySQL Databases first, then import database.sql via phpMyAdmin.</p>
    <label>DB Host</label>
    <input type="text" name="db_host" value="localhost">
    <label>Database Name <span style="color:#ff6b6b">*</span></label>
    <input type="text" name="db_name" placeholder="e.g. sugarmum_schwab">
    <label>Database User <span style="color:#ff6b6b">*</span></label>
    <input type="text" name="db_user" placeholder="e.g. sugarmum_admin">
    <label>Database Password</label>
    <input type="password" name="db_pass">
    <button class="btn" type="submit">🗄️ Save DB Config &amp; Test Connection</button>
  </form>

  <div class="warning">
    ⚠️ <strong>Security:</strong> Delete this file after setup!<br>
    Remove <code>public_html/install.php</code> from cPanel File Manager once complete.
  </div>

<?php endif; ?>
</div>
</body>
</html>
