# Sprint 2 — Tier C: Multi-tenancy (Clients + Super Admin vs HR Admin)

The brief defines three user tiers: Candidate, HR Admin / Director (client-scoped), and Super Admin (S&R, platform-wide). Right now `jae_staff` is a single tier with blanket access. This sprint adds a `Client` tenant model and splits admin into two tiers with proper query scoping.

**Self-contained: this file is the source of truth for the sprint — it should contain enough detail that a fresh Claude session could pick up mid-sprint without having to re-derive decisions.**

---

## Goal (one paragraph)

Every admin record (Position, Application, Interview) becomes associated with a `Client`. HR admins are bound to exactly one client and only see that client's data. Super admins see everything and manage both Clients (the companies) and the HR Admin users within them. The candidate-facing public site stays unchanged — candidates don't pick a client, they apply to positions, and the client affiliation is derived from the position.

---

## Data model changes

### New table: `clients`

| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| name | string | e.g., "JAE Tijuana" |
| slug | string, unique | e.g., "jae-tijuana" — for URLs |
| industry | string, nullable | e.g., "Manufacturing" |
| logo | string, nullable | filename on public disk |
| is_active | boolean, default true | |
| created_at / updated_at | timestamps | |
| deleted_at | timestamp, nullable | soft delete |

Model: `App\Models\Client` — soft-deleted. Relations: `hasMany` users, `hasMany` positions. `getLogoUrlAttribute()` accessor that points to `asset('storage/client-logos/...')`.

### `users` — add `client_id`

Nullable foreign key to `clients.id`. Null for super admins and candidates, set for HR admins.

```
Schema::table('users', fn ($t) => $t->foreignId('client_id')->nullable()->after('role')->constrained()->nullOnDelete());
```

### `positions` — add `client_id`

Required (not nullable). Default during migration: backfill all existing positions to a seed client "JAE Tijuana".

```
Schema::table('positions', fn ($t) => $t->foreignId('client_id')->nullable()->after('id')->constrained());
// backfill:
// 1. Create the default client if it doesn't exist (or insert via raw SQL in the migration)
// 2. UPDATE positions SET client_id = <default_client_id> WHERE client_id IS NULL
// 3. Change column to non-nullable
```

### `UserRole` enum — rename + add

Current: `CANDIDATE = 'candidate'`, `JAE_STAFF = 'jae_staff'`.

New:
- `CANDIDATE = 'candidate'` (unchanged)
- `HR_ADMIN = 'hr_admin'` (renamed from `JAE_STAFF`)
- `SUPER_ADMIN = 'super_admin'` (new)

Migration renames the role value for existing users:
- `admin@seekrecruit.com` → `super_admin`
- All other `jae_staff` (including `maria@seekrecruit.com`) → `hr_admin` bound to the "JAE Tijuana" client

The enum column on `users` is `enum('candidate', 'jae_staff')` in the original migration. We need to:
1. Change the enum to include the new values (or make it a string)
2. Rename `jae_staff` rows to `hr_admin`

Cleanest approach: change the `role` column to a string (drop the enum constraint) since enum changes in MySQL are painful. The Laravel enum cast at the model level is what enforces validity from the app side.

---

## Role semantics

| Role | Can access | Scope |
|---|---|---|
| `candidate` | Public site + `/candidate/*` | Own data only |
| `hr_admin` | Public site + `/admin/*` (except Super-only routes) | Their `client_id` only |
| `super_admin` | Public site + `/admin/*` (including Clients + Admins CRUD) | All clients |

`User` model helpers:
- `isCandidate(): bool`
- `isHrAdmin(): bool`
- `isSuperAdmin(): bool`
- `isAdmin(): bool` — true for either `hr_admin` or `super_admin` (any non-candidate with admin access). **Keep this helper since blade templates already use it.**

---

## Middleware changes

### `EnsureUserHasRole` — accept comma-separated roles

```
Route::middleware(['auth', 'role:hr_admin,super_admin'])->...
```

Current signature is `handle(Request $request, Closure $next, string $role)`. Change to parse the comma list: `handle(..., string $roles)` and split on `,`. If user's role matches any entry, pass. Otherwise redirect.

Redirect logic: unauthenticated → login; wrong role → their "home" (candidate dashboard or admin dashboard depending on current role). Super admin should never be redirected away from admin routes, so the mismatch path only hits candidates who try to access admin URLs.

### Route groups

- `role:candidate` — unchanged (candidate area)
- `role:hr_admin,super_admin` — all existing admin routes (replace current `role:jae_staff`)
- `role:super_admin` — new routes for Clients CRUD and Admin user management

---

## Query scoping

### Where HR admins are restricted

Pattern: add an explicit filter in each admin controller's `index`/`show` methods. No global scopes — too easy to forget when you need to override.

**`Admin/PositionController`:**
- `index`: `->where('client_id', $user->client_id)` when HR admin
- `show` + `edit` + `update` + `destroy`: `abort_unless($position->client_id === $user->client_id || $user->isSuperAdmin(), 403)`
- `create`: HR admin → force `client_id = $user->client_id` on the form; Super admin → client dropdown
- `store`: validate `client_id` is set; HR admin overrides to their own

**`Admin/ApplicationController`:**
- `index`: `->whereHas('position', fn($q) => $q->where('client_id', $user->client_id))` when HR admin
- `show` + `updateStatus` + `addNote`: abort_unless check on the position's client_id
- Stats inside `index` same scoping

**`Admin/InterviewController`:**
- `index`: `->whereHas('application.position', fn($q) => $q->where('client_id', $user->client_id))` when HR admin
- `store` + `update` + `destroy`: abort_unless check via application→position→client_id
- The "Applications" dropdown for scheduling modal — scope the application list

**`Admin/CandidateController`:**
- `index`: HR admin sees only candidates who have applied to their client → `->whereHas('applications.position', fn($q) => $q->where('client_id', $user->client_id))`
- `show`: HR admin can view the candidate if that candidate has at least one application to their client; otherwise 403
- `downloadCv`: same rule as `show`
- Super admin sees all candidates unrestricted

**`Admin/DashboardController`:**
- Calls `StatsService::getStats($user)` or `getStats($clientId = null)`
- Super admin with no `?client_id` → platform-wide stats; with `?client_id=X` → that client's stats
- HR admin → always their client's stats

**`StatsService`:**
- Accept optional `?Client $client` parameter
- When set, filter all queries by `client_id` on positions/applications (joined for interviews)

---

## New pages (Super Admin only)

### `/admin/clients` — list
Columns: logo, name, industry, active positions count, total applications count, hires count, status, actions (edit, quick active-toggle).

### `/admin/clients/create`
Fields: name (required), slug (required, unique, auto-filled from name), industry (optional), logo (uploaded after creation via edit page), active (default true).

### `/admin/clients/{client}/edit`
Same fields. Logo upload + delete section (same pattern as positions/edit). Delete client button (soft-deletes; confirms that positions go with it).

### `/admin/clients/{client}` — detail
Shows: client info, list of HR admins, list of positions, applications pipeline snapshot, recent hires. Good Super Admin context page.

### `/admin/admins` — list HR admins
Columns: name, email, client, created_at, actions (edit, delete).
Super admin creates HR admin accounts here and assigns them to a client.

### `/admin/admins/create`
Fields: name, email, password (or "send invite link" — **we'll do direct password for now**, email invites are Tier F polish), client (dropdown), role defaults to hr_admin (no super-admin creation via UI yet — seed only).

### `/admin/admins/{user}/edit`
Same fields minus role (can't change role via UI). Can reassign client.

---

## UX touches

### Sidebar (partials/sidebar.blade.php)
Current nav items: Dashboard, Candidates, Applications, Interviews, Positions.
Add, visible only for `$user->isSuperAdmin()`:
- Clients (admin.clients.index)
- Admins (admin.admins.index)

Pattern: add `role` key to the `$navItems` array entries; filter the array before foreach.

### Client switcher (top of every admin page for Super Admin)
- Stored in session under `selected_client_id`
- Small dropdown in the admin layout header — "All Clients" or a specific client
- Changing it POSTs to a tiny controller action that sets the session and redirects back
- Controllers that scope by client use the session value for super admins; HR admins ignore it (always their client)

Actually to keep it simple for V1: just make it a `?client_id=X` query parameter, passed through links. No session. Simpler to implement and debug. **Decision: query param, not session.** Can revisit if nav gets annoying.

### HR admin client banner
Small banner at the top of the admin dashboard: "Viewing data for **{client name}**". On other admin pages, the sidebar footer or topbar shows the client name under the user chip.

### Candidate detail (Admin view)
When an HR admin views a candidate, only show the applications that belong to their client (filter `$candidate->applications` in the controller before passing to view).

---

## Unilateral decisions (flag if you'd change them)

1. **Role rename**: `jae_staff` → `hr_admin`. The old name was confusing (S&R staff ≠ client-side HR).
2. **One client per HR admin**. Multi-client HR admins (consultants who work with several companies) could come later via a pivot table. For now, 1:many.
3. **Default seed client "JAE Tijuana"** inherits all existing positions + `maria@seekrecruit.com`. `admin@seekrecruit.com` becomes the Super Admin (no client).
4. **Super Admin UI creates HR admins directly with a password**, not via email invite. Invite tokens + email flow belongs in Tier F polish.
5. **Super Admin's client filter is a query param, not session-persisted**. One source of truth per URL.
6. **Candidate profiles stay global**. HR admins see the subset of candidates that have applied to their client. Super admins see everything.
7. **Soft-deleting a client also soft-deletes its positions**. Applications stay intact (historical record). Enforced via the `Client::deleting` model event.
8. **No cross-client leak of interviews**. An HR admin can't schedule an interview for an application that isn't theirs, even if they guess the URL.
9. **Role column becomes a string** (drop the MySQL enum), so we can add roles without schema migrations later. Enum cast at the model level enforces the set.

---

## Out of scope (on purpose)

- Per-client branding on public pages (logos on position detail when the position belongs to a non-JAE client) — could be a nice Sprint 2.5 add-on but not required for Tier C.
- Billing / plan tiers per client.
- Client self-service signup flow.
- Impersonation / "log in as client" for Super Admins.
- Audit log for who changed what.
- Multi-client HR admins (see above).
- Email invites for new HR admins.

---

## Seeders

### `ClientSeeder` — new
Three seed clients:
1. **JAE Tijuana** — Manufacturing (the original/default)
2. **Acme Engineering** — Automotive
3. **Tijuana Electronics** — Electronics / IoT

### `UserSeeder` — updated
- `admin@seekrecruit.com` → Super Admin, no client
- `maria@seekrecruit.com` → HR Admin, client = JAE Tijuana
- New: `jorge@acme.com` → HR Admin, client = Acme Engineering
- New: `elena@tjelectronics.com` → HR Admin, client = Tijuana Electronics
- Candidates unchanged

### `PositionSeeder` — updated
Distribute the 7 seed positions across the 3 clients:
- JAE Tijuana: Junior Software Developer, Mechanical Engineer, Industrial Engineer Intern (3)
- Acme Engineering: Full Stack Developer, Quality Assurance Engineer (2)
- Tijuana Electronics: Electronics Technician, Data Analyst (2)

### `ApplicationSeeder` — unchanged
The existing seeder doesn't reference clients; it just joins candidates to positions. Applications will inherit the client through their position.

---

## Commit checkpoints

Small commits, pushed as we go, so if we get compacted or interrupted, the work is persistent on `origin/main`.

1. **`Sprint 2: plan`** — this file, committed before any code changes
2. **`Sprint 2: Client model + role refactor + schema`** — new migration(s), `Client` model, `UserRole` enum refactor, `User` helpers. Includes backfill + seeder for default client. `php -l` clean.
3. **`Sprint 2: scope admin queries by client`** — update `Admin/PositionController`, `Admin/ApplicationController`, `Admin/InterviewController`, `Admin/CandidateController`, `StatsService`, `EnsureUserHasRole`, route middleware. No new views yet.
4. **`Sprint 2: Client CRUD (Super Admin)`** — `Admin/ClientController`, `ClientRequest`, routes, views (index/create/edit/show + logo upload).
5. **`Sprint 2: Admin user management + role-gated nav`** — `Admin/UserController` for creating HR admins, role-filtered sidebar, client switcher / filter param helper, HR admin client banner on dashboard.
6. **`Sprint 2: seeders, docs, final polish`** — `ClientSeeder`, updated `UserSeeder` + `PositionSeeder`, `CLAUDE.md` updates, any loose ends.

---

## File checklist

### Create
- [ ] `app/Models/Client.php`
- [ ] `app/Http/Controllers/Admin/ClientController.php`
- [ ] `app/Http/Controllers/Admin/UserController.php`
- [ ] `app/Http/Requests/Admin/ClientRequest.php`
- [ ] `app/Http/Requests/Admin/StoreAdminUserRequest.php`
- [ ] `app/Http/Requests/Admin/UpdateAdminUserRequest.php`
- [ ] `database/migrations/<ts>_create_clients_table.php`
- [ ] `database/migrations/<ts>_add_client_id_to_users_and_positions.php`
- [ ] `database/migrations/<ts>_change_users_role_to_string_and_rename_jae_staff.php`
- [ ] `database/seeders/ClientSeeder.php`
- [ ] `resources/views/admin/clients/index.blade.php`
- [ ] `resources/views/admin/clients/create.blade.php`
- [ ] `resources/views/admin/clients/edit.blade.php`
- [ ] `resources/views/admin/clients/show.blade.php`
- [ ] `resources/views/admin/admins/index.blade.php`
- [ ] `resources/views/admin/admins/create.blade.php`
- [ ] `resources/views/admin/admins/edit.blade.php`

### Modify
- [ ] `app/Enums/UserRole.php` — rename JAE_STAFF → HR_ADMIN, add SUPER_ADMIN
- [ ] `app/Models/User.php` — helpers, `client()` belongsTo
- [ ] `app/Models/Position.php` — `client()` belongsTo, add `client_id` to fillable
- [ ] `app/Http/Middleware/EnsureUserHasRole.php` — comma-separated roles
- [ ] `app/Http/Controllers/Auth/LoginController.php` — redirect logic for super vs hr admin (both go to admin dashboard; candidate goes to candidate dashboard)
- [ ] `app/Http/Controllers/Admin/DashboardController.php` — respect scope
- [ ] `app/Http/Controllers/Admin/CandidateController.php` — scope by client via applications
- [ ] `app/Http/Controllers/Admin/ApplicationController.php` — scope by position→client
- [ ] `app/Http/Controllers/Admin/InterviewController.php` — scope by application→position→client
- [ ] `app/Http/Controllers/Admin/PositionController.php` — scope by client, client selector for Super Admin on create
- [ ] `app/Services/StatsService.php` — accept optional `?Client` scope
- [ ] `app/Http/Requests/Admin/PositionRequest.php` — require `client_id`; validate access
- [ ] `routes/web.php` — switch middleware to `role:hr_admin,super_admin` for admin group; add Super-only group for Clients/Admins
- [ ] `database/seeders/UserSeeder.php` — new roles, assign clients
- [ ] `database/seeders/PositionSeeder.php` — assign client_id per position
- [ ] `database/seeders/DatabaseSeeder.php` — include ClientSeeder before UserSeeder (so FK exists)
- [ ] `resources/views/partials/sidebar.blade.php` — role-filter nav items, HR client name under user chip
- [ ] `resources/views/layouts/admin.blade.php` — Super Admin client switcher in topbar
- [ ] `resources/views/admin/dashboard.blade.php` — HR admin client banner, respect scope
- [ ] `resources/views/admin/positions/create.blade.php` — client dropdown for Super Admin
- [ ] `resources/views/admin/positions/edit.blade.php` — same
- [ ] `resources/views/admin/positions/index.blade.php` — show client column (Super Admin view)
- [ ] `resources/views/admin/applications/index.blade.php` — show client column (Super Admin view)
- [ ] `CLAUDE.md` — data-model + roles + scoping conventions

### Consider
- Possibly: `config/filesystems.php` — already covers public disk; client logos go to `storage/app/public/client-logos/`, just document
- `tests/` — no coverage yet per the project's current state; skip unless we want to start

---

## Session notes (update as we go so context survives compaction)

- [ ] ChClient model
- [ ] Schema migration
- [ ] Role refactor complete
- [ ] Query scoping complete
- [ ] Client CRUD live
- [ ] Admin user CRUD live
- [ ] Sidebar + switcher live
- [ ] Seeders pass `migrate:fresh --seed`
- [ ] CLAUDE.md updated
- [ ] All commits pushed

---

## If you are a fresh Claude session resuming mid-sprint

1. Run `git log --oneline -10` to see how far we got.
2. Check the "Session notes" section above for live status ticks.
3. The most recent commit message always starts with `Sprint 2:`.
4. If a migration or seeder fails, rollback is `php artisan migrate:rollback --step=N` where N is the number of Sprint 2 migrations shipped.
5. Test accounts after full seed:
   - `admin@seekrecruit.com` — Super Admin
   - `maria@seekrecruit.com` — HR Admin (JAE Tijuana)
   - `jorge@acme.com` — HR Admin (Acme Engineering)
   - `elena@tjelectronics.com` — HR Admin (Tijuana Electronics)
   - `juan@example.com` and other candidates — unchanged
   All passwords: `password`
