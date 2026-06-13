<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Support Tickets — Admin — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar"><div class="topbar-title">Support Tickets</div></div>
    <div class="page-body">
      <div class="action-bar">
        <?php foreach(['all'=>'All','open'=>'Open','closed'=>'Closed'] as $k=>$v): ?>
        <a href="/admin/tickets?status=<?=$k?>" class="btn <?= $filter===$k?'btn-accent':'btn-outline' ?> btn-sm"><?=$v?></a>
        <?php endforeach; ?>
        <span style="margin-left:auto;font-size:13px;color:#8892b0"><?= count($tickets) ?> tickets</span>
      </div>
      <div class="card">
        <div class="table-wrap">
          <table>
            <thead><tr><th>User</th><th>Subject</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach($tickets as $t): ?>
            <tr>
              <td>
                <div class="td-name"><?= e($t['full_name']) ?></div>
                <div style="font-size:11px;color:#8892b0"><?= e($t['email']) ?></div>
              </td>
              <td class="td-name" style="font-size:13px"><?= e($t['subject']) ?></td>
              <td><span class="badge badge-<?= $t['status']==='open'?'pending':'approved' ?>"><?= ucfirst($t['status']) ?></span></td>
              <td class="text-muted fs-11"><?= date('M d, Y H:i', strtotime($t['created_at'])) ?></td>
              <td><a href="/admin/tickets/<?= $t['id'] ?>" class="btn btn-outline btn-xs"><i class="fas fa-reply"></i> Reply</a></td>
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
