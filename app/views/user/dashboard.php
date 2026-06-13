<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">Dashboard</div>
        <div class="topbar-subtitle">Welcome back, <?= e($user['full_name']) ?>!</div>
      </div>
      <div class="topbar-right">
        <span class="topbar-time" id="live-time"></span>
        <a href="/deposit" class="btn btn-accent btn-sm"><i class="fas fa-plus"></i> Deposit</a>
      </div>
    </div>

    <!-- Ticker -->
    <div class="ticker-bar">
      <div class="ticker-inner">
        <?php $ticks=[['fab fa-bitcoin','#f7931a','BTC $67,432','+2.4%','up'],['fab fa-ethereum','#627eea','ETH $3,841','+1.8%','up'],['fas fa-sun','#9945ff','SOL $182','+3.1%','up'],['fas fa-coins','#f3ba2f','BNB $595','-0.5%','dn'],['fas fa-dollar-sign','#26a17b','USDT $1.00','0.0%',''],['fas fa-chart-line','#4a9eff','S&P 5842','+0.6%','up']];
        foreach(array_merge($ticks,$ticks) as $t): ?>
        <span class="ticker-item"><i class="<?=$t[0]?>" style="color:<?=$t[1]?>"></i>&nbsp;<?=$t[2]?> <span class="<?=$t[4]?>"><?=$t[3]?></span></span>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="page-body">
      <!-- Balance Cards -->
      <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr))">
        <div class="stat-card">
          <div class="stat-icon accent"><i class="fas fa-wallet"></i></div>
          <div class="stat-content">
            <div class="stat-label">Account Balance</div>
            <div class="stat-value accent"><?= formatMoney((float)$user['balance']) ?></div>
            <div class="stat-change"><i class="fas fa-circle-info"></i> Available</div>
          </div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon green"><i class="fas fa-arrow-trend-up"></i></div>
          <div class="stat-content">
            <div class="stat-label">Total Profit</div>
            <div class="stat-value green"><?= formatMoney((float)$user['total_profit']) ?></div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> Lifetime earnings</div>
          </div>
        </div>
        <div class="stat-card blue">
          <div class="stat-icon blue"><i class="fas fa-gift"></i></div>
          <div class="stat-content">
            <div class="stat-label">Bonus</div>
            <div class="stat-value"><?= formatMoney((float)$user['bonus']) ?></div>
            <div class="stat-change"><i class="fas fa-star"></i> Rewards earned</div>
          </div>
        </div>
        <div class="stat-card yellow">
          <div class="stat-icon yellow"><i class="fas fa-users"></i></div>
          <div class="stat-content">
            <div class="stat-label">Referral Bonus</div>
            <div class="stat-value"><?= formatMoney((float)$user['referral_bonus']) ?></div>
            <div class="stat-change"><i class="fas fa-user-plus"></i> From referrals</div>
          </div>
        </div>
        <div class="stat-card red">
          <div class="stat-icon red"><i class="fas fa-arrow-up-from-bracket"></i></div>
          <div class="stat-content">
            <div class="stat-label">Total Withdrawn</div>
            <div class="stat-value"><?= formatMoney((float)$user['withdrawals']) ?></div>
            <div class="stat-change"><i class="fas fa-check"></i> Processed</div>
          </div>
        </div>
        <div class="stat-card purple">
          <div class="stat-icon purple"><i class="fas fa-microchip"></i></div>
          <div class="stat-content">
            <div class="stat-label">Mining Status</div>
            <div class="stat-value" style="font-size:16px;margin-top:4px"><?= $miningSession ? '<span style="color:#00e5a0">Active</span>' : '<span style="color:#5c6585">Inactive</span>' ?></div>
            <div class="stat-change"><i class="fas fa-circle" style="color:<?= $miningSession ? '#00e5a0':'#5c6585'?>"></i> <?= $miningSession ? ucfirst($miningSession['type']).' Mining' : 'Not started' ?></div>
          </div>
        </div>
      </div>

      <!-- TradingView Chart + Quick Actions -->
      <div class="dashboard-grid" style="margin-bottom:24px">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title"><i class="fas fa-chart-candlestick" style="color:#00d4c8"></i> Live Market Chart</div>
              <div class="card-subtitle">BTC/USD — Real-time</div>
            </div>
          </div>
          <div class="tv-widget">
            <div class="tradingview-widget-container" style="height:300px">
              <div id="tradingview_chart" style="height:300px"></div>
              <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
              <script>
              new TradingView.widget({
                width:'100%', height:300,
                symbol:'BTCUSD', interval:'60',
                timezone:'Etc/UTC', theme:'dark',
                style:'1', locale:'en',
                toolbar_bg:'#111735',
                enable_publishing:false, hide_side_toolbar:false,
                allow_symbol_change:true,
                container_id:'tradingview_chart',
                backgroundColor:'#111735',
                gridColor:'rgba(30,37,85,0.5)',
              });
              </script>
            </div>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px">
          <div class="card">
            <div class="card-title" style="margin-bottom:14px;font-size:14px"><i class="fas fa-bolt" style="color:#ffd700"></i> Quick Actions</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
              <a href="/deposit" class="btn btn-accent btn-sm" style="flex-direction:column;height:54px;gap:4px"><i class="fas fa-arrow-down-to-bracket"></i><span style="font-size:11px">Deposit</span></a>
              <a href="/withdraw" class="btn btn-outline btn-sm" style="flex-direction:column;height:54px;gap:4px"><i class="fas fa-arrow-up-from-bracket"></i><span style="font-size:11px">Withdraw</span></a>
              <a href="/trading-plans" class="btn btn-outline btn-sm" style="flex-direction:column;height:54px;gap:4px"><i class="fas fa-chart-line"></i><span style="font-size:11px">Invest</span></a>
              <a href="/mining" class="btn btn-outline btn-sm" style="flex-direction:column;height:54px;gap:4px"><i class="fas fa-microchip"></i><span style="font-size:11px">Mining</span></a>
            </div>
          </div>

          <?php if ($miningSession): ?>
          <div class="card">
            <div class="card-title" style="margin-bottom:12px;font-size:14px"><i class="fas fa-microchip" style="color:#00d4c8"></i> Active Mining</div>
            <div class="mining-active">
              <div class="mining-pulse"></div>
              <div>
                <div style="font-weight:600;font-size:13px"><?= ucfirst($miningSession['type']) ?> Mining</div>
                <div style="font-size:11px;color:#8892b0;margin-top:2px">Started <?= date('M d, H:i', strtotime($miningSession['started_at'])) ?></div>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <div class="card">
            <div class="card-title" style="margin-bottom:12px;font-size:14px"><i class="fas fa-share-nodes" style="color:#00d4c8"></i> Your Referral Code</div>
            <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-family:monospace;font-size:15px;font-weight:700;color:#00d4c8;text-align:center;letter-spacing:2px"><?= e($user['referral_code']) ?></div>
            <button onclick="copyRef('<?= e($user['referral_code']) ?>')" class="btn btn-outline btn-sm btn-full" style="margin-top:10px" id="copyRefBtn">
              <i class="fas fa-copy"></i> Copy Code
            </button>
          </div>
        </div>
      </div>

      <!-- Active Plans -->
      <?php if ($activePlans): ?>
      <div class="card mb-24">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-chart-bar" style="color:#00d4c8"></i> Active Investment Plans</div>
          <a href="/trading-plans" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px">
          <?php foreach ($activePlans as $ap): ?>
          <div style="background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:16px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
              <span style="font-weight:700;color:#fff;font-size:14px"><?= e($ap['plan_name']) ?></span>
              <span class="badge badge-active">Active</span>
            </div>
            <div class="ap-row"><span class="ap-label">Invested</span><span class="ap-val text-accent"><?= formatMoney($ap['amount']) ?></span></div>
            <div class="ap-row"><span class="ap-label">Profit Earned</span><span class="ap-val text-green"><?= formatMoney($ap['profit_earned']) ?></span></div>
            <div class="ap-row"><span class="ap-label">Daily Return</span><span class="ap-val"><?= $ap['daily_return'] ?>%</span></div>
            <div class="ap-row" style="border:none"><span class="ap-label">Ends</span><span class="ap-val"><?= date('M d, Y', strtotime($ap['ends_at'])) ?></span></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Recent Transactions -->
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-clock-rotate-left" style="color:#00d4c8"></i> Recent Transactions</div>
          <a href="/transactions" class="btn btn-outline btn-sm">View All</a>
        </div>
        <?php if ($recentTx): ?>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Type</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($recentTx as $tx): ?>
            <tr>
              <td><span class="badge badge-<?= e($tx['type']) ?>"><i class="fas fa-<?= $tx['type']==='deposit'?'arrow-down':'arrow-up' ?>"></i> <?= ucfirst($tx['type']) ?></span></td>
              <td class="td-name"><?= formatMoney($tx['amount']) ?></td>
              <td><span class="badge badge-<?= e($tx['status']) ?>"><?= ucfirst($tx['status']) ?></span></td>
              <td class="text-muted"><?= date('M d, Y H:i', strtotime($tx['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:40px;color:#5c6585">
          <i class="fas fa-inbox" style="font-size:36px;margin-bottom:12px;display:block"></i>
          No transactions yet. <a href="/deposit">Make your first deposit</a>.
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
function copyRef(code){
  navigator.clipboard.writeText(code).then(()=>{
    const b=document.getElementById('copyRefBtn');
    b.innerHTML='<i class="fas fa-check"></i> Copied!';
    b.style.color='#00e5a0';b.style.borderColor='#00e5a0';
    setTimeout(()=>{b.innerHTML='<i class="fas fa-copy"></i> Copy Code';b.style.color='';b.style.borderColor='';},2000);
  });
}
function updateTime(){
  document.getElementById('live-time').textContent=new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(updateTime,1000); updateTime();
</script>
</body>
</html>
