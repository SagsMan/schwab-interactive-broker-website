<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title"><i class="fas fa-shield-halved" style="color:#00d4c8"></i> Admin Dashboard</div>
        <div class="topbar-subtitle">Full platform overview and control</div>
      </div>
      <div class="topbar-right">
        <a href="/admin/users" class="btn btn-outline btn-sm"><i class="fas fa-users"></i> Users</a>
        <a href="/admin/transactions?status=pending" class="btn btn-accent btn-sm"><i class="fas fa-clock"></i> Pending (<?= $pendingTx ?>)</a>
      </div>
    </div>
    <div class="page-body">

      <!-- Primary Stats -->
      <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:24px">
        <div class="stat-card">
          <div class="stat-icon accent"><i class="fas fa-users"></i></div>
          <div class="stat-content">
            <div class="stat-label">Total Users</div>
            <div class="stat-value accent"><?= number_format($totalUsers) ?></div>
            <div class="stat-change"><i class="fas fa-user-check"></i> <?= number_format($activeUsers) ?> active</div>
          </div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon green"><i class="fas fa-wallet"></i></div>
          <div class="stat-content">
            <div class="stat-label">Total Balance</div>
            <div class="stat-value green"><?= formatMoney($totalBal) ?></div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> Platform-wide</div>
          </div>
        </div>
        <div class="stat-card yellow">
          <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
          <div class="stat-content">
            <div class="stat-label">Pending Tx</div>
            <div class="stat-value"><?= number_format($pendingTx) ?></div>
            <div class="stat-change down"><i class="fas fa-triangle-exclamation"></i> Need review</div>
          </div>
        </div>
        <div class="stat-card blue">
          <div class="stat-icon blue"><i class="fas fa-credit-card"></i></div>
          <div class="stat-content">
            <div class="stat-label">Total Transactions</div>
            <div class="stat-value"><?= number_format($totalTx) ?></div>
          </div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon green"><i class="fas fa-arrow-down"></i></div>
          <div class="stat-content">
            <div class="stat-label">Total Deposits</div>
            <div class="stat-value"><?= formatMoney($totalDeposits) ?></div>
            <div class="stat-change up">Approved</div>
          </div>
        </div>
        <div class="stat-card red">
          <div class="stat-icon red"><i class="fas fa-arrow-up"></i></div>
          <div class="stat-content">
            <div class="stat-label">Total Withdrawals</div>
            <div class="stat-value"><?= formatMoney($totalWithdraws) ?></div>
          </div>
        </div>
        <div class="stat-card orange">
          <div class="stat-icon orange"><i class="fas fa-ticket"></i></div>
          <div class="stat-content">
            <div class="stat-label">Open Tickets</div>
            <div class="stat-value"><?= $openTickets ?></div>
            <div class="stat-change"><a href="/admin/tickets" style="color:#ff8c42;font-size:11px">View tickets</a></div>
          </div>
        </div>
        <div class="stat-card purple">
          <div class="stat-icon purple"><i class="fas fa-user-minus"></i></div>
          <div class="stat-content">
            <div class="stat-label">Inactive Users</div>
            <div class="stat-value"><?= $totalUsers - $activeUsers ?></div>
          </div>
        </div>
      </div>

      <!-- Quick Admin Actions -->
      <div class="card mb-24">
        <div class="card-title" style="margin-bottom:14px;font-size:15px"><i class="fas fa-bolt" style="color:#ffd700"></i> Quick Actions</div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <a href="/admin/transactions?status=pending" class="btn btn-warning"><i class="fas fa-check-double"></i> Review Pending Tx</a>
          <a href="/admin/users" class="btn btn-outline"><i class="fas fa-users"></i> Manage Users</a>
          <a href="/admin/tickets" class="btn btn-outline"><i class="fas fa-ticket"></i> Support Tickets</a>
          <a href="/admin/notifications" class="btn btn-outline"><i class="fas fa-bullhorn"></i> Broadcast Message</a>
          <a href="/admin/plans" class="btn btn-outline"><i class="fas fa-chart-line"></i> Manage Plans</a>
          <a href="/admin/email-logs" class="btn btn-outline"><i class="fas fa-envelope"></i> Email Logs</a>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
        <!-- Recent Users -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-user-plus" style="color:#00d4c8"></i> Recent Users</div>
            <a href="/admin/users" class="btn btn-outline btn-sm">View All</a>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>User</th><th>Balance</th><th>Status</th><th>Joined</th></tr></thead>
              <tbody>
              <?php foreach($recentUsers as $u): ?>
              <tr>
                <td>
                  <a href="/admin/users/<?= $u['id'] ?>" style="display:flex;align-items:center;gap:10px">
                    <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#00d4c8,#4a9eff);display:flex;align-items:center;justify-content:center;font-weight:700;color:#000;font-size:12px;flex-shrink:0"><?= strtoupper(substr($u['full_name'],0,1)) ?></div>
                    <div>
                      <div class="td-name" style="font-size:13px"><?= e($u['full_name']) ?></div>
                      <div style="font-size:11px;color:#8892b0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:120px"><?= e($u['email']) ?></div>
                    </div>
                  </a>
                </td>
                <td style="color:#00d4c8;font-weight:600;font-size:13px"><?= formatMoney($u['balance']) ?></td>
                <td><span class="badge <?= $u['is_active']?'badge-active':'badge-rejected' ?>"><?= $u['is_active']?'Active':'Suspended' ?></span></td>
                <td class="text-muted fs-11"><?= date('M d', strtotime($u['created_at'])) ?></td>
              </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-credit-card" style="color:#00d4c8"></i> Recent Transactions</div>
            <a href="/admin/transactions" class="btn btn-outline btn-sm">View All</a>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>User</th><th>Type</th><th>Amount</th><th>Status</th></tr></thead>
              <tbody>
              <?php foreach($recentTx as $tx): ?>
              <tr>
                <td>
                  <div class="td-name" style="font-size:13px"><?= e($tx['full_name']) ?></div>
                  <div style="font-size:11px;color:#8892b0"><?= e($tx['email']) ?></div>
                </td>
                <td><span class="badge badge-<?= e($tx['type']) ?>"><?= ucfirst($tx['type']) ?></span></td>
                <td class="td-name" style="font-size:13px"><?= formatMoney($tx['amount']) ?></td>
                <td>
                  <span class="badge badge-<?= e($tx['status']) ?>"><?= ucfirst($tx['status']) ?></span>
                  <?php if($tx['status']==='pending'): ?>
                  <form method="POST" action="/admin/tx-action" style="display:inline;margin-left:4px">
                    <input type="hidden" name="tx_id" value="<?= $tx['id'] ?>">
                    <button name="action" value="approve" class="btn btn-success btn-xs" title="Approve"><i class="fas fa-check"></i></button>
                    <button name="action" value="reject" class="btn btn-danger btn-xs" title="Reject"><i class="fas fa-times"></i></button>
                  </form>
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
</div>
</body>
</html>
