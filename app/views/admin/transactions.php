<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Transactions — Admin — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">Transactions</div>
        <div class="topbar-subtitle">Review and approve/reject deposits &amp; withdrawals</div>
      </div>
    </div>
    <div class="page-body">
      <?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <!-- Filter -->
      <div class="action-bar">
        <?php foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $k=>$v): ?>
        <a href="/admin/transactions?status=<?=$k?>" class="btn <?= $filter===$k?'btn-accent':'btn-outline' ?> btn-sm"><?=$v?></a>
        <?php endforeach; ?>
        <span style="margin-left:auto;font-size:13px;color:#8892b0"><?= count($txs) ?> records</span>
      </div>

      <div class="card">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>User</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Coin</th>
                <th>Wallet / Description</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($txs as $tx): ?>
            <tr>
              <td class="text-muted fs-11"><?= $tx['id'] ?></td>
              <td>
                <a href="/admin/users/<?= $tx['user_id'] ?>" style="font-weight:600;color:#e8eaf6;font-size:13px"><?= e($tx['full_name']) ?></a>
                <div style="font-size:11px;color:#8892b0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:160px"><?= e($tx['email']) ?></div>
              </td>
              <td><span class="badge badge-<?= e($tx['type']) ?>"><i class="fas fa-<?= $tx['type']==='deposit'?'arrow-down':($tx['type']==='withdrawal'?'arrow-up':'right-left') ?>"></i> <?= ucfirst($tx['type']) ?></span></td>
              <td style="font-weight:700;color:#00d4c8"><?= formatMoney($tx['amount']) ?></td>
              <td class="text-muted fs-12"><?= $tx['coin'] ? strtoupper(e($tx['coin'])) : '—' ?></td>
              <td class="text-muted" style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px"><?= e($tx['wallet'] ?? $tx['description'] ?? '') ?></td>
              <td><span class="badge badge-<?= e($tx['status']) ?>"><?= ucfirst($tx['status']) ?></span></td>
              <td class="text-muted fs-11"><?= date('M d, Y H:i', strtotime($tx['created_at'])) ?></td>
              <td>
                <?php if($tx['status']==='pending'): ?>
                <form method="POST" action="/admin/tx-action" style="display:flex;gap:4px">
                  <input type="hidden" name="tx_id" value="<?= $tx['id'] ?>">
                  <button name="action" value="approve" class="btn btn-success btn-xs" title="Approve"><i class="fas fa-check"></i> Approve</button>
                  <button name="action" value="reject" class="btn btn-danger btn-xs" title="Reject"><i class="fas fa-times"></i></button>
                </form>
                <?php else: ?>
                <span style="color:#5c6585;font-size:12px">—</span>
                <?php endif; ?>
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
