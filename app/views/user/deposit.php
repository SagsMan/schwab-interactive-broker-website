<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Deposit — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">Deposit Funds</div>
        <div class="topbar-subtitle">Send crypto to your wallet address below</div>
      </div>
      <div class="topbar-right">
        <span style="font-size:13px;color:#8892b0">Balance: </span>
        <span style="font-size:14px;font-weight:700;color:#00d4c8"><?= formatMoney((float)$user['balance']) ?></span>
      </div>
    </div>
    <div class="page-body">
      <?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <div class="alert alert-warning" style="margin-bottom:20px">
        <i class="fas fa-triangle-exclamation"></i>
        <div>
          <strong>Important:</strong> After submitting your deposit request, send the <strong>exact amount</strong> to the wallet address shown for your selected coin. Your deposit will be reviewed and approved within 30 minutes.
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:24px">
        <!-- Deposit Form -->
        <div>
          <div class="card mb-16">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-arrow-down-to-bracket" style="color:#00d4c8"></i> Deposit Request</div>
            </div>
            <form method="POST" action="/deposit" id="depositForm">
              <div class="form-group">
                <label class="form-label">Amount (USD)</label>
                <div class="input-group">
                  <span class="input-prefix">$</span>
                  <input type="number" name="amount" class="form-control" placeholder="100.00" min="10" step="0.01" required>
                </div>
                <div class="form-hint">Minimum: $10.00</div>
              </div>
              <div class="form-group">
                <label class="form-label">Select Cryptocurrency</label>
                <select name="coin" id="coinSelect" class="form-control" onchange="showCoinAddress(this.value)">
                  <?php foreach($cryptos as $key=>$c): ?>
                  <option value="<?=$key?>"><?=$c['symbol']?> — <?=$c['name']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <button type="submit" class="btn btn-accent btn-full" style="font-size:15px;padding:13px">
                <i class="fas fa-paper-plane"></i> Submit Deposit Request
              </button>
            </form>
          </div>

          <div class="card">
            <div class="card-title" style="margin-bottom:14px;font-size:14px"><i class="fas fa-info-circle" style="color:#4a9eff"></i> Plan Minimums</div>
            <?php foreach([['Starter Plan','$100'],['Silver Plan','$1,000'],['Gold Plan','$5,000'],['Platinum Plan','$20,000']] as $p): ?>
            <div class="ap-row"><span class="ap-label"><?=$p[0]?></span><span class="ap-val text-accent"><?=$p[1]?></span></div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Wallet Addresses -->
        <div>
          <div class="card-title" style="font-size:15px;margin-bottom:16px;color:#fff"><i class="fas fa-wallet" style="color:#00d4c8"></i> Payment Wallet Addresses</div>
          <div class="crypto-grid" id="cryptoAddresses">
            <?php foreach($cryptos as $key=>$c): ?>
            <div class="crypto-card" id="crypto-<?=$key?>" onclick="selectCoin('<?=$key?>')">
              <div class="crypto-header">
                <div class="crypto-icon" style="background:<?=$c['color']?>22;color:<?=$c['color']?>">
                  <i class="<?=$c['icon']?>"></i>
                </div>
                <div>
                  <div class="crypto-name"><?=$c['name']?></div>
                  <div class="crypto-symbol"><?=$c['symbol']?></div>
                </div>
              </div>
              <div class="crypto-addr" id="addr-<?=$key?>"><?=e($c['address'])?></div>
              <button type="button" onclick="copyAddr('<?=$key?>','<?=addslashes($c['address'])?>')" class="copy-btn" id="copybtn-<?=$key?>">
                <i class="fas fa-copy"></i> Copy <?=$c['symbol']?> Address
              </button>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="alert alert-info" style="margin-top:16px">
            <i class="fas fa-circle-info"></i>
            <div>
              Only send the selected cryptocurrency to its corresponding address. Sending to the wrong address may result in permanent loss of funds.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function copyAddr(coin, addr){
  navigator.clipboard.writeText(addr).then(()=>{
    const b=document.getElementById('copybtn-'+coin);
    b.classList.add('copied');
    b.innerHTML='<i class="fas fa-check"></i> Copied!';
    setTimeout(()=>{b.classList.remove('copied');b.innerHTML='<i class="fas fa-copy"></i> Copy '+coin.toUpperCase()+' Address';},2500);
  });
}
function selectCoin(coin){
  document.querySelectorAll('.crypto-card').forEach(c=>c.classList.remove('selected'));
  document.getElementById('crypto-'+coin)?.classList.add('selected');
  document.getElementById('coinSelect').value=coin;
}
function showCoinAddress(coin){ selectCoin(coin); }
// Highlight first coin
document.addEventListener('DOMContentLoaded',()=>selectCoin('bitcoin'));
</script>
</body>
</html>
