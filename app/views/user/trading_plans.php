<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Trading Plans — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">Trading Plans</div>
        <div class="topbar-subtitle">Choose a plan and start earning daily returns</div>
      </div>
      <div class="topbar-right">
        <span style="font-size:13px;color:#8892b0">Balance: </span>
        <span style="font-size:14px;font-weight:700;color:#00d4c8"><?= formatMoney((float)$user['balance']) ?></span>
      </div>
    </div>
    <div class="page-body">
      <?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <?php if ($activePlans): ?>
      <div class="card mb-24">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-circle-check" style="color:#00e5a0"></i> Your Active Plans</div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px">
          <?php foreach($activePlans as $ap): ?>
          <div style="background:rgba(0,212,200,.06);border:1px solid rgba(0,212,200,.2);border-radius:10px;padding:16px">
            <div style="font-weight:700;color:#fff;margin-bottom:10px"><?= e($ap['plan_name']) ?></div>
            <div class="ap-row"><span class="ap-label">Invested</span><span class="ap-val text-accent"><?= formatMoney($ap['amount']) ?></span></div>
            <div class="ap-row"><span class="ap-label">Profit</span><span class="ap-val text-green"><?= formatMoney($ap['profit_earned']) ?></span></div>
            <div class="ap-row"><span class="ap-label">Daily Return</span><span class="ap-val"><?= $ap['daily_return'] ?>%</span></div>
            <div class="ap-row" style="border:none"><span class="ap-label">Ends</span><span class="ap-val"><?= date('M d, Y', strtotime($ap['ends_at'])) ?></span></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="card-title" style="font-size:17px;margin-bottom:20px;color:#fff"><i class="fas fa-gem" style="color:#ffd700"></i> Available Plans</div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px">
        <?php
        $iconMap=['Starter Plan'=>'fas fa-seedling','Silver Plan'=>'fas fa-medal','Gold Plan'=>'fas fa-trophy','Platinum Plan'=>'fas fa-crown'];
        $colorMap=['Starter Plan'=>'#00d4c8','Silver Plan'=>'#8892b0','Gold Plan'=>'#ffd700','Platinum Plan'=>'#9945ff'];
        $featured=['Gold Plan'];
        foreach($plans as $plan):
          $isFeatured = in_array($plan['name'],$featured);
          $color = $colorMap[$plan['name']] ?? '#00d4c8';
          $icon  = $iconMap[$plan['name']] ?? 'fas fa-chart-line';
          $totalReturn = $plan['daily_return'] * $plan['duration_days'];
        ?>
        <div class="trading-plan-card <?= $isFeatured?'featured':'' ?>" style="<?= $isFeatured?"border-color:$color":'' ?>">
          <?php if($isFeatured): ?><span class="plan-badge" style="background:<?=$color?>;color:#000">POPULAR</span><?php endif; ?>
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
            <div style="width:46px;height:46px;border-radius:12px;background:<?=$color?>18;color:<?=$color?>;display:flex;align-items:center;justify-content:center;font-size:20px">
              <i class="<?=$icon?>"></i>
            </div>
            <div>
              <div style="font-size:17px;font-weight:800;color:#fff"><?= e($plan['name']) ?></div>
              <div style="font-size:12px;color:<?=$color?>">Daily returns up to <?= $plan['daily_return'] ?>%</div>
            </div>
          </div>
          <div style="font-size:42px;font-weight:900;color:<?=$color?>;line-height:1;margin-bottom:16px">
            <?= $plan['daily_return'] ?>%<span style="font-size:16px;color:#8892b0;font-weight:500">/day</span>
          </div>
          <?php foreach([
            ['Min Deposit',formatMoney($plan['min_amount'])],
            ['Max Deposit',formatMoney($plan['max_amount'])],
            ['Duration',$plan['duration_days'].' Days'],
            ['Total Return',number_format($totalReturn,1).'%'],
          ] as $r): ?>
          <div class="ap-row">
            <span class="ap-label"><?=$r[0]?></span>
            <span class="ap-val" style="<?= $r[0]==='Total Return'?'color:#00e5a0':'' ?>"><?=$r[1]?></span>
          </div>
          <?php endforeach; ?>
          <p style="font-size:12px;color:#8892b0;margin:12px 0 16px;line-height:1.6"><?= e($plan['description']) ?></p>
          <a href="/deposit" class="btn btn-full" style="background:<?=$color?>18;border:1px solid <?=$color?>40;color:<?=$color?>;font-weight:700;font-size:14px">
            <i class="fas fa-arrow-down-to-bracket"></i> Deposit & Invest
          </a>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="alert alert-info" style="margin-top:24px">
        <i class="fas fa-circle-info"></i>
        <div>To activate a plan, <a href="/deposit"><strong>deposit funds</strong></a> first. Contact <a href="/support"><strong>support</strong></a> to manually activate your chosen plan after depositing.</div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
