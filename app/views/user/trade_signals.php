<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Trade Signals — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">Trade Signals</div>
        <div class="topbar-subtitle">Real-time market analysis and buy/sell signals</div>
      </div>
      <div class="topbar-right">
        <div class="mining-active" style="padding:6px 12px">
          <div class="mining-pulse"></div>
          <span style="font-size:12px">Live</span>
        </div>
      </div>
    </div>
    <div class="page-body">
      <!-- TradingView Widget -->
      <div class="card mb-24">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-chart-candlestick" style="color:#00d4c8"></i> Live Market Chart</div>
        </div>
        <div class="tv-widget">
          <div class="tradingview-widget-container" style="height:400px">
            <div id="tv_chart" style="height:400px"></div>
            <script src="https://s3.tradingview.com/tv.js"></script>
            <script>
            new TradingView.widget({
              width:'100%',height:400,symbol:'BTCUSD',interval:'60',
              timezone:'Etc/UTC',theme:'dark',style:'1',locale:'en',
              toolbar_bg:'#111735',enable_publishing:false,
              allow_symbol_change:true,container_id:'tv_chart',
              backgroundColor:'#111735',gridColor:'rgba(30,37,85,0.5)',
              studies:['RSI@tv-basicstudies','MACD@tv-basicstudies'],
            });
            </script>
          </div>
        </div>
      </div>

      <!-- Signals Grid -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-signal" style="color:#00d4c8"></i> Active Signals</div>
            <span class="badge badge-active">Live</span>
          </div>
          <?php $signals=[
            ['BTC/USD','BUY','67,432','71,000','65,800','Strong','fab fa-bitcoin','#f7931a',95],
            ['ETH/USD','BUY','3,841','4,200','3,600','Strong','fab fa-ethereum','#627eea',88],
            ['SOL/USD','HOLD','182','210','165','Moderate','fas fa-sun','#9945ff',72],
            ['BNB/USD','SELL','595','—','560','Weak','fas fa-coins','#f3ba2f',45],
          ]; foreach($signals as $s): ?>
          <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border)">
            <div style="width:36px;height:36px;border-radius:50%;background:<?=$s[7]?>18;color:<?=$s[7]?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px"><i class="<?=$s[6]?>"></i></div>
            <div style="flex:1">
              <div style="font-weight:700;color:#fff;font-size:13px"><?=$s[0]?></div>
              <div style="font-size:11px;color:#8892b0">Entry: $<?=$s[2]?> &nbsp;|&nbsp; Target: <?=$s[3]?=='—'?'—':'$'.$s[3]?></div>
            </div>
            <div style="text-align:right">
              <span class="badge <?=$s[1]==='BUY'?'badge-approved':($s[1]==='SELL'?'badge-rejected':'badge-pending')?>" style="font-size:12px;font-weight:800"><?=$s[1]?></span>
              <div style="font-size:10px;color:#8892b0;margin-top:3px"><?=$s[5]?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-newspaper" style="color:#00d4c8"></i> Market News</div>
          </div>
          <?php $news=[
            ['Bitcoin breaks $67K resistance, targets $70K next week','2 hours ago','up'],
            ['Fed hints at rate cut — crypto markets surge 4%','4 hours ago','up'],
            ['Ethereum ETF sees record $800M inflows','6 hours ago','up'],
            ['Solana DeFi TVL hits all-time high of $8.2B','8 hours ago','up'],
            ['BNB faces resistance at $600, analysts cautious','12 hours ago','dn'],
          ]; foreach($news as $n): ?>
          <div style="padding:10px 0;border-bottom:1px solid var(--border);display:flex;gap:10px">
            <i class="fas fa-circle-arrow-<?=$n[2]==='up'?'up':'down'?>" style="color:<?=$n[2]==='up'?'#00e5a0':'#ff4757'?>;margin-top:2px;flex-shrink:0"></i>
            <div>
              <div style="font-size:13px;color:#e8eaf6;line-height:1.5"><?=$n[0]?></div>
              <div style="font-size:11px;color:#5c6585;margin-top:2px"><?=$n[1]?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Crypto Overview Table -->
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-table" style="color:#00d4c8"></i> Crypto Market Overview</div>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Asset</th><th>Price</th><th>24h Change</th><th>Market Cap</th><th>Signal</th><th>Strength</th></tr></thead>
            <tbody>
            <?php $overview=[
              ['fab fa-bitcoin','#f7931a','Bitcoin','BTC','$67,432','+2.4%','up','$1.32T','BUY',95],
              ['fab fa-ethereum','#627eea','Ethereum','ETH','$3,841','+1.8%','up','$461B','BUY',88],
              ['fas fa-sun','#9945ff','Solana','SOL','$182.30','+3.1%','up','$83B','HOLD',72],
              ['fas fa-coins','#f3ba2f','BNB','BNB','$595.80','-0.5%','dn','$88B','SELL',45],
              ['fas fa-dollar-sign','#26a17b','Tether','USDT','$1.00','0.0%','','$116B','HOLD',50],
            ]; foreach($overview as $o): ?>
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <span style="width:30px;height:30px;border-radius:50%;background:<?=$o[1]?>18;color:<?=$o[1]?>;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0"><i class="<?=$o[0]?>"></i></span>
                  <div><div class="td-name"><?=$o[2]?></div><div class="text-muted fs-11"><?=$o[3]?></div></div>
                </div>
              </td>
              <td class="td-name"><?=$o[4]?></td>
              <td class="<?=$o[6]==='up'?'text-green':($o[6]==='dn'?'text-red':'text-muted')?> fw-600"><?=$o[5]?></td>
              <td class="text-muted"><?=$o[7]?></td>
              <td><span class="badge <?=$o[8]==='BUY'?'badge-approved':($o[8]==='SELL'?'badge-rejected':'badge-pending')?>"><?=$o[8]?></span></td>
              <td>
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="flex:1;height:4px;background:var(--border);border-radius:2px">
                    <div style="width:<?=$o[9]?>%;height:100%;background:<?=$o[9]>70?'#00e5a0':($o[9]>50?'#ffd700':'#ff4757')?>;border-radius:2px"></div>
                  </div>
                  <span style="font-size:11px;color:#8892b0"><?=$o[9]?>%</span>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
