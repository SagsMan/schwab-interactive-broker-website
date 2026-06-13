<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mining — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title"><i class="fas fa-microchip"></i> Crypto Mining</div>
        <div class="topbar-subtitle">Start a mining session and earn passively</div>
      </div>
    </div>
    <div class="page-body">
      <?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <!-- Active Mining Banner -->
      <?php $active = array_filter($myMining, fn($m)=>$m['status']==='active'); ?>
      <?php if ($active): ?>
      <div style="background:rgba(0,212,200,.08);border:1px solid rgba(0,212,200,.2);border-radius:12px;padding:20px;margin-bottom:24px;display:flex;align-items:center;gap:16px">
        <div class="mining-pulse" style="width:12px;height:12px"></div>
        <div>
          <div style="font-size:16px;font-weight:700;color:#fff">Mining Session Active</div>
          <?php foreach($active as $a): ?>
          <div style="font-size:13px;color:#8892b0;margin-top:3px"><i class="fas fa-microchip" style="color:#00d4c8"></i> <?= ucfirst($a['type']) ?> Mining — Started <?= date('M d, Y H:i', strtotime($a['started_at'])) ?></div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Mining Types -->
      <div class="card-title" style="font-size:17px;margin-bottom:20px;color:#fff"><i class="fas fa-bolt" style="color:#ffd700"></i> Choose Your Mining Type</div>
      <div class="mining-grid" style="margin-bottom:32px">
        <?php $types=[
          ['asic','🖥️','ASIC Miners','Application-Specific Integrated Circuit miners. Most efficient for Bitcoin mining. High hashrate with low power consumption. Ideal for serious miners.','8.2 TH/s','Hashrate','1-3%','Daily Yield','#00d4c8'],
          ['gpu','🎮','GPU Rigs','Graphics Processing Unit mining rigs. Mine ETH, RVN, ERG and 20+ altcoins. Flexible, great for portfolio diversification and altcoin mining.','580 MH/s','Hashrate','2-4%','Daily Yield','#4a9eff'],
          ['cloud','☁️','Cloud Mining','Zero hardware required. Rent our cloud mining infrastructure. Perfect for beginners. Start instantly without any technical knowledge or equipment.','Instant','Start Time','1.5-3.5%','Daily Yield','#9945ff'],
        ]; foreach($types as $t): ?>
        <div class="mining-card" style="border-color:<?=$t[8]?>30">
          <span class="mining-icon"><?=$t[0]==='asic'?'🖥️':($t[0]==='gpu'?'🎮':'☁️')?></span>
          <div class="mining-title"><?=$t[2]?></div>
          <p class="mining-desc"><?=$t[3]?></p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px">
            <div style="background:rgba(255,255,255,.04);border-radius:8px;padding:12px;text-align:center">
              <div style="font-size:18px;font-weight:800;color:<?=$t[8]?>"><?=$t[4]?></div>
              <div style="font-size:10px;color:#8892b0;text-transform:uppercase;letter-spacing:.5px;margin-top:2px"><?=$t[5]?></div>
            </div>
            <div style="background:rgba(255,255,255,.04);border-radius:8px;padding:12px;text-align:center">
              <div style="font-size:18px;font-weight:800;color:#00e5a0"><?=$t[6]?></div>
              <div style="font-size:10px;color:#8892b0;text-transform:uppercase;letter-spacing:.5px;margin-top:2px"><?=$t[7]?></div>
            </div>
          </div>
          <form method="POST" action="/mining/start">
            <input type="hidden" name="type" value="<?=$t[0]?>">
            <button type="submit" class="btn btn-full" style="background:<?=$t[8]?>18;border:1px solid <?=$t[8]?>40;color:<?=$t[8]?>;font-weight:700">
              <i class="fas fa-play"></i> Start <?=$t[2]?>
            </button>
          </form>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Mining History -->
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-clock-rotate-left" style="color:#00d4c8"></i> Mining History</div>
        </div>
        <?php if ($myMining): ?>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Type</th><th>Status</th><th>Started</th></tr></thead>
            <tbody>
            <?php foreach($myMining as $m): ?>
            <tr>
              <td class="td-name"><i class="fas fa-microchip" style="color:#00d4c8"></i> <?= ucfirst($m['type']) ?> Mining</td>
              <td><span class="badge badge-<?= $m['status']==='active'?'active':'completed' ?>"><?= ucfirst($m['status']) ?></span></td>
              <td class="text-muted"><?= date('M d, Y H:i', strtotime($m['started_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:40px;color:#5c6585">
          <i class="fas fa-microchip" style="font-size:36px;display:block;margin-bottom:12px"></i>
          No mining sessions yet. Start one above!
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
