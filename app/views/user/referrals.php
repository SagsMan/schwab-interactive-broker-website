<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Referrals — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar"><div class="topbar-title">Referral Program</div></div>
    <div class="page-body">
      <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
        <div class="stat-card green">
          <div class="stat-icon green"><i class="fas fa-users"></i></div>
          <div class="stat-content"><div class="stat-label">Total Referrals</div><div class="stat-value green"><?= count($refs) ?></div></div>
        </div>
        <div class="stat-card yellow">
          <div class="stat-icon yellow"><i class="fas fa-dollar-sign"></i></div>
          <div class="stat-content"><div class="stat-label">Referral Bonus</div><div class="stat-value"><?= formatMoney((float)$user['referral_bonus']) ?></div></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon accent"><i class="fas fa-percent"></i></div>
          <div class="stat-content"><div class="stat-label">Per Referral</div><div class="stat-value accent">$10.00</div></div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:24px">
        <div>
          <div class="card mb-16">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-share-nodes" style="color:#00d4c8"></i> Your Referral Link</div>
            </div>
            <div style="background:var(--bg2);border:1px solid var(--border);border-radius:8px;padding:12px 14px;margin-bottom:12px">
              <div style="font-size:11px;color:#8892b0;margin-bottom:4px">Your unique code</div>
              <div style="font-size:20px;font-weight:900;color:#00d4c8;letter-spacing:3px"><?= e($user['referral_code']) ?></div>
            </div>
            <div style="display:flex;gap:10px">
              <button onclick="copyCode()" class="btn btn-accent" id="codeBtn" style="flex:1"><i class="fas fa-copy"></i> Copy Code</button>
              <button onclick="copyLink()" class="btn btn-outline" id="linkBtn" style="flex:1"><i class="fas fa-link"></i> Copy Link</button>
            </div>
            <div style="background:var(--bg2);border:1px solid var(--border);border-radius:8px;padding:10px 12px;margin-top:12px;font-family:monospace;font-size:12px;color:#8892b0;word-break:break-all" id="refLink">
              <?= (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']==='on'?'https':'http').'://'.$_SERVER['HTTP_HOST'].'/register?ref='.e($user['referral_code']) ?>
            </div>
          </div>

          <div class="card">
            <div class="card-title" style="font-size:14px;margin-bottom:14px"><i class="fas fa-circle-info" style="color:#4a9eff"></i> How It Works</div>
            <?php foreach([
              ['fas fa-share','Share your referral code or link with friends'],
              ['fas fa-user-plus','Friend registers using your code'],
              ['fas fa-dollar-sign','You instantly earn $10 referral bonus'],
              ['fas fa-repeat','Repeat — no limit on referrals!'],
            ] as $i=>$s): ?>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)">
              <div style="width:30px;height:30px;border-radius:50%;background:rgba(0,212,200,.1);color:#00d4c8;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0"><i class="<?=$s[0]?>"></i></div>
              <span style="font-size:13px;color:#8892b0"><?=$s[1]?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-list" style="color:#00d4c8"></i> Your Referrals</div>
            <span class="badge badge-active"><?= count($refs) ?> total</span>
          </div>
          <?php if($refs): ?>
          <?php foreach($refs as $r): ?>
          <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border)">
            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#00d4c8,#4a9eff);display:flex;align-items:center;justify-content:center;font-weight:700;color:#000;font-size:14px;flex-shrink:0">
              <?= strtoupper(substr($r['full_name'],0,1)) ?>
            </div>
            <div style="flex:1;min-width:0">
              <div style="font-weight:600;color:#fff;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($r['full_name']) ?></div>
              <div style="font-size:11px;color:#8892b0"><?= date('M d, Y', strtotime($r['created_at'])) ?></div>
            </div>
            <span class="text-green fw-600 fs-13">+$10.00</span>
          </div>
          <?php endforeach; ?>
          <?php else: ?>
          <div style="text-align:center;padding:40px 20px;color:#5c6585">
            <i class="fas fa-users" style="font-size:36px;display:block;margin-bottom:12px"></i>
            No referrals yet. Share your link to start earning!
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function copyCode(){
  navigator.clipboard.writeText('<?= e($user['referral_code']) ?>').then(()=>{
    const b=document.getElementById('codeBtn');
    b.innerHTML='<i class="fas fa-check"></i> Copied!';
    b.style.background='#00e5a0';b.style.color='#000';
    setTimeout(()=>{b.innerHTML='<i class="fas fa-copy"></i> Copy Code';b.style.background='';b.style.color='';},2000);
  });
}
function copyLink(){
  const link=document.getElementById('refLink').textContent.trim();
  navigator.clipboard.writeText(link).then(()=>{
    const b=document.getElementById('linkBtn');
    b.innerHTML='<i class="fas fa-check"></i> Copied!';
    b.style.borderColor='#00e5a0';b.style.color='#00e5a0';
    setTimeout(()=>{b.innerHTML='<i class="fas fa-link"></i> Copy Link';b.style.borderColor='';b.style.color='';},2000);
  });
}
</script>
</body>
</html>
