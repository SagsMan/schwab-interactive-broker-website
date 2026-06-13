<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Support — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar"><div class="topbar-title">Support Center</div></div>
    <div class="page-body">
      <?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:24px">
        <div>
          <div class="card mb-16">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-ticket" style="color:#00d4c8"></i> Submit a Support Ticket</div>
            </div>
            <form method="POST" action="/support">
              <div class="form-group">
                <label class="form-label">Subject</label>
                <select name="subject" class="form-control">
                  <option>Deposit not reflecting</option>
                  <option>Withdrawal not processed</option>
                  <option>Account issue</option>
                  <option>Trading plan inquiry</option>
                  <option>Mining question</option>
                  <option>KYC verification</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Describe your issue in detail..." required></textarea>
              </div>
              <button type="submit" class="btn btn-accent btn-full"><i class="fas fa-paper-plane"></i> Submit Ticket</button>
            </form>
          </div>

          <?php if($tickets): ?>
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-clock-rotate-left" style="color:#00d4c8"></i> My Tickets</div>
            </div>
            <?php foreach($tickets as $t): ?>
            <div class="ticket-card">
              <div style="flex:1">
                <div style="font-weight:700;color:#fff;font-size:14px;margin-bottom:4px"><?= e($t['subject']) ?></div>
                <div style="font-size:12px;color:#8892b0;margin-bottom:6px"><?= nl2br(e(substr($t['message'],0,100))) ?>...</div>
                <?php if($t['reply']): ?>
                <div style="background:rgba(0,212,200,.06);border:1px solid rgba(0,212,200,.15);border-radius:8px;padding:10px 12px;margin-top:8px">
                  <div style="font-size:11px;color:#00d4c8;margin-bottom:4px;font-weight:600"><i class="fas fa-reply"></i> Admin Reply</div>
                  <div style="font-size:13px;color:#e8eaf6"><?= nl2br(e($t['reply'])) ?></div>
                </div>
                <?php endif; ?>
                <div style="font-size:11px;color:#5c6585;margin-top:6px"><i class="fas fa-clock"></i> <?= date('M d, Y H:i',strtotime($t['created_at'])) ?></div>
              </div>
              <span class="badge badge-<?= $t['status']==='open'?'pending':'approved' ?>"><?= ucfirst($t['status']) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px">
          <div class="card">
            <div class="card-title" style="font-size:14px;margin-bottom:14px"><i class="fas fa-headset" style="color:#00d4c8"></i> Contact Options</div>
            <?php foreach([
              ['fas fa-envelope','Email Support','support@schwabinteractivebroker.com','#00d4c8'],
              ['fab fa-telegram','Telegram','@SchwabBrokerSupport','#0088cc'],
              ['fab fa-whatsapp','WhatsApp','+1 (800) 555-0100','#25d366'],
            ] as $c): ?>
            <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border)">
              <div style="width:36px;height:36px;border-radius:10px;background:<?=$c[3]?>18;color:<?=$c[3]?>;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0"><i class="<?=$c[0]?>"></i></div>
              <div><div style="font-weight:600;color:#fff;font-size:13px"><?=$c[1]?></div><div style="font-size:12px;color:#8892b0"><?=$c[2]?></div></div>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="card">
            <div class="card-title" style="font-size:14px;margin-bottom:14px"><i class="fas fa-circle-question" style="color:#4a9eff"></i> FAQ</div>
            <?php foreach([
              ['How long does deposit approval take?','Deposits are typically approved within 30 minutes during business hours.'],
              ['How long for withdrawals?','Withdrawals are processed within 24–48 hours to your crypto wallet.'],
              ['Can I change my investment plan?','Contact support to upgrade or change your active trading plan.'],
            ] as $q): ?>
            <details style="border-bottom:1px solid var(--border);padding:10px 0">
              <summary style="font-size:13px;color:#e8eaf6;cursor:pointer;font-weight:600;list-style:none"><?=$q[0]?></summary>
              <p style="font-size:12px;color:#8892b0;margin-top:8px;line-height:1.6"><?=$q[1]?></p>
            </details>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
