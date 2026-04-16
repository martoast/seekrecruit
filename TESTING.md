# Seek & Recruit — Test Accounts

All accounts use the password: **`password`**

The deployed app has three user tiers. Pick one depending on what you want to test.

---

## 🏢 Super Admin (S&R)
Full platform control — manages client companies and HR admins.

| Email | What they see |
|---|---|
| `admin@seekrecruit.com` | Every client, every position, every application. Plus two extra sidebar sections: **Clients** (add/edit/archive client companies) and **Admins** (create/edit HR admin accounts). |

**Try:**
- Dashboard with aggregate stats (22 candidates, 54 applications platform-wide)
- Click **Clients** → pick one → "View Dashboard" to pivot into that client's scoped view
- Use `?client_id=X` on any admin URL to filter
- Create a new client + assign a new HR admin to them

---

## 👔 HR Admins (scoped to one client)
See only their client's positions, applications, candidates, and interviews.

| Email | Client | Pipeline |
|---|---|---|
| `maria@seekrecruit.com` | **JAE Tijuana** (Manufacturing) | 20 applications · 4 hired |
| `jorge@acme.com` | **Acme Engineering** (Automotive) | 18 applications · 2 hired |
| `elena@tjelectronics.com` | **Tijuana Electronics** (IoT) | 16 applications · 2 hired |

**Try:**
- Dashboard shows only their client's pipeline (banner at top confirms which one)
- Candidates list only shows people who applied to their client
- Cannot see or touch Clients / Admins sections (they're Super Admin only)
- Attempting to access another client's data returns 403

---

## 👤 Candidates
Public-site users who apply to positions and track their own applications.

### Featured candidates (rich pipeline data)

| Email | Profile | Application story |
|---|---|---|
| `juan@example.com` | UABC · Computer Science · PHP/Laravel | 2 apps — JAE *interview stage*, Acme *registered* |
| `ana@example.com` | CETYS · Software Eng · React/Node | 2 apps — JAE *preselected*, Acme *finalist* (multiple interviews done) |
| `carlos@example.com` | UABC · Mechanical · SolidWorks | JAE *evaluation* (CAD test passed) |
| `sofia@example.com` | ITT · Industrial · Lean/SAP | JAE **hired** ✅ |
| `diego@example.com` | UABC · Electronics · Embedded | TJE discarded + JAE registered |
| `laura@example.com` | CETYS · Computer Sci · Python/Django | 2 apps — JAE *interview*, Acme *preselected* |
| `roberto@example.com` | ITT · Mechatronics · PLC/Robotics | Acme *evaluation* |

### Additional candidates
15 more factory-generated candidates with varied Mexican names, TJ-area universities (UABC, CETYS, ITT, Xochicalco, UdeG, Ibero), and realistic skill mixes. See them via `admin@seekrecruit.com` → **Candidates**.

**Try as a candidate:**
- Dashboard shows profile completion %, recent applications, quick actions
- Browse `/positions` (public) and apply to any open one
- `/candidate/profile` — update details, upload CV, upload profile photo
- `/candidate/referrals` — send a referral email

---

## 🧪 Quick smoke test

1. Log in as **`juan@example.com`** → apply to a new position → log out
2. Log in as **`maria@seekrecruit.com`** → find Juan's new JAE application in `/admin/applications` → change status via the stepper → add a note
3. Log in as **`jorge@acme.com`** → confirm you **can't** see Juan's JAE application (should be hidden)
4. Log in as **`admin@seekrecruit.com`** → see both views under `/admin/clients/1` and `/admin/clients/2` — pipelines match what Maria + Jorge each saw

If all four of those work end-to-end, the multi-tenancy and pipeline flow is solid.

---

## 🏠 Public pages (no login)

- `/` — landing page
- `/positions` — 10 open positions (the draft + closed ones are hidden from the public listing)
- `/positions/{id}` — detail page with salary, modality, and "Apply" button (CTA routes to login for guests)
- `/login` / `/register` / `/forgot-password`

---

## 🔄 Resetting to a clean state

If testers want to wipe and reseed:

```bash
php artisan migrate:fresh --seed
```

This drops every table, re-runs all migrations, and re-seeds the 3 clients / 26 users / 13 positions / 54 applications / 67 interviews / 7 referrals. Safe to run anytime in a dev/staging environment — **do not run in production**.
