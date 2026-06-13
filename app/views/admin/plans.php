<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Trading Plans — Admin — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar"><div class="topbar-title">Trading Plans</div></div>
    <div class="page-body">
      <?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:24px">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-line" style="color:#00d4c8"></i> All Trading Plans</div>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Plan Name</th><th>Min</th><th>Max</th><th>Daily %</th><th>Days</th><th>Status</th><th>Action</th></tr></thead>
              <tbody>
              <?php foreach($plans as $p): ?>
              <tr>
                <td class="td-name"><?= e($p['name']) ?></td>
                <td style="color:#00e5a0;font-weight:600"><?= formatMoney($p['min_amount']) ?></td>
                <td class="text-muted"><?= formatMoney($p['max_amount']) ?></td>
                <td style="font-weight:700;color:#00d4c8"><?= $p['daily_return'] ?>%</td>
                <td class="text-muted"><?= $p['duration_days'] ?> days</td>
                <td><span class="badge <?= $p['is_active']?'badge-active':'badge-rejected' ?>"><?= $p['is_active']?'Active':'Disabled' ?></span></td>
                <td>
                  <form method="POST" action="/admin/toggle-plan">
                    <input type="hidden" name="plan_id" value="<?= $p['id'] ?>">
                    <button class="btn <?= $p['is_active']?'btn-danger':'btn-success' ?> btn-xs">
                      <i class="fas fa-<?= $p['is_active']?'pause':'play' ?>"></i> <?= $p['is_active']?'Disable':'Enable' ?>
                    </button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-plus" style="color:#00e5a0"></i> Add New Plan</div>
          </div>
          <form method="POST" action="/admin/add-plan">
            <div class="form-group">
              <label class="form-label">Plan Name</label>
              <input type="text" name="name" class="form-control" placeholder="e.g. Diamond Plan" required>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Min Amount ($)</label>
                <input type="number" name="min_amount" class="form-control" placeholder="100" min="1" required>
              </div>
              <div class="form-group">
                <label class="form-label">Max Amount ($)</label>
                <input type="number" name="max_amount" class="form-control" placeholder="999" min="1" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Daily Return (%)</label>
                <input type="number" name="daily_return" class="form-control" placeholder="2.5" min="0.01" step="0.01" required>
              </div>
              <div class="form-group">
                <label class="form-label">Duration (days)</label>
                <input type="number" name="duration_days" class="form-control" placeholder="7" min="1" required>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="2" placeholder="Plan description..."></textarea>
            </div>
            <button type="submit" class="btn btn-accent btn-full"><i class="fas fa-plus"></i> Add Plan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
