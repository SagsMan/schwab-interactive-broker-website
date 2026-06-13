<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Sign In — Schwab Interactive Broker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="demo-banner"><i class="fas fa-shield-halved"></i>&nbsp;<strong>DEMO ACCOUNT ONLY</strong> — Educational purposes only.</div>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="logo-mark" style="justify-content:center;gap:12px">
        <div class="logo-icon" style="width:44px;height:44px;font-size:20px">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00d4c8" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
        </div>
        <div style="text-align:left">
          <div style="font-weight:800;font-size:16px">Schwab Interactive Broker</div>
          <div style="font-size:11px;color:#8892b0;letter-spacing:.5px">INVESTOR PORTAL</div>
        </div>
      </div>
    </div>

    <div class="auth-title">Welcome Back</div>
    <div class="auth-sub">Sign in to your investment account</div>

    <?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($success) ?></div><?php endif; ?>

    <form method="POST" action="/login">
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-group">
          <i class="fas fa-envelope input-prefix"></i>
          <input type="email" name="email" class="form-control" placeholder="you@example.com" required autofocus>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-group">
          <i class="fas fa-lock input-prefix"></i>
          <input type="password" name="password" id="pwd" class="form-control" placeholder="••••••••" required style="padding-right:44px">
        </div>
        <button type="button" onclick="togglePwd()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:#8892b0;cursor:pointer;font-size:14px" id="eyeBtn"><i class="fas fa-eye" id="eyeIcon"></i></button>
      </div>
      <button type="submit" class="btn btn-accent btn-full" style="font-size:15px;padding:12px">
        <i class="fas fa-sign-in-alt"></i> Sign In
      </button>
    </form>

    <div class="auth-divider" style="margin:20px 0">or</div>
    <p style="text-align:center;font-size:13px;color:#8892b0">
      Don't have an account? <a href="/register" style="font-weight:600">Create one free</a>
    </p>
    <p style="text-align:center;margin-top:10px;font-size:12px;color:#5c6585">
      <a href="/" style="color:#5c6585"><i class="fas fa-arrow-left"></i> Back to homepage</a>
    </p>
  </div>
</div>
<script>
function togglePwd(){
  const p=document.getElementById('pwd'), i=document.getElementById('eyeIcon');
  p.type=p.type==='password'?'text':'password';
  i.className=p.type==='password'?'fas fa-eye':'fas fa-eye-slash';
}
</script>
</body>
</html>
