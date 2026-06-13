<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Email Logs — Admin — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar"><div class="topbar-title">Email Logs</div></div>
    <div class="page-body">
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-envelope-open-text" style="color:#4a9eff"></i> All Sent Emails</div>
          <span class="badge badge-pending"><?= count($logs) ?> emails</span>
        </div>
        <?php if($logs): ?>
        <div class="table-wrap">
          <table>
            <thead><tr><th>To</th><th>User</th><th>Subject</th><th>Sent At</th></tr></thead>
            <tbody>
            <?php foreach($logs as $log): ?>
            <tr>
              <td class="td-name fs-12"><?= e($log['to_email']) ?></td>
              <td class="text-muted fs-12"><?= e($log['full_name'] ?? '—') ?></td>
              <td style="font-size:13px;color:#e8eaf6"><?= e($log['subject']) ?></td>
              <td class="text-muted fs-11"><?= date('M d, Y H:i', strtotime($log['sent_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:40px;color:#5c6585">
          <i class="fas fa-envelope" style="font-size:36px;display:block;margin-bottom:12px"></i>
          No emails sent yet.
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
