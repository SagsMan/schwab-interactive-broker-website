<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($user['full_name']) ?> — Admin — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">User Detail</div>
        <div class="topbar-subtitle"><a href="/admin/users" style="color:#8892b0"><i class="fas fa-arrow-left"></i> Back to Users</a></div>
      </div>
    </div>
    <div class="page-body">
      <?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <!-- User Header -->
      <div class="user-detail-header">
        <div class="user-detail-avatar"><?= strtoupper(substr($user['full_name'],0,1)) ?></div>
        <div style="flex:1">
          <h2><?= e($user['full_name']) ?></h2>
          <div style="color:#8892b0;font-size:14px;margin-bottom:8px">
            <i class="fas fa-envelope"></i> <?= e($user['email']) ?>
            <?php if($user['phone']): ?> &nbsp;|&nbsp; <i class="fas fa-phone"></i> <?= e($user['phone']) ?><?php endif; ?>
            <?php if($user['country']): ?> &nbsp;|&nbsp; <i class="fas fa-globe"></i> <?= e($user['country']) ?><?php endif; ?>
          </div>
          <div class="user-detail-badges">
            <span class="badge <?= $user['is_active']?'badge-active':'badge-rejected' ?>"><?= $user['is_active']?'Active':'Suspended' ?></span>
            <?php if(!empty($user['is_restricted'])): ?><span class="badge badge-pending"><i class="fas fa-lock"></i> Restricted</span><?php endif; ?>
            <span class="badge badge-completed">ID: #<?= $user['id'] ?></span>
            <span class="badge" style="background:rgba(255,255,255,.05)"><i class="fas fa-calendar"></i> Joined <?= date('M d, Y', strtotime($user['created_at'])) ?></span>
          </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <form method="POST" action="/admin/user-toggle" style="display:inline">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <button class="btn <?= $user['is_active']?'btn-danger':'btn-success' ?> btn-sm">
              <i class="fas fa-<?= $user['is_active']?'ban':'circle-check' ?>"></i> <?= $user['is_active']?'Suspend':'Activate' ?>
            </button>
          </form>
          <form method="POST" action="/admin/restrict-user" style="display:inline">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="action" value="<?= empty($user['is_restricted'])?'restrict':'unrestrict' ?>">
            <button class="btn <?= empty($user['is_restricted'])?'btn-warning':'btn-success' ?> btn-sm">
              <i class="fas fa-<?= empty($user['is_restricted'])?'lock':'unlock' ?>"></i>
              <?= empty($user['is_restricted'])?'Restrict':'Unrestrict' ?>
            </button>
          </form>
        </div>
      </div>

      <!-- Balance Cards -->
      <div class="admin-stats-big">
        <?php foreach([
          ['Balance','fas fa-wallet','accent',formatMoney($user['balance']),'balance'],
          ['Total Profit','fas fa-arrow-trend-up','green',formatMoney($user['total_profit']),'total_profit'],
          ['Bonus','fas fa-gift','blue',formatMoney($user['bonus']),'bonus'],
          ['Referral Bonus','fas fa-users','yellow',formatMoney($user['referral_bonus']),'referral_bonus'],
          ['Withdrawals','fas fa-arrow-up','red',formatMoney($user['withdrawals']),'withdrawals'],
        ] as $s): ?>
        <div class="stat-card <?= $s[2] ?>">
          <div class="stat-icon <?= $s[2] ?>"><i class="<?= $s[1] ?>"></i></div>
          <div class="stat-content">
            <div class="stat-label"><?= $s[0] ?></div>
            <div class="stat-value <?= $s[2] ?>"><?= $s[3] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
        <!-- Update Balance -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-pen-to-square" style="color:#00d4c8"></i> Update Balance</div>
          </div>
          <form method="POST" action="/admin/update-balance">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Field</label>
                <select name="field" class="form-control">
                  <option value="balance">Balance</option>
                  <option value="total_profit">Total Profit</option>
                  <option value="bonus">Bonus</option>
                  <option value="referral_bonus">Referral Bonus</option>
                  <option value="withdrawals">Withdrawals</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Operation</label>
                <select name="op" class="form-control">
                  <option value="set">Set to exact amount</option>
                  <option value="add">Add to current</option>
                  <option value="subtract">Subtract from current</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Amount (USD)</label>
              <div class="input-group">
                <span class="input-prefix">$</span>
                <input type="number" name="amount" class="form-control" placeholder="0.00" min="0" step="0.01" required>
              </div>
            </div>
            <button type="submit" class="btn btn-accent btn-full"><i class="fas fa-save"></i> Update Balance</button>
          </form>
        </div>

        <!-- Send Email -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-envelope" style="color:#4a9eff"></i> Send Email to User</div>
          </div>
          <form method="POST" action="/admin/send-email">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <div class="form-group">
              <label class="form-label">To</label>
              <input type="text" class="form-control" value="<?= e($user['email']) ?>" disabled style="opacity:.7">
            </div>
            <div class="form-group">
              <label class="form-label">Subject</label>
              <select name="subject" class="form-control" id="emailSubject" onchange="fillBody()">
                <option value="Account Update">Account Update</option>
                <option value="Deposit Confirmed">Deposit Confirmed</option>
                <option value="Withdrawal Processed">Withdrawal Processed</option>
                <option value="Important Account Notice">Important Account Notice</option>
                <option value="KYC Verification Required">KYC Verification Required</option>
                <option value="Custom Message">Custom Message</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Message</label>
              <textarea name="body" id="emailBody" class="form-control" rows="4" required placeholder="Type your message here..."></textarea>
            </div>
            <button type="submit" class="btn btn-full" style="background:rgba(74,158,255,.12);border:1px solid rgba(74,158,255,.3);color:#4a9eff">
              <i class="fas fa-paper-plane"></i> Send Email
            </button>
          </form>
        </div>
      </div>

      <!-- Send Notification -->
      <div class="card mb-20">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-bell" style="color:#ffd700"></i> Send In-App Notification</div>
        </div>
        <form method="POST" action="/admin/add-notification" style="display:flex;gap:12px;align-items:flex-end">
          <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
          <div class="form-group" style="flex:1;margin:0">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" placeholder="Notification title" required>
          </div>
          <div class="form-group" style="flex:2;margin:0">
            <label class="form-label">Message</label>
            <input type="text" name="message" class="form-control" placeholder="Notification message" required>
          </div>
          <button type="submit" class="btn btn-warning btn-sm" style="flex-shrink:0"><i class="fas fa-paper-plane"></i> Send</button>
        </form>
      </div>

      <!-- Profile Info -->
      <?php if($user['address'] || $user['gender'] || $user['country']): ?>
      <div class="card mb-20">
        <div class="card-header"><div class="card-title"><i class="fas fa-id-card" style="color:#00d4c8"></i> Profile Information</div></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px">
          <?php foreach(['phone'=>'Phone','country'=>'Country','gender'=>'Gender','address'=>'Address','referral_code'=>'Referral Code'] as $f=>$l): ?>
          <?php if($user[$f]): ?>
          <div style="background:var(--bg2);border-radius:8px;padding:12px">
            <div style="font-size:11px;color:#8892b0;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px"><?=$l?></div>
            <div style="font-size:13px;font-weight:600;color:#fff"><?= e($user[$f]) ?></div>
          </div>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <!-- Transactions -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-credit-card" style="color:#00d4c8"></i> Transactions</div>
            <span class="badge badge-pending"><?= count($txs) ?></span>
          </div>
          <div class="table-wrap" style="max-height:300px;overflow-y:auto">
            <table>
              <thead><tr><th>Type</th><th>Amount</th><th>Status</th><th>Date</th><th>Act</th></tr></thead>
              <tbody>
              <?php foreach($txs as $tx): ?>
              <tr>
                <td><span class="badge badge-<?= e($tx['type']) ?>"><?= ucfirst($tx['type']) ?></span></td>
                <td class="td-name" style="font-size:13px"><?= formatMoney($tx['amount']) ?></td>
                <td><span class="badge badge-<?= e($tx['status']) ?>"><?= ucfirst($tx['status']) ?></span></td>
                <td class="text-muted fs-11"><?= date('M d, H:i', strtotime($tx['created_at'])) ?></td>
                <td>
                  <?php if($tx['status']==='pending'): ?>
                  <form method="POST" action="/admin/tx-action">
                    <input type="hidden" name="tx_id" value="<?= $tx['id'] ?>">
                    <button name="action" value="approve" class="btn btn-success btn-xs"><i class="fas fa-check"></i></button>
                    <button name="action" value="reject" class="btn btn-danger btn-xs"><i class="fas fa-times"></i></button>
                  </form>
                  <?php else: ?>—<?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Support Tickets -->
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-ticket" style="color:#ff8c42"></i> Support Tickets</div>
          </div>
          <?php if($tickets): ?>
          <?php foreach($tickets as $t): ?>
          <div style="padding:10px 0;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
              <div style="font-weight:600;color:#fff;font-size:13px;flex:1"><?= e($t['subject']) ?></div>
              <span class="badge badge-<?= $t['status']==='open'?'pending':'approved' ?>"><?= ucfirst($t['status']) ?></span>
            </div>
            <div style="font-size:12px;color:#8892b0;margin-top:4px"><?= e(substr($t['message'],0,80)) ?>...</div>
            <div style="font-size:11px;color:#5c6585;margin-top:4px"><?= date('M d, Y', strtotime($t['created_at'])) ?></div>
          </div>
          <?php endforeach; ?>
          <?php else: ?><div style="text-align:center;padding:20px;color:#5c6585;font-size:13px">No support tickets</div><?php endif; ?>
        </div>
      </div>

      <!-- Email History -->
      <?php if($emailLogs): ?>
      <div class="card" style="margin-top:20px">
        <div class="card-header">
          <div class="card-title"><i class="fas fa-envelope-open-text" style="color:#4a9eff"></i> Email History</div>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Subject</th><th>Sent At</th></tr></thead>
            <tbody>
            <?php foreach($emailLogs as $log): ?>
            <tr>
              <td class="td-name" style="font-size:13px"><?= e($log['subject']) ?></td>
              <td class="text-muted fs-12"><?= date('M d, Y H:i', strtotime($log['sent_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<script>
const templates={
  'Account Update':'Dear Investor,\n\nYour account has been reviewed and updated. Please log in to review the changes.\n\nBest regards,\nSchwab Interactive Broker',
  'Deposit Confirmed':'Dear Investor,\n\nYour deposit has been confirmed and credited to your account. Log in to start investing.\n\nBest regards,\nSchwab Interactive Broker',
  'Withdrawal Processed':'Dear Investor,\n\nYour withdrawal request has been processed and sent to your wallet. Please allow 24-48 hours for blockchain confirmation.\n\nBest regards,\nSchwab Interactive Broker',
  'Important Account Notice':'Dear Investor,\n\nThis is an important notice regarding your account. Please log in and review your account status or contact support.\n\nBest regards,\nSchwab Interactive Broker',
  'KYC Verification Required':'Dear Investor,\n\nTo comply with regulations, we require identity verification (KYC). Please contact support to provide the necessary documents.\n\nBest regards,\nSchwab Interactive Broker',
};
function fillBody(){
  const s=document.getElementById('emailSubject').value;
  if(templates[s]) document.getElementById('emailBody').value=templates[s];
}
fillBody();
</script>
</body>
</html>
