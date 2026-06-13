<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Withdraw — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">Withdraw Funds</div>
        <div class="topbar-subtitle">Withdraw profits to your crypto wallet</div>
      </div>
      <div class="topbar-right">
        <span style="font-size:13px;color:#8892b0">Available: </span>
        <span style="font-size:14px;font-weight:700;color:#00d4c8"><?= formatMoney((float)$user['balance']) ?></span>
      </div>
    </div>
    <div class="page-body">
      <?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:24px">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-arrow-up-from-bracket" style="color:#ff4757"></i> Withdrawal Request</div>
          </div>
          <form method="POST" action="/withdraw">
            <div class="form-group">
              <label class="form-label">Amount (USD)</label>
              <div class="input-group">
                <span class="input-prefix">$</span>
                <input type="number" name="amount" class="form-control" placeholder="20.00" min="20" max="<?= (float)$user['balance'] ?>" step="0.01" required>
              </div>
              <div class="form-hint">Min: $20.00 &nbsp;|&nbsp; Available: <?= formatMoney((float)$user['balance']) ?></div>
            </div>
            <div class="form-group">
              <label class="form-label">Withdrawal Method</label>
              <select name="method" class="form-control" id="wMethod" onchange="updatePlaceholder()">
                <option value="bitcoin">Bitcoin (BTC)</option>
                <option value="ethereum">Ethereum (ETH)</option>
                <option value="solana">Solana (SOL)</option>
                <option value="bnb">BNB</option>
                <option value="usdt">USDT (ERC20/TRC20)</option>
                <option value="usdc">USDC</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Your Wallet Address</label>
              <input type="text" name="wallet" id="walletInput" class="form-control" placeholder="Enter your BTC wallet address" required>
              <div class="form-hint">Double-check your address. Incorrect addresses may result in permanent loss.</div>
            </div>
            <div class="alert alert-warning" style="font-size:13px">
              <i class="fas fa-triangle-exclamation"></i>
              Withdrawals are processed within 24–48 hours. Ensure your wallet address is correct.
            </div>
            <button type="submit" class="btn btn-danger btn-full" style="font-size:15px;padding:13px">
              <i class="fas fa-arrow-up-from-bracket"></i> Submit Withdrawal
            </button>
          </form>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px">
          <div class="stat-card red">
            <div class="stat-icon red"><i class="fas fa-wallet"></i></div>
            <div class="stat-content">
              <div class="stat-label">Available Balance</div>
              <div class="stat-value"><?= formatMoney((float)$user['balance']) ?></div>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon accent"><i class="fas fa-history"></i></div>
            <div class="stat-content">
              <div class="stat-label">Total Withdrawn</div>
              <div class="stat-value"><?= formatMoney((float)$user['withdrawals']) ?></div>
            </div>
          </div>

          <div class="card">
            <div class="card-title" style="font-size:14px;margin-bottom:12px"><i class="fas fa-circle-info" style="color:#4a9eff"></i> Withdrawal Info</div>
            <?php foreach([['Minimum','$20.00'],['Processing','24–48 hours'],['Fee','None'],['Method','Crypto only']] as $r): ?>
            <div class="ap-row"><span class="ap-label"><?=$r[0]?></span><span class="ap-val"><?=$r[1]?></span></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
const placeholders={bitcoin:'Enter your BTC address (bc1q...)',ethereum:'Enter your ETH address (0x...)',solana:'Enter your SOL address',bnb:'Enter your BNB address (0x...)',usdt:'Enter your USDT address',usdc:'Enter your USDC address'};
function updatePlaceholder(){
  const m=document.getElementById('wMethod').value;
  document.getElementById('walletInput').placeholder=placeholders[m]||'Enter wallet address';
}
</script>
</body>
</html>
