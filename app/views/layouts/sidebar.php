<?php
$currentPath = strtok($_SERVER['REQUEST_URI'], '?');
function isActive(string $path): string { global $currentPath; return $currentPath === $path ? 'active' : ''; }
function isActivePrefix(string $prefix): string { global $currentPath; return str_starts_with($currentPath, $prefix) ? 'active' : ''; }
$isAdminArea = str_starts_with($currentPath, '/admin');
$unread = $unread ?? 0;
?>
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-mark">
      <div class="logo-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00d4c8" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
      </div>
      <div class="logo-name">
        <div>Schwab Interactive</div>
        <small>BROKER</small>
      </div>
    </div>
  </div>

  <div class="sidebar-user">
    <div class="user-avatar"><?= strtoupper(substr($_SESSION['full_name'] ?? 'U', 0, 1)) ?></div>
    <div class="user-info">
      <div class="user-name"><?= e($_SESSION['full_name'] ?? '') ?></div>
      <div class="user-role"><?= isAdmin() ? 'Administrator' : 'Investor' ?></div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <?php if ($isAdminArea): ?>
      <div class="nav-section-label">Admin Panel</div>
      <a href="/admin" class="nav-item <?= isActive('/admin') ?>">
        <span class="icon"><i class="fas fa-gauge-high"></i></span> Dashboard
      </a>
      <a href="/admin/users" class="nav-item <?= isActivePrefix('/admin/users') ?>">
        <span class="icon"><i class="fas fa-users"></i></span> Users
      </a>
      <a href="/admin/transactions" class="nav-item <?= isActive('/admin/transactions') ?>">
        <span class="icon"><i class="fas fa-credit-card"></i></span> Transactions
      </a>
      <a href="/admin/plans" class="nav-item <?= isActive('/admin/plans') ?>">
        <span class="icon"><i class="fas fa-chart-line"></i></span> Trading Plans
      </a>
      <a href="/admin/tickets" class="nav-item <?= isActivePrefix('/admin/tickets') ?>">
        <span class="icon"><i class="fas fa-ticket"></i></span> Support Tickets
      </a>
      <a href="/admin/notifications" class="nav-item <?= isActive('/admin/notifications') ?>">
        <span class="icon"><i class="fas fa-bullhorn"></i></span> Broadcast
      </a>
      <a href="/admin/email-logs" class="nav-item <?= isActive('/admin/email-logs') ?>">
        <span class="icon"><i class="fas fa-envelope-open-text"></i></span> Email Logs
      </a>
      <hr style="border:none;border-top:1px solid var(--border);margin:10px 8px">
      <a href="/dashboard" class="nav-item">
        <span class="icon"><i class="fas fa-arrow-left"></i></span> User View
      </a>
    <?php else: ?>
      <div class="nav-section-label">Overview</div>
      <a href="/dashboard" class="nav-item <?= isActive('/dashboard') ?>">
        <span class="icon"><i class="fas fa-house"></i></span> Dashboard
      </a>
      <div class="nav-section-label">Finance</div>
      <a href="/deposit" class="nav-item <?= isActive('/deposit') ?>">
        <span class="icon"><i class="fas fa-arrow-down-to-bracket"></i></span> Deposit
      </a>
      <a href="/withdraw" class="nav-item <?= isActive('/withdraw') ?>">
        <span class="icon"><i class="fas fa-arrow-up-from-bracket"></i></span> Withdraw
      </a>
      <a href="/transactions" class="nav-item <?= isActive('/transactions') ?>">
        <span class="icon"><i class="fas fa-list-ul"></i></span> Transactions
      </a>
      <a href="/transfer" class="nav-item <?= isActive('/transfer') ?>">
        <span class="icon"><i class="fas fa-right-left"></i></span> Transfer Funds
      </a>
      <div class="nav-section-label">Invest</div>
      <a href="/trading-plans" class="nav-item <?= isActive('/trading-plans') ?>">
        <span class="icon"><i class="fas fa-chart-line"></i></span> Trading Plans
      </a>
      <a href="/trade-signals" class="nav-item <?= isActive('/trade-signals') ?>">
        <span class="icon"><i class="fas fa-signal"></i></span> Trade Signals
      </a>
      <a href="/mining" class="nav-item <?= isActive('/mining') ?>">
        <span class="icon"><i class="fas fa-microchip"></i></span> Mining
      </a>
      <div class="nav-section-label">Account</div>
      <a href="/referrals" class="nav-item <?= isActive('/referrals') ?>">
        <span class="icon"><i class="fas fa-users"></i></span> Referrals
      </a>
      <a href="/notifications" class="nav-item <?= isActive('/notifications') ?>">
        <span class="icon"><i class="fas fa-bell"></i></span> Notifications
        <?php if ($unread > 0): ?>
          <span class="notif-badge"><?= $unread ?></span>
        <?php endif; ?>
      </a>
      <a href="/support" class="nav-item <?= isActive('/support') ?>">
        <span class="icon"><i class="fas fa-headset"></i></span> Support
      </a>
      <a href="/profile" class="nav-item <?= isActive('/profile') ?>">
        <span class="icon"><i class="fas fa-user-circle"></i></span> My Profile
      </a>
      <?php if (isAdmin()): ?>
        <hr style="border:none;border-top:1px solid var(--border);margin:10px 8px">
        <a href="/admin" class="nav-item">
          <span class="icon"><i class="fas fa-shield-halved"></i></span> Admin Panel
        </a>
      <?php endif; ?>
    <?php endif; ?>
  </nav>

  <div class="sidebar-footer">
    <a href="/logout" class="btn btn-outline btn-sm btn-full">
      <i class="fas fa-right-from-bracket"></i> Logout
    </a>
  </div>
</aside>
