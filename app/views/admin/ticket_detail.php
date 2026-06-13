<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Ticket #<?= $ticket['id'] ?> — Admin</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">Ticket #<?= $ticket['id'] ?></div>
        <div class="topbar-subtitle"><a href="/admin/tickets" style="color:#8892b0"><i class="fas fa-arrow-left"></i> Back to Tickets</a></div>
      </div>
    </div>
    <div class="page-body">
      <?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:24px">
        <div>
          <div class="card mb-20">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-ticket" style="color:#ff8c42"></i> <?= e($ticket['subject']) ?></div>
              <span class="badge badge-<?= $ticket['status']==='open'?'pending':'approved' ?>"><?= ucfirst($ticket['status']) ?></span>
            </div>
            <div style="background:var(--bg2);border-radius:8px;padding:16px;margin-bottom:16px">
              <div style="font-size:12px;color:#8892b0;margin-bottom:8px"><i class="fas fa-user"></i> <?= e($ticket['full_name']) ?> (<?= e($ticket['email']) ?>)</div>
              <div style="font-size:14px;color:#e8eaf6;line-height:1.7"><?= nl2br(e($ticket['message'])) ?></div>
              <div style="font-size:11px;color:#5c6585;margin-top:10px"><i class="fas fa-clock"></i> <?= date('M d, Y H:i', strtotime($ticket['created_at'])) ?></div>
            </div>

            <?php if($ticket['reply']): ?>
            <div style="background:rgba(0,212,200,.06);border:1px solid rgba(0,212,200,.2);border-radius:8px;padding:16px">
              <div style="font-size:12px;color:#00d4c8;font-weight:600;margin-bottom:8px"><i class="fas fa-reply"></i> Admin Reply — <?= date('M d, Y H:i', strtotime($ticket['replied_at'])) ?></div>
              <div style="font-size:14px;color:#e8eaf6;line-height:1.7"><?= nl2br(e($ticket['reply'])) ?></div>
            </div>
            <?php endif; ?>
          </div>

          <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-reply" style="color:#00d4c8"></i> Reply to Ticket</div></div>
            <form method="POST" action="/admin/reply-ticket">
              <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
              <div class="form-group">
                <label class="form-label">Your Reply</label>
                <textarea name="reply" class="form-control" rows="5" placeholder="Type your reply..." required></textarea>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Set Status</label>
                  <select name="status" class="form-control">
                    <option value="closed">Closed (resolved)</option>
                    <option value="open">Open (pending)</option>
                  </select>
                </div>
                <div style="display:flex;align-items:flex-end">
                  <button type="submit" class="btn btn-accent btn-full"><i class="fas fa-paper-plane"></i> Send Reply</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-title" style="font-size:14px;margin-bottom:14px"><i class="fas fa-user" style="color:#00d4c8"></i> User Details</div>
          <?php foreach(['full_name'=>'Name','email'=>'Email'] as $f=>$l): ?>
          <div class="ap-row"><span class="ap-label"><?=$l?></span><span class="ap-val"><?= e($ticket[$f]) ?></span></div>
          <?php endforeach; ?>
          <div class="ap-row"><span class="ap-label">User ID</span><span class="ap-val"><?= $ticket['user_id'] ?></span></div>
          <a href="/admin/users/<?= $ticket['user_id'] ?>" class="btn btn-outline btn-sm btn-full" style="margin-top:14px">
            <i class="fas fa-user"></i> View Full Profile
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
