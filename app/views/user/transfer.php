<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Transfer Funds — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div class="topbar-title">Transfer Funds</div>
      <div class="topbar-right">
        <span style="font-size:13px;color:#8892b0">Balance: </span>
        <span style="font-size:14px;font-weight:700;color:#00d4c8"><?= formatMoney((float)$user['balance']) ?></span>
      </div>
    </div>
    <div class="page-body">
      <?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:24px">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-right-left" style="color:#4a9eff"></i> Send Funds</div>
          </div>
          <form method="POST" action="/transfer">
            <div class="form-group">
              <label class="form-label">Recipient Email</label>
              <div class="input-group">
                <i class="fas fa-envelope input-prefix"></i>
                <input type="email" name="to_email" class="form-control" placeholder="recipient@example.com" required>
              </div>
              <div class="form-hint">Enter the registered email address of the recipient.</div>
            </div>
            <div class="form-group">
              <label class="form-label">Amount (USD)</label>
              <div class="input-group">
                <span class="input-prefix">$</span>
                <input type="number" name="amount" class="form-control" placeholder="10.00" min="1" max="<?= (float)$user['balance'] ?>" step="0.01" required>
              </div>
              <div class="form-hint">Available: <?= formatMoney((float)$user['balance']) ?></div>
            </div>
            <div class="alert alert-info" style="font-size:13px">
              <i class="fas fa-circle-info"></i>
              Transfers are instant and cannot be reversed. Make sure the recipient email is correct.
            </div>
            <button type="submit" class="btn btn-accent btn-full" style="font-size:15px;padding:13px">
              <i class="fas fa-paper-plane"></i> Send Transfer
            </button>
          </form>
        </div>
        <div style="display:flex;flex-direction:column;gap:16px">
          <div class="stat-card">
            <div class="stat-icon accent"><i class="fas fa-wallet"></i></div>
            <div class="stat-content">
              <div class="stat-label">Available Balance</div>
              <div class="stat-value accent"><?= formatMoney((float)$user['balance']) ?></div>
            </div>
          </div>
          <div class="card">
            <div class="card-title" style="font-size:14px;margin-bottom:12px"><i class="fas fa-circle-info" style="color:#4a9eff"></i> Transfer Info</div>
            <?php foreach([['Speed','Instant'],['Fee','Free'],['Min Amount','$1.00'],['Reversal','Not possible']] as $r): ?>
            <div class="ap-row"><span class="ap-label"><?=$r[0]?></span><span class="ap-val"><?=$r[1]?></span></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
