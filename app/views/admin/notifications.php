<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Broadcast — Admin — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar"><div class="topbar-title">Broadcast Notifications</div></div>
    <div class="page-body">
      <?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-bullhorn" style="color:#ffd700"></i> Send Notification</div>
          </div>
          <form method="POST" action="/admin/add-notification" id="notifForm">
            <div class="form-group">
              <label class="form-label">Recipient</label>
              <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:13px">
                  <input type="checkbox" name="send_all" id="sendAll" onchange="toggleUser()" style="accent-color:#00d4c8"> Send to ALL users
                </label>
              </div>
              <select name="user_id" id="userSelect" class="form-control">
                <option value="">Select a specific user...</option>
                <?php foreach($users as $u): ?>
                <option value="<?=$u['id']?>"><?= e($u['full_name']) ?> (<?= e($u['email']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Notification Title</label>
              <input type="text" name="title" class="form-control" placeholder="e.g. System Maintenance Notice" required>
            </div>
            <div class="form-group">
              <label class="form-label">Message</label>
              <textarea name="message" class="form-control" rows="4" placeholder="Notification message..." required></textarea>
            </div>
            <button type="submit" class="btn btn-accent btn-full"><i class="fas fa-paper-plane"></i> Send Notification</button>
          </form>
        </div>

        <div class="card">
          <div class="card-title" style="font-size:14px;margin-bottom:14px"><i class="fas fa-bolt" style="color:#ffd700"></i> Quick Templates</div>
          <?php $templates=[
            ['System Maintenance','Platform will undergo scheduled maintenance. Transactions may be temporarily delayed.'],
            ['New Feature','We have launched a new feature on the platform. Log in to explore!'],
            ['Bonus Credited','A special bonus has been credited to your account. Check your dashboard!'],
            ['Withdrawal Update','All pending withdrawals are being processed. Thank you for your patience.'],
            ['Security Alert','Please review your account security settings and update your password if needed.'],
          ]; foreach($templates as $t): ?>
          <div style="padding:10px 0;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <div>
                <div style="font-weight:600;color:#fff;font-size:13px"><?=$t[0]?></div>
                <div style="font-size:11px;color:#8892b0;margin-top:2px"><?= substr($t[1],0,60) ?>...</div>
              </div>
              <button onclick="useTemplate('<?= addslashes($t[0]) ?>','<?= addslashes($t[1]) ?>')" class="btn btn-outline btn-xs" style="flex-shrink:0"><i class="fas fa-arrow-right"></i></button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function toggleUser(){
  const all=document.getElementById('sendAll').checked;
  document.getElementById('userSelect').disabled=all;
  document.getElementById('userSelect').style.opacity=all?'.4':'1';
}
function useTemplate(title,msg){
  document.querySelector('[name="title"]').value=title;
  document.querySelector('[name="message"]').value=msg;
  window.scrollTo({top:0,behavior:'smooth'});
}
</script>
</body>
</html>
