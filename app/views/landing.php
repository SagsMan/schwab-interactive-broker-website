<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Schwab Interactive Broker — Smart Investment Platform</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="demo-banner">
  <i class="fas fa-shield-halved"></i>&nbsp;
  <strong>DEMO ACCOUNT ONLY</strong> — For educational purposes. Not real financial advice or real profits.
</div>

<nav class="landing-nav" style="top:40px">
  <div class="nav-logo">
    <div class="logo-icon">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00d4c8" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
    </div>
    <div><div style="font-weight:800">Schwab Interactive</div><small style="font-size:10px;font-weight:400;color:#8892b0;letter-spacing:.8px">BROKER</small></div>
  </div>
  <div class="nav-links">
    <a href="#features">Features</a>
    <a href="#plans">Plans</a>
    <a href="#mining">Mining</a>
    <a href="#howto">How It Works</a>
  </div>
  <div class="nav-btns">
    <a href="/login" class="btn btn-outline btn-sm">Sign In</a>
    <a href="/register" class="btn btn-accent btn-sm"><i class="fas fa-rocket"></i> Get Started</a>
  </div>
</nav>

<!-- Live Ticker -->
<div class="ticker-bar" style="margin-top:112px">
  <div class="ticker-inner">
    <?php $tickers=[['fab fa-bitcoin','#f7931a','BTC','$67,432','up','+2.4%'],['fab fa-ethereum','#627eea','ETH','$3,841','up','+1.8%'],['fas fa-sun','#9945ff','SOL','$182','up','+3.1%'],['fas fa-coins','#f3ba2f','BNB','$595','dn','-0.5%'],['fas fa-dollar-sign','#26a17b','USDT','$1.00','','0.0%'],['fas fa-chart-line','#4a9eff','S&P500','5,842','up','+0.6%'],['fas fa-chart-bar','#00d4c8','NASDAQ','18,910','up','+1.1%']];
    foreach(array_merge($tickers,$tickers) as $t): ?>
    <span class="ticker-item"><i class="<?=$t[0]?>" style="color:<?=$t[1]?>"></i>&nbsp;<?=$t[2]?> <span class="<?=$t[4]?>"><?=$t[3]?> <?=$t[5]?></span></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- Hero -->
<section class="hero" style="padding-top:80px;min-height:88vh">
  <div class="hero-bg"></div>
  <div class="hero-glow"></div>
  <div class="hero-content">
    <div class="hero-badge"><i class="fas fa-shield-halved"></i>&nbsp;Trusted Investment Platform</div>
    <h1>Grow Your Wealth<br>with <span>Smart Investing</span></h1>
    <p>Join thousands of investors growing portfolios with expert-curated trading plans, real-time signals, and crypto mining — all in one platform.</p>
    <div class="hero-btns">
      <a href="/register" class="btn btn-accent" style="font-size:15px;padding:13px 30px"><i class="fas fa-rocket"></i> Start Investing Free</a>
      <a href="/login" class="btn btn-outline" style="font-size:15px;padding:13px 30px"><i class="fas fa-sign-in-alt"></i> Sign In</a>
    </div>
    <div style="display:flex;gap:20px;margin-top:26px;flex-wrap:wrap">
      <?php foreach(['No hidden fees','24/7 support','Instant payouts'] as $f): ?>
      <span style="font-size:13px;color:#8892b0;display:flex;align-items:center;gap:5px"><i class="fas fa-check-circle" style="color:#00e5a0"></i><?=$f?></span>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="hero-visual">
    <img src="https://images.unsplash.com/photo-1640340434855-6084b1f4901c?w=900&q=80&fit=crop" alt="Dashboard" loading="lazy">
    <div style="position:absolute;bottom:-18px;left:-28px;background:#111735;border:1px solid #252d5c;border-radius:12px;padding:14px 18px;box-shadow:0 8px 32px rgba(0,0,0,.6)">
      <div style="font-size:10px;color:#8892b0;letter-spacing:.5px;text-transform:uppercase;margin-bottom:2px">Today's Return</div>
      <div style="font-size:22px;font-weight:900;color:#00e5a0">+$2,418.50</div>
      <div style="font-size:11px;color:#00e5a0;margin-top:2px"><i class="fas fa-arrow-up"></i> 4.8% vs yesterday</div>
    </div>
  </div>
</section>

<!-- Stats Bar -->
<section style="background:#0d1232;padding:40px 60px;border-top:1px solid #1e2555;border-bottom:1px solid #1e2555">
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;max-width:960px;margin:0 auto;text-align:center">
    <?php foreach([['$2.8B+','Total Volume'],['48K+','Active Investors'],['99.9%','Uptime Guaranteed'],['5%','Max Daily Return']] as $s): ?>
    <div>
      <div style="font-size:34px;font-weight:900;color:#00d4c8"><?=$s[0]?></div>
      <div style="font-size:11px;color:#8892b0;text-transform:uppercase;letter-spacing:.5px;margin-top:4px"><?=$s[1]?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Features -->
<section class="features-section" id="features">
  <div class="section-header">
    <div class="section-label"><i class="fas fa-star"></i> Why Choose Us</div>
    <h2 class="section-title">Everything You Need to Invest</h2>
    <p class="section-sub">A complete suite of tools designed to maximize your investment returns.</p>
  </div>
  <div class="features-grid">
    <?php $features=[
      ['fas fa-chart-line','Expert Trading Plans','4 curated plans with up to 5% daily returns. Starter, Silver, Gold, and Platinum tiers.'],
      ['fab fa-bitcoin','Crypto Deposits','BTC, ETH, SOL, BNB, USDT, USDC. Real wallets, fast approval.'],
      ['fas fa-microchip','Crypto Mining','ASIC, GPU, and Cloud Mining. Earn passively while you sleep.'],
      ['fas fa-signal','Live Trade Signals','Real-time buy/sell alerts for crypto and forex markets.'],
      ['fas fa-users','Referral Program','Earn $10 for every friend referred. Share your unique link.'],
      ['fas fa-shield-halved','Bank-Grade Security','Encrypted transactions. Full transparency. Always safe.'],
    ]; foreach($features as $f): ?>
    <div class="feature-card">
      <div class="feature-icon"><i class="<?=$f[0]?>"></i></div>
      <div class="feature-title"><?=$f[1]?></div>
      <p class="feature-desc"><?=$f[2]?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Plans -->
<section class="plans-section" id="plans">
  <div class="section-header">
    <div class="section-label"><i class="fas fa-gem"></i> Investment Plans</div>
    <h2 class="section-title">Choose Your Plan</h2>
    <p class="section-sub">Start small or go big — every investor has a plan.</p>
  </div>
  <div class="plans-grid">
    <?php $lplans=[
      ['Starter','1.5','$100','$999','7 Days','10.5%',false],
      ['Silver','2.5','$1,000','$4,999','14 Days','35%',false],
      ['Gold','3.5','$5,000','$19,999','21 Days','73.5%',true],
      ['Platinum','5.0','$20,000','$999,999','30 Days','150%',false],
    ]; foreach($lplans as $p): ?>
    <div class="plan-lcard <?=$p[6]?'featured':''?>">
      <?php if($p[6]): ?><span class="plan-badge">POPULAR</span><?php endif; ?>
      <div style="font-size:12px;color:<?=$p[6]?'#00d4c8':'#8892b0'?>;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;font-weight:600"><?=$p[0]?></div>
      <div class="plan-return"><?=$p[1]?>%<span>/day</span></div>
      <div class="plan-details">
        <div class="plan-row"><span style="color:#8892b0">Min Deposit</span><span style="color:#e8eaf6;font-weight:600"><?=$p[2]?></span></div>
        <div class="plan-row"><span style="color:#8892b0">Max Deposit</span><span style="color:#e8eaf6;font-weight:600"><?=$p[3]?></span></div>
        <div class="plan-row"><span style="color:#8892b0">Duration</span><span style="color:#e8eaf6;font-weight:600"><?=$p[4]?></span></div>
        <div class="plan-row"><span style="color:#8892b0">Total Return</span><span style="color:#00e5a0;font-weight:700"><?=$p[5]?></span></div>
      </div>
      <a href="/register" class="btn <?=$p[6]?'btn-accent':'btn-outline'?> btn-full"><?=$p[6]?'Get Started — Popular':'Get Started'?></a>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Mining -->
<section style="padding:100px 60px;background:#0d1232;border-top:1px solid #1e2555" id="mining">
  <div class="section-header">
    <div class="section-label"><i class="fas fa-microchip"></i> Crypto Mining</div>
    <h2 class="section-title">Mine While You Sleep</h2>
    <p class="section-sub">Three powerful mining options. Zero hardware required for cloud mining.</p>
  </div>
  <div class="mining-grid" style="max-width:900px;margin:0 auto">
    <?php $mtypes=[
      ['🖥️','ASIC Miners','Highest efficiency for Bitcoin. Application-specific hardware with massive hashrate.','8.2 TH/s','Avg Hashrate','#00d4c8','btn-accent'],
      ['🎮','GPU Rigs','Mine ETH, RVN, ERG and 20+ coins. Flexible and great for diversification.','580 MH/s','Avg Hashrate','#4a9eff','btn-outline" style="border-color:#4a9eff;color:#4a9eff'],
      ['☁️','Cloud Mining','Zero hardware. Rent our capacity. Perfect for beginners starting out.','Instant','Start Time','#9945ff','btn-outline" style="border-color:#9945ff;color:#9945ff'],
    ]; foreach($mtypes as $m): ?>
    <div class="mining-card" style="border-color:<?=$m[5]?>20">
      <span class="mining-icon"><?=$m[0]?></span>
      <div class="mining-title"><?=$m[1]?></div>
      <p class="mining-desc"><?=$m[2]?></p>
      <div class="mining-stat" style="color:<?=$m[5]?>"><?=$m[3]?></div>
      <div class="mining-stat-label"><?=$m[4]?></div>
      <a href="/register" class="btn <?=$m[6]?> btn-full">Start <?=$m[1]?></a>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- How It Works -->
<section class="howto-section" id="howto">
  <div class="section-header">
    <div class="section-label"><i class="fas fa-list-ol"></i> Get Started</div>
    <h2 class="section-title">How It Works</h2>
    <p class="section-sub">Earning starts in 4 simple steps.</p>
  </div>
  <div class="steps-grid">
    <?php $steps=[
      ['1','Create Account','Sign up in under 2 minutes. Name, email, password. Instant activation.','fas fa-user-plus'],
      ['2','Fund Your Account','Deposit with BTC, ETH, SOL, BNB, USDT, USDC or other cryptos. Minimum $10.','fas fa-wallet'],
      ['3','Choose a Plan or Mine','Select a trading plan or start a mining session. Earnings are automated.','fas fa-chart-line'],
      ['4','Withdraw Profits','Withdraw anytime. Processed to your crypto wallet within 24–48 hours.','fas fa-arrow-up'],
    ]; foreach($steps as $s): ?>
    <div class="step">
      <div class="step-num"><?=$s[0]?></div>
      <div class="step-title"><?=$s[1]?></div>
      <p class="step-desc"><?=$s[2]?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Testimonials -->
<section style="padding:80px 60px;background:#0a0d1f;border-top:1px solid #1e2555">
  <div class="section-header">
    <div class="section-label"><i class="fas fa-quote-left"></i> Investors</div>
    <h2 class="section-title">What Our Investors Say</h2>
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;max-width:1100px;margin:0 auto">
    <?php foreach([
      ['J. Williams','JW','Started with Starter plan. 10.5% in 7 days. Moved to Gold and never looked back.','BTC Investor'],
      ['M. Okafor','MO','Cloud mining is incredible. Passive income without any technical knowledge needed.','Cloud Miner'],
      ['A. Chen','AC','Referred 8 friends and earned $80 in referral bonuses. The platform just works.','Gold Investor'],
    ] as $t): ?>
    <div class="card" style="border-color:#252d5c">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
        <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#00d4c8,#4a9eff);display:flex;align-items:center;justify-content:center;font-weight:700;color:#000;font-size:14px;flex-shrink:0"><?=$t[1]?></div>
        <div><div style="font-weight:700;color:#fff;font-size:14px"><?=$t[0]?></div><div style="font-size:11px;color:#00d4c8"><?=$t[3]?></div></div>
        <div style="margin-left:auto;color:#ffd700;font-size:11px"><?= str_repeat('<i class="fas fa-star"></i>',5) ?></div>
      </div>
      <p style="color:#8892b0;font-size:13px;line-height:1.7;font-style:italic">"<?=$t[2]?>"</p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div style="max-width:560px;margin:0 auto">
    <div class="section-label" style="margin-bottom:12px"><i class="fas fa-bolt"></i> Join Today</div>
    <h2>Ready to Start Earning?</h2>
    <p>Join 48,000+ investors already growing their wealth.</p>
    <a href="/register" class="btn btn-accent" style="font-size:16px;padding:15px 40px"><i class="fas fa-user-plus"></i> Create Free Account</a>
    <p style="font-size:12px;color:#5c6585;margin-top:14px"><i class="fas fa-lock"></i> Secure &amp; encrypted. No credit card required.</p>
  </div>
</section>

<!-- Footer -->
<footer class="landing-footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="logo-mark" style="margin-bottom:14px">
        <div class="logo-icon" style="width:36px;height:36px;font-size:16px">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00d4c8" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
        </div>
        <div><div style="font-weight:700;font-size:14px">Schwab Interactive Broker</div></div>
      </div>
      <p>Professional investment platform for crypto and stock market investors. Demo educational platform.</p>
      <div style="display:flex;gap:10px;margin-top:14px">
        <?php foreach(['fab fa-twitter','fab fa-telegram','fab fa-instagram','fab fa-youtube'] as $ico): ?>
        <a href="#" style="width:32px;height:32px;background:#1e2555;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#8892b0;font-size:13px;transition:all .2s" onmouseover="this.style.color='#00d4c8'" onmouseout="this.style.color='#8892b0'"><i class="<?=$ico?>"></i></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="footer-col"><h4>Platform</h4><a href="#plans">Trading Plans</a><a href="#mining">Mining</a><a href="/register">Get Started</a><a href="/login">Sign In</a></div>
    <div class="footer-col"><h4>Account</h4><a href="/register">Register</a><a href="/login">Login</a><a href="/support">Support</a><a href="#howto">How It Works</a></div>
    <div class="footer-col"><h4>Legal</h4><a href="#">Terms of Service</a><a href="#">Privacy Policy</a><a href="#">Risk Disclosure</a><a href="#">AML Policy</a></div>
  </div>
  <div class="footer-bottom">
    <p>© <?=date('Y')?> Schwab Interactive Broker. All rights reserved. Demo platform — not real investment advice.</p>
    <p><i class="fas fa-shield-halved" style="color:#00d4c8"></i> 256-bit SSL Secured</p>
  </div>
</footer>
</body>
</html>
