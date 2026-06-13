<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Users — Admin — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">User Management</div>
        <div class="topbar-subtitle"><?= count($users) ?> users registered</div>
      </div>
    </div>
    <div class="page-body">
      <?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-users" style="color:#00d4c8"></i> All Users</div>
          <form method="GET" style="display:flex;gap:8px">
            <input type="text" name="q" class="form-control" placeholder="Search name, email, phone..." value="<?= e($search) ?>" style="width:280px">
            <button class="btn btn-accent btn-sm"><i class="fas fa-search"></i></button>
            <?php if($search): ?><a href="/admin/users" class="btn btn-outline btn-sm"><i class="fas fa-times"></i></a><?php endif; ?>
          </form>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>User</th>
                <th>Phone</th>
                <th>Country</th>
                <th>Balance</th>
                <th>Profit</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($users as $u): ?>
            <tr>
              <td>
                <a href="/admin/users/<?= $u['id'] ?>" style="display:flex;align-items:center;gap:10px">
                  <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#00d4c8,#4a9eff);display:flex;align-items:center;justify-content:center;font-weight:700;color:#000;font-size:13px;flex-shrink:0"><?= strtoupper(substr($u['full_name'],0,1)) ?></div>
                  <div>
                    <div class="td-name"><?= e($u['full_name']) ?></div>
                    <div style="font-size:11px;color:#8892b0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:160px"><?= e($u['email']) ?></div>
                  </div>
                </a>
              </td>
              <td class="text-muted fs-12"><?= e($u['phone'] ?? '—') ?></td>
              <td class="text-muted fs-12"><?= e($u['country'] ?? '—') ?></td>
              <td style="color:#00d4c8;font-weight:700"><?= formatMoney($u['balance']) ?></td>
              <td style="color:#00e5a0;font-weight:600"><?= formatMoney($u['total_profit']) ?></td>
              <td>
                <?php if(!$u['is_active']): ?>
                  <span class="badge badge-rejected"><i class="fas fa-ban"></i> Suspended</span>
                <?php elseif(!empty($u['is_restricted'])): ?>
                  <span class="badge badge-pending"><i class="fas fa-lock"></i> Restricted</span>
                <?php else: ?>
                  <span class="badge badge-active"><i class="fas fa-circle"></i> Active</span>
                <?php endif; ?>
              </td>
              <td class="text-muted fs-11"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
              <td>
                <div style="display:flex;gap:5px">
                  <a href="/admin/users/<?= $u['id'] ?>" class="btn btn-outline btn-xs" title="View Details"><i class="fas fa-eye"></i></a>
                  <form method="POST" action="/admin/user-toggle" style="display:inline">
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <button class="btn <?= $u['is_active']?'btn-danger':'btn-success' ?> btn-xs" title="<?= $u['is_active']?'Suspend':'Activate' ?>">
                      <i class="fas fa-<?= $u['is_active']?'ban':'circle-check' ?>"></i>
                    </button>
                  </form>
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
