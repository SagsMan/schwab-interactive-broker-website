<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create Account — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="demo-banner"><i class="fas fa-shield-halved"></i>&nbsp;<strong>DEMO ACCOUNT ONLY</strong> — Educational purposes only.</div>
<div class="auth-wrap" style="align-items:flex-start;padding:80px 20px">
  <div class="auth-card" style="max-width:560px">
    <div class="auth-logo">
      <div class="logo-mark" style="justify-content:center;gap:12px">
        <div class="logo-icon" style="width:44px;height:44px;font-size:20px">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00d4c8" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
        </div>
        <div style="text-align:left">
          <div style="font-weight:800;font-size:16px">Schwab Interactive Broker</div>
          <div style="font-size:11px;color:#8892b0;letter-spacing:.5px">CREATE INVESTOR ACCOUNT</div>
        </div>
      </div>
    </div>

    <div class="auth-title">Create Your Account</div>
    <div class="auth-sub">Join 48,000+ investors. Free to get started.</div>

    <?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div><?php endif; ?>

    <form method="POST" action="/register">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Full Name *</label>
          <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address *</label>
          <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input type="tel" name="phone" class="form-control" placeholder="+1 234 567 8900">
        </div>
        <div class="form-group">
          <label class="form-label">Country</label>
          <select name="country" class="form-control">
            <option value="">Select Country</option>
            <?php $countries=['United States','United Kingdom','Canada','Australia','Nigeria','Ghana','Kenya','South Africa','Germany','France','India','Pakistan','UAE','Saudi Arabia','Singapore','Malaysia','Brazil','Mexico','Other']; foreach($countries as $c): ?><option value="<?=$c?>"><?=$c?></option><?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-control">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
            <option value="prefer_not">Prefer not to say</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Referral Code (optional)</label>
          <input type="text" name="referral_code" class="form-control" placeholder="e.g. ABC12345" value="<?= e($_GET['ref'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Home Address</label>
        <input type="text" name="address" class="form-control" placeholder="123 Main Street, City, Country">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Password *</label>
          <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required minlength="6">
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password *</label>
          <input type="password" name="confirm_password" id="cpass" class="form-control" placeholder="Repeat password" required>
        </div>
      </div>
      <div class="alert alert-info" style="font-size:12px;margin-bottom:16px">
        <i class="fas fa-info-circle"></i> This is a demo platform. No real money involved. For educational purposes only.
      </div>
      <button type="submit" class="btn btn-accent btn-full" style="font-size:15px;padding:13px">
        <i class="fas fa-user-plus"></i> Create Free Account
      </button>
    </form>

    <p style="text-align:center;margin-top:18px;font-size:13px;color:#8892b0">
      Already have an account? <a href="/login" style="font-weight:600">Sign in</a>
    </p>
    <p style="text-align:center;margin-top:8px;font-size:12px;color:#5c6585">
      <a href="/" style="color:#5c6585"><i class="fas fa-arrow-left"></i> Back to homepage</a>
    </p>
  </div>
</div>
</body>
</html>
