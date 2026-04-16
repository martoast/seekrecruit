# Seek & Recruit — Laravel Monolith

Private recruitment platform for JAE Tijuana and its client companies in Baja California. Candidates build profiles and apply to positions. Two admin tiers manage the pipeline: **HR Admins** are scoped to a single client company; **Super Admins** (S&R operators) see everything and manage the client roster itself.

This used to be a Laravel API with a separate Nuxt 3 SPA. It is now a **single server-rendered Laravel + Blade app**. The Nuxt repo is archived — don't port patterns back from it without thinking.

## Stack

- Laravel 12 (PHP 8.2+), session auth via the `web` guard
- MySQL 8 (Sail/Docker compose shipped in `compose.yaml`)
- Blade views + Blade Components for the UI kit
- Tailwind CSS 4 (CSS-based config — all theme tokens live in `resources/css/app.css`, there is no `tailwind.config.js`)
- Vite 7 + `laravel-vite-plugin` + `@tailwindcss/vite`
- Tiny vanilla JS in `resources/js/app.js` for mobile menu + toast dismissal — **no Alpine, no Livewire, no Inertia**. If a page needs interactivity, inline a `<script>` block at the bottom of the Blade view.

## What makes this app tick

**Routing.** Everything is in `routes/web.php` under named routes. `routes/api.php` does not exist. Four middleware-scoped groups:

- `guest` — login/register/forgot/reset
- `auth` + `role:candidate` — `/candidate/*`, named `candidate.*`
- `auth` + `role:hr_admin,super_admin` — `/admin/*`, named `admin.*` (everything both admin tiers can touch)
- `auth` + `role:super_admin` — `/admin/clients/*` + `/admin/admins/*` (Super Admin only: client management + HR admin CRUD)

The `role` alias points at `App\Http\Middleware\EnsureUserHasRole`, which takes a **comma-separated list of roles** (variadic under the hood) and redirects (not aborts) if the user's role doesn't match any. Mismatches go to `candidate.dashboard` or `admin.dashboard` depending on the user's current role. Registered in `bootstrap/app.php`.

**Auth.** Session-based only. No Sanctum, no Fortify, no tokens. `LoginController` calls `Auth::attempt`; `RegisterController` creates a `User` + `CandidateProfile` in a transaction and `Auth::login`s them; `LogoutController` invalidates the session. `PasswordResetController` uses Laravel's built-in `Password` broker. The password reset URL is built in `AppServiceProvider` to point at the in-app `password.reset` route.

After login, users are redirected based on role: any admin (`hr_admin` or `super_admin`) → `admin.dashboard`; candidates → `candidate.dashboard`. `guest` middleware does the inverse — already-logged-in users never see the auth pages.

**Controllers.** One controller per feature, grouped by area:

```
app/Http/Controllers/
├── HomeController.php          # landing page
├── PositionController.php      # public positions list + detail
├── Auth/
│   ├── LoginController.php
│   ├── RegisterController.php
│   ├── LogoutController.php    # __invoke
│   └── PasswordResetController.php
├── Candidate/
│   ├── DashboardController.php
│   ├── ProfileController.php   # edit/update + CV + profile image
│   ├── ApplicationController.php
│   └── ReferralController.php
└── Admin/
    ├── Concerns/
    │   └── ScopesToClient.php   # trait with activeClientId/userOwnsClient/activeClient helpers
    ├── DashboardController.php
    ├── CandidateController.php
    ├── ApplicationController.php
    ├── InterviewController.php
    ├── PositionController.php
    ├── ClientController.php     # Super Admin only — Client CRUD
    └── UserController.php       # Super Admin only — HR Admin CRUD
```

Controllers return Views for GETs and `redirect()->back()->with('success', '...')` (or `redirect()->route(...)`) for mutations. **Never return JSON from a controller** — this app has no API surface. If you find yourself wanting to, you're probably trying to do AJAX; do a full form POST + redirect + flash instead.

**FormRequests** handle all validation in `app/Http/Requests/{Auth,Candidate,Admin}/`. The interesting one is `Candidate/UpdateProfileRequest` — it uses `prepareForValidation` to parse the comma-separated `skills` string from the profile page's tag UI into an array before the validator runs. Follow the same pattern if you need to coerce input shape.

## Blade layouts

Four layouts in `resources/views/layouts/`:

- `app.blade.php` — public pages. Navbar + footer + mobile menu.
- `candidate.blade.php` — candidate area. Navbar only (no footer).
- `admin.blade.php` — admin area. Fixed sidebar on desktop, mobile header + drawer on mobile.
- `auth.blade.php` — centered card for login/register/forgot/reset. No nav.

Pages `@extends` the appropriate layout and define `@section('title', ...)` and `@section('content', ...)`. Auth pages also define `@section('heading', ...)` and `@section('subheading', ...)`.

Shared partials live in `resources/views/partials/`:

- `navbar.blade.php` — public + candidate nav, dynamic based on `auth()->user()->isAdmin()` / `isCandidate()`
- `sidebar.blade.php` — admin nav, data-driven from an array of `['label', 'route', 'active', 'icon']`. Add items there.
- `mobile-menu.blade.php` — drawer, toggled by `[data-mobile-menu-toggle]` buttons from `app.js`
- `footer.blade.php`
- `toast.blade.php` — reads `session('success'|'error'|'warning'|'info'|'status')` and renders a stack of toasts, top-right. Auto-dismissed client-side after 5s.

## Blade UI kit

Every form control and primitive is a Blade Component under `resources/views/components/ui/`. Use these instead of hand-writing Tailwind — they own error display, CSRF-friendly names, and styling consistency.

```blade
<x-ui.button variant="primary|secondary|danger|ghost" size="sm|md|lg" type="submit" loading>Label</x-ui.button>
<x-ui.input  label="..." name="email" type="email" required />
<x-ui.textarea label="..." name="bio" :rows="4" />
<x-ui.select   label="..." name="status" :options="['a' => 'Alpha', 'b' => 'Beta']" placeholder="Choose..." />
<x-ui.card padding="lg">...<x-slot:header>...</x-slot:header><x-slot:footer>...</x-slot:footer></x-ui.card>
<x-ui.badge variant="success|warning|danger|info|default" size="sm|md">Label</x-ui.badge>
<x-ui.alert type="success|error|warning|info">Message</x-ui.alert>
<x-ui.empty-state title="..." description="..."><x-slot:action>...</x-slot:action></x-ui.empty-state>
<x-ui.status-badge :status="$application->status" type="application|referral|interview" size="sm|md" />
<x-ui.spinner size="sm|md|lg" />
```

**Important convention:** `<x-ui.input>`, `<x-ui.textarea>`, and `<x-ui.select>` all auto-bind to `$errors` when you give them a `name` attribute. You don't need to pass `:error="$errors->first('name')"` — just use `name="email"` and the error message shows up automatically under the field. They also auto-populate from `old()` on validation failures. Pass `:value="..."` explicitly to override (e.g., on edit forms).

`<x-ui.status-badge>` takes either a `BackedEnum` instance or a string. It handles the three enums — `ApplicationStatus`, `ReferralStatus`, `InterviewType` — via the `type` prop.

## Forms and CSRF

All mutating forms are full POSTs, CSRF-protected:

```blade
<form method="POST" action="{{ route('candidate.profile.update') }}">
    @csrf
    @method('PUT')  {{-- for PUT/PATCH/DELETE --}}
    ...
</form>
```

File uploads need `enctype="multipart/form-data"`. The profile and position edit pages show the pattern — inline `<label>` wrapping a hidden `<input type="file">` with `onchange="this.form.submit()"` for single-field image uploads.

For flash feedback, controllers return `back()->with('success', 'Message')` or `->with('error', 'Message')` — the toast partial picks it up automatically.

## Data model

- **`Client`** — tenant model (a client company). `hasMany` users (HR admins), `hasMany` positions. Soft-deleted; deleting a client cascades to its positions via the `booted()` hook but leaves applications intact as historical record. Fields: `name`, `slug` (unique), `industry`, `logo`, `is_active`.
- **`User`** — role column is now a plain string (no enum constraint in the DB), cast to `UserRole` enum at the model layer: `candidate` / `hr_admin` / `super_admin`. `client_id` is nullable — set for HR admins, null for candidates and super admins.
  - Helpers: `isCandidate()`, `isHrAdmin()`, `isSuperAdmin()`, `isAdmin()` (broad — either admin tier).
  - `hasOne CandidateProfile`, `belongsTo Client`, etc.
- **`Position`** `belongsTo Client` (required). `hasMany Application`. Soft-deleted.
- `Referral` is standalone; references `User` as referrer / referred.
- Enums in `app/Enums/`: `UserRole`, `ApplicationStatus`, `InterviewType`, `Gender`, `ReferralStatus`, `PositionStatus`, `EmploymentType`, `Modality`.
- `CandidateProfile->skills` is a JSON column cast to `array`.
- `CandidateProfile->profile_image_url`, `Position->image_url`, `Position->company_logo_url`, `Position->salary_range`, `Client->logo_url` are accessor attributes. The image URLs prepend `asset('storage/...')` — they need the storage symlink to work.
- `Position` fields: `client_id` (required), `title`, `description`, `requirements`, `location`, `company_name`, `salary_min` + `salary_max` + `salary_currency` (nullable), `employment_type` (enum: full_time/part_time/internship/contract), `modality` (enum: on_site/remote/hybrid), `status` (enum: open/closed/draft). **There is no `is_active` — it was replaced by `status` in April 2026.** Public pages filter by `status = 'open'`.

### Client scoping (Tier C, April 2026)

The `ScopesToClient` trait in `app/Http/Controllers/Admin/Concerns/` is the single source of truth for how admin controllers filter by client:

```php
$clientId = $this->activeClientId($user, $request->integer('client_id') ?: null);
// HR Admin  → always $user->client_id (the URL filter is ignored)
// Super Admin → $request->integer('client_id') or null (show-everything)

$this->userOwnsClient($user, $clientId)   // ACL check for show/update/destroy
$this->activeClient($user, $requestedId)  // returns the Client model for banner rendering
```

**Every admin controller that touches `Position`, `Application`, `Interview`, `CandidateProfile`, or `StatsService` uses this trait** to scope list queries and authorize single-record operations. When you add a new admin controller, add the trait + the scoping helper calls; don't reinvent the pattern.

For candidates: HR admins only see the candidates who have applied to their client (`whereHas('applications.position', ...)`) and only those applications are shown on the detail page — cross-client applications are hidden even if a candidate applied elsewhere too.

`StatsService::getStats(?int $clientId = null)` follows the same contract: null = platform-wide, integer = scope to that client.

## File storage

Two disks, two strategies:

- **Private disk** (`storage/app/private`) — CVs only. Uploaded via `CvStorageService::upload`, served to the browser via `Storage::disk('private')->temporaryUrl(...)` from `CvStorageService::getSignedUrl` (30-minute expiry). The candidate profile page and admin candidate detail page both redirect to the signed URL on "Download CV".
- **Public disk** (`storage/app/public`) — profile images, position cover images, company logos. Needs `php artisan storage:link` once. The model accessors assume the symlink exists.

If you add a new upload type, decide which disk before writing the controller. CVs are private because they're personal documents; images are public because they're shown on public position detail pages.

## StatsService

`App\Services\StatsService` builds the admin dashboard payload: total candidates, total applications, applications grouped by status (all statuses guaranteed present, zero-filled), interviews this week, 5 most recent applications (eager-loaded with candidate + position), top 10 universities by candidate count. It returns a plain array — the dashboard view consumes `$stats['applications_by_status']` etc. directly.

## Running locally

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed      # creates test accounts
php artisan storage:link              # required for image uploads
npm run dev                           # one terminal — Vite HMR
php artisan serve                     # another terminal
```

Seed accounts (all password `password`):

- **Super Admin** (platform-wide): `admin@seekrecruit.com`
- **HR Admins** (one per seed client):
  - `maria@seekrecruit.com` → JAE Tijuana
  - `jorge@acme.com` → Acme Engineering
  - `elena@tjelectronics.com` → Tijuana Electronics
- **Candidates**: `juan@example.com`, `ana@example.com`, `carlos@example.com`, `sofia@example.com`, `diego@example.com`, `laura@example.com`, `roberto@example.com` + 15 more factory-generated candidates

📖 **For the full testing guide aimed at non-developers** (what each account can do, suggested smoke-test flow, what's visible on the public site), point the team at [`TESTING.md`](TESTING.md) in the repo root. That file is written for QA — this one is written for engineers.

## Conventions and gotchas

- **Named routes everywhere.** All route references in Blade use `route('admin.candidates.show', $candidate)`. Grep for hardcoded paths like `/admin/candidates` — they're a smell.
- **Sidebar is data-driven.** Don't hand-roll `<a>` tags in `sidebar.blade.php` — edit the `$navItems` array at the top of that file.
- **Navbar is route-sensitive.** It reads `request()->routeIs('candidate.profile.*')` to highlight the active link. Name routes accordingly.
- **Toast messages are opinionated keys.** Use `success`, `error`, `warning`, `info`, or `status`. Anything else won't render.
- **Don't reintroduce Sanctum or Fortify.** They were ripped out intentionally (they were causing route collisions and unused overhead). If you need API auth later, build it deliberately under a separate guard.
- **Don't return JSON.** See above. No API surface.
- **Don't introduce a global scope on Position/Application/etc.** to enforce client tenancy. It's tempting but brittle — you'll forget to override it exactly when you need unfiltered access (e.g. Super Admin views). Keep scoping explicit via `ScopesToClient`.
- **Super Admin client filter is `?client_id=X` on the URL, not a session variable.** A URL is always the source of truth for what's being viewed. If you find yourself wanting session persistence, document the trade-off before adding it.
- **HR Admin `client_id` is enforced server-side** in `PositionRequest::prepareForValidation` and in controller `store`/`update` methods — never trust a submitted `client_id` from an HR admin form.
- **`CandidateProfile` columns `university`, `degree`, `location`, `gender` are NOT NULL** in the migration. `RegisterController` initializes them to empty strings on signup so the NOT NULL doesn't trip. `UpdateProfileRequest` enforces them as `required` once the candidate edits their profile.
- **CORS middleware is still there** (`config/cors.php`) but there are no cross-origin callers; it's just default Laravel. Ignore unless you reintroduce an API.
- **The `Notifications/*` classes are not wired up.** `ApplicationReceived`, `ApplicationStatusChanged`, `InterviewScheduled`, `ReferralInvite` exist as `ShouldQueue` classes but nothing dispatches them. If you wire email, hook them into `Admin/ApplicationController::updateStatus`, `Admin/InterviewController::store`, `Candidate/ApplicationController::store`, and `Candidate/ReferralController::store` respectively.

## Adding a new page

1. Pick the layout (`app`, `candidate`, `admin`, `auth`).
2. Create the Blade file under `resources/views/<area>/<name>.blade.php`.
3. Add a controller method that returns `view('<area>.<name>', compact(...))`.
4. Add a named route in `routes/web.php` inside the right middleware group.
5. If it's in the candidate/admin sidebar or navbar, add the link to `partials/sidebar.blade.php` (admin) or `partials/navbar.blade.php` + `partials/mobile-menu.blade.php` (candidate/public).
6. Use `<x-ui.*>` components for anything form-shaped. Don't hand-roll inputs.

## Directory map

```
app/
├── Enums/                        # UserRole, ApplicationStatus, etc.
├── Http/
│   ├── Controllers/              # see Controllers section above
│   ├── Middleware/EnsureUserHasRole.php
│   └── Requests/                 # FormRequests for Auth/Candidate/Admin
├── Models/                       # User, CandidateProfile, Position, Application, Interview, ApplicationNote, Referral
├── Notifications/                # NOT WIRED UP — see gotchas
├── Providers/AppServiceProvider.php
└── Services/                     # CvStorageService, StatsService

resources/
├── css/app.css                   # Tailwind theme + keyframes — edit this for color/font changes
├── js/app.js                     # mobile menu + toast dismissal
└── views/
    ├── admin/                    # dashboard + candidates/ + applications/ + interviews/ + positions/
    ├── candidate/                # dashboard + profile + applications/ + referrals
    ├── auth/                     # login/register/forgot/reset
    ├── positions/                # public list + detail + _card partial
    ├── components/ui/            # Blade UI kit (button, input, card, badge, ...)
    ├── layouts/                  # app, candidate, admin, auth
    ├── partials/                 # navbar, sidebar, footer, mobile-menu, toast
    └── home.blade.php            # landing

routes/web.php                    # everything lives here
bootstrap/app.php                 # web router + role middleware alias
```
