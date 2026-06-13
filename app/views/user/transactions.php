<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Transactions — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div class="topbar-title">Transaction History</div>
      <div class="topbar-right">
        <a href="/deposit" class="btn btn-accent btn-sm"><i class="fas fa-plus"></i> New Deposit</a>
      </div>
    </div>
    <div class="page-body">
      <!-- Summary -->
      <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
        <?php
        $totDep = array_sum(array_column(array_filter($txs,fn($t)=>$t['type']==='deposit'&&$t['status']==='approved'),'amount'));
        $totWit = array_sum(array_column(array_filter($txs,fn($t)=>$t['type']==='withdrawal'),'amount'));
        $pending = count(array_filter($txs,fn($t)=>$t['status']==='pending'));
        ?>
        <div class="stat-card green">
          <div class="stat-icon green"><i class="fas fa-arrow-down"></i></div>
          <div class="stat-content"><div class="stat-label">Total Deposited</div><div class="stat-value green"><?= formatMoney($totDep) ?></div></div>
        </div>
        <div class="stat-card red">
          <div class="stat-icon red"><i class="fas fa-arrow-up"></i></div>
          <div class="stat-content"><div class="stat-label">Total Withdrawn</div><div class="stat-value"><?= formatMoney($totWit) ?></div></div>
        </div>
        <div class="stat-card yellow">
          <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
          <div class="stat-content"><div class="stat-label">Pending</div><div class="stat-value"><?= $pending ?></div></div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-list-ul" style="color:#00d4c8"></i> All Transactions</div>
          <span style="font-size:12px;color:#8892b0"><?= count($txs) ?> total</span>
        </div>
        <?php if ($txs): ?>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Coin</th>
                <th>Status</th>
                <th>Description</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($txs as $i => $tx): ?>
            <tr>
              <td class="text-muted fs-12"><?= $tx['id'] ?></td>
              <td>
                <span class="badge badge-<?= e($tx['type']) ?>">
                  <i class="fas fa-<?= $tx['type']==='deposit'?'arrow-down':($tx['type']==='withdrawal'?'arrow-up':'right-left') ?>"></i>
                  <?= ucfirst($tx['type']) ?>
                </span>
              </td>
              <td class="td-name"><?= formatMoney($tx['amount']) ?></td>
              <td class="text-muted fs-12"><?= $tx['coin'] ? strtoupper(e($tx['coin'])) : '—' ?></td>
              <td><span class="badge badge-<?= e($tx['status']) ?>"><?= ucfirst($tx['status']) ?></span></td>
              <td class="text-muted" style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($tx['description'] ?? '') ?></td>
              <td class="text-muted fs-12"><?= date('M d, Y H:i', strtotime($tx['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:50px;color:#5c6585">
          <i class="fas fa-inbox" style="font-size:40px;display:block;margin-bottom:14px"></i>
          No transactions found. <a href="/deposit">Make your first deposit</a>.
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
