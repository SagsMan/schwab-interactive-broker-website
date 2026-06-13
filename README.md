# Investment Dolphin

A demo investment/trading platform built with PHP + HTML/CSS/JS and PostgreSQL. Two-part system: user dashboard and admin panel. For educational purposes only — no real money involved.

## Run & Operate

- PHP server: `php -S 0.0.0.0:$PORT -t artifacts/investment-platform/public artifacts/investment-platform/public/index.php`
- Workflow: `artifacts/investment-platform: web` (auto-managed by Replit)
- Required env: `DATABASE_URL` or `PGHOST/PGPORT/PGUSER/PGPASSWORD/PGDATABASE`

## Stack

- **Backend**: PHP 8.2 built-in server, custom router in `public/index.php`
- **Frontend**: Pure HTML/CSS/JS — dark navy (`#0a0d1f`) + cyan (`#00d4c8`) theme
- **DB**: PostgreSQL (Replit managed), PDO driver
- **Auth**: PHP sessions + bcrypt passwords

## Where things live

```
artifacts/investment-platform/
├── public/
│   ├── index.php          ← Front controller + all routes + all controllers
│   └── assets/css/style.css
├── app/
│   ├── config/database.php
│   └── views/
│       ├── landing.php
│       ├── auth/login.php, register.php
│       ├── user/dashboard.php, deposit.php, withdraw.php, transactions.php,
│       │        transfer.php, trading_plans.php, trade_signals.php,
│       │        referrals.php, notifications.php, support.php
│       ├── admin/dashboard.php, users.php, user_detail.php,
│       │         transactions.php, plans.php
│       └── layouts/sidebar.php
└── database.sql           ← SQL schema + seed file
```

## Architecture decisions

- All routing and controller logic is in `public/index.php` — simple, no framework
- Clean URL routing: PHP built-in server router script intercepts all requests; static files served via `return false`
- DB schema auto-initializes on first request via `initSchema()`
- Admin default login: `admin@admin.com` / `admin@admin2026`
- Sessions for auth, bcrypt for passwords

## Product

- **Landing page** — hero, features, stats, CTA
- **User registration/login** with referral code support
- **User dashboard** — balance, profit, bonus, referral bonus, withdrawals
- **Finance management** — Deposit, Withdraw, Transfer Funds, Transactions
- **Trading** — Trading Plans (4 tiers), Trade Signals, Active Plans
- **Account** — Referrals (+$10/referral), Notifications, Support tickets
- **Admin panel** — Dashboard stats, User management, Transaction approve/reject, Balance editing, Plan management

## User preferences

_Populate as you build._

## Gotchas

- PHP built-in server is single-threaded — fine for demo, not production
- `initSchema()` runs on every request but is safe (uses `IF NOT EXISTS`)
- The `integratedSkills` field in artifact.toml must be preserved when editing TOML
- Admin user seeded with bcrypt hash of `admin@admin2026`

## Pointers

- SQL schema file: `artifacts/investment-platform/database.sql`
- Main router/controller: `artifacts/investment-platform/public/index.php`
- Styles: `artifacts/investment-platform/public/assets/css/style.css`
