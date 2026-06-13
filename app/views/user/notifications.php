<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Notifications — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div class="topbar-title">Notifications</div>
      <div class="topbar-right"><span class="badge badge-active"><?= count($notifs) ?> total</span></div>
    </div>
    <div class="page-body">
      <?php if($notifs): ?>
      <?php $icons=['Welcome'=>'fas fa-hand-wave','Deposit'=>'fas fa-arrow-down','Withdrawal'=>'fas fa-arrow-up','Transaction'=>'fas fa-credit-card','Mining'=>'fas fa-microchip','Support'=>'fas fa-headset','Account'=>'fas fa-user-shield','Message'=>'fas fa-envelope']; ?>
      <?php foreach($notifs as $n):
        $icon='fas fa-bell';
        foreach($icons as $k=>$v){ if(stripos($n['title'],$k)!==false){$icon=$v;break;} }
      ?>
      <div class="notif-item">
        <div class="notif-icon"><i class="<?=$icon?>"></i></div>
        <div class="notif-body" style="flex:1">
          <div class="notif-title"><?= e($n['title']) ?></div>
          <div class="notif-msg"><?= nl2br(e($n['message'])) ?></div>
          <div class="notif-time"><i class="fas fa-clock"></i> <?= date('M d, Y H:i', strtotime($n['created_at'])) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php else: ?>
      <div style="text-align:center;padding:60px;color:#5c6585">
        <i class="fas fa-bell-slash" style="font-size:48px;display:block;margin-bottom:16px"></i>
        <div style="font-size:16px;font-weight:600;color:#8892b0;margin-bottom:8px">No notifications</div>
        <div style="font-size:13px">You're all caught up!</div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
