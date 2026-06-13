<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Profile — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-layout">
  <?php require ROOT . '/app/views/layouts/sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div class="topbar-title">My Profile</div>
    </div>
    <div class="page-body">
      <?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($err) ?></div><?php endif; ?>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-user-circle" style="color:#00d4c8"></i> Personal Information</div>
          </div>
          <form method="POST" action="/profile">
            <div class="form-group">
              <label class="form-label">Full Name</label>
              <input type="text" name="full_name" class="form-control" value="<?= e($user['full_name']) ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Email Address</label>
              <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled style="opacity:.6">
              <div class="form-hint">Email cannot be changed</div>
            </div>
            <div class="form-group">
              <label class="form-label">Phone Number</label>
              <input type="tel" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>" placeholder="+1 234 567 8900">
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" value="<?= e($user['country'] ?? '') ?>" placeholder="Your country">
              </div>
              <div class="form-group">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-control">
                  <?php foreach([''=>'Select...','male'=>'Male','female'=>'Female','other'=>'Other','prefer_not'=>'Prefer not to say'] as $v=>$l): ?>
                  <option value="<?=$v?>" <?= ($user['gender']??'')===$v?'selected':'' ?>><?=$l?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Home Address</label>
              <input type="text" name="address" class="form-control" value="<?= e($user['address'] ?? '') ?>" placeholder="123 Main Street, City">
            </div>
            <hr style="border:none;border-top:1px solid var(--border);margin:20px 0">
            <div class="card-title" style="font-size:14px;margin-bottom:14px;color:#8892b0">Change Password (leave blank to keep current)</div>
            <div class="form-group">
              <label class="form-label">New Password</label>
              <input type="password" name="new_password" class="form-control" placeholder="Min. 6 characters" minlength="6">
            </div>
            <button type="submit" class="btn btn-accent btn-full"><i class="fas fa-save"></i> Save Changes</button>
          </form>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px">
          <div class="card">
            <div class="card-title" style="margin-bottom:14px;font-size:14px"><i class="fas fa-id-card" style="color:#00d4c8"></i> Account Summary</div>
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border)">
              <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#00d4c8,#4a9eff);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#000">
                <?= strtoupper(substr($user['full_name'],0,1)) ?>
              </div>
              <div>
                <div style="font-size:16px;font-weight:700;color:#fff"><?= e($user['full_name']) ?></div>
                <div style="font-size:13px;color:#8892b0"><?= e($user['email']) ?></div>
                <div style="margin-top:6px"><span class="badge badge-active"><i class="fas fa-circle"></i> Active Investor</span></div>
              </div>
            </div>
            <?php foreach([
              ['fas fa-wallet','Account Balance',formatMoney($user['balance']),'#00d4c8'],
              ['fas fa-arrow-trend-up','Total Profit',formatMoney($user['total_profit']),'#00e5a0'],
              ['fas fa-share-nodes','Referral Code',$user['referral_code'],'#ffd700'],
              ['fas fa-calendar','Member Since',date('M d, Y',strtotime($user['created_at'])),'#8892b0'],
            ] as $r): ?>
            <div class="ap-row">
              <span class="ap-label"><i class="<?=$r[0]?>" style="color:<?=$r[3]?>"></i> <?=$r[1]?></span>
              <span style="font-weight:600;color:<?=$r[3]?>;font-size:13px"><?=$r[2]?></span>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="card">
            <div class="card-title" style="font-size:14px;margin-bottom:12px"><i class="fas fa-shield-halved" style="color:#00d4c8"></i> Account Security</div>
            <div class="alert alert-info" style="margin-bottom:0">
              <i class="fas fa-lock"></i>
              <div>Your account is secured with bcrypt password hashing. Change your password regularly for best security.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
