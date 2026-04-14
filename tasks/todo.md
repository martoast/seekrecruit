# Seek & Recruit — Laravel Monolith Refactor

Consolidating the Nuxt 3 SPA into the Laravel app so everything lives in one repo. Converting the API into a traditional server-rendered Laravel + Blade app with Tailwind.

---

## Architecture decisions

- **Server-rendered Blade views** — no SPA, no AJAX by default. Forms POST with CSRF and redirect back with flash messages. Simpler than porting the Pinia + $fetch dance.
- **Session auth via `web` guard** — ditch Sanctum bearer tokens. Use `Auth::login()` + session cookies. Fortify features are disabled (`'features' => []`) to avoid route collisions; auth controllers are rewritten as simple web controllers.
- **Route stack moves from `api.php` → `web.php`** — `bootstrap/app.php` is updated to register web routes. All controllers now return Views (for GET) or `redirect()->back()` (for mutations) instead of JSON.
- **Keep existing controller namespaces / FormRequests / Resources models** — FormRequests still validate; Resources aren't used for Blade (views read models directly). Existing middleware `role:candidate` / `role:jae_staff` still works.
- **Toasts = session flashes** — `session()->flash('success' | 'error' | 'info', '...')` rendered by a single partial in every layout.
- **Blade components** under `resources/views/components/ui/*` for the UI kit (button, input, card, badge, alert, modal, pagination, empty-state). Layout partials under `resources/views/partials/*`.
- **CVs stay on private disk with signed URLs**; profile/position/company images stay on public disk (needs `php artisan storage:link`).
- **Fixes applied in passing**:
  - Password reset URL now points to `/reset-password?token=...&email=...` (Nuxt URL was `/password-reset/{token}` which 404'd anyway)
  - Dashboard "rejected" typo becomes `discarded`
  - Empty policy stubs removed (not registered anywhere)
  - Unused dead files removed: top-level `ProfileController`, `AdminMiddleware` alias
  - CV upload limit unified at 5 MB (was advertised as 10 MB in Nuxt, server was 5 MB)

---

## Build plan

### Phase 1 — Foundation ✅
- [x] Inventory Laravel frontend setup (Vite + Tailwind 4 already there)
- [x] Extend `resources/css/app.css` with the Nuxt theme (primary, dark, accent, etc.)
- [x] Update `bootstrap/app.php` to register web routes
- [x] Clear out `routes/web.php` and `routes/api.php` for new route set
- [x] Disable Fortify features (`config/fortify.php` features = [])
- [x] Strip Sanctum from `User` model (no more bearer tokens)
- [x] Add `auth.web` + custom `role` web middleware aliases

### Phase 2 — Layouts & UI components ✅
- [x] Base `app.blade.php` layout (loads Vite, head, toast partial, slot)
- [x] Public layout partial: navbar + footer
- [x] Candidate layout partial: navbar (no footer)
- [x] Admin layout partial: sidebar + top bar
- [x] Mobile menu partial
- [x] Toast partial (reads session flashes)
- [x] `<x-ui.button>` — variant/size/loading/type
- [x] `<x-ui.input>` — label/error/required/type
- [x] `<x-ui.textarea>`
- [x] `<x-ui.select>` — options slot via attribute
- [x] `<x-ui.card>` — header/footer/default slots
- [x] `<x-ui.badge>` — variant/size
- [x] `<x-ui.alert>` — type
- [x] `<x-ui.empty-state>` — title/description/action slot
- [x] `<x-ui.pagination>` — paginator from Eloquent
- [x] `<x-ui.status-badge>` — application/referral status mapping
- [x] `<x-ui.spinner>`

### Phase 3 — Auth ✅
- [x] Rewrite `LoginController` — session auth via `Auth::attempt`, returns views
- [x] Rewrite `RegisterController` — creates user + candidate profile, logs in
- [x] Rewrite `LogoutController` — session invalidate + redirect
- [x] Rewrite `PasswordResetController` — uses `Password` broker + views
- [x] `MeController` removed (no longer needed — Auth::user() in blade)
- [x] Login page view
- [x] Register page view
- [x] Forgot password page view
- [x] Reset password page view

### Phase 4 — Public pages ✅
- [x] Landing page (`/`) — hero, stats, CTA sections (port of pages/index.vue)
- [x] Positions list (`/positions`)
- [x] Position detail (`/positions/{position}`)
- [x] Public `PositionController` returns views

### Phase 5 — Candidate area ✅
- [x] Candidate dashboard (`/candidate`)
- [x] Profile page (`/candidate/profile`) with update form
- [x] Profile image upload + delete
- [x] CV upload + delete + download
- [x] Applications list (`/candidate/applications`)
- [x] Application detail (`/candidate/applications/{application}`)
- [x] Referrals page (`/candidate/referrals`)

### Phase 6 — Admin area ✅
- [x] Admin dashboard (`/admin`) — stats grid, pipeline, recent applications
- [x] Candidates list (`/admin/candidates`) with pagination
- [x] Candidate detail (`/admin/candidates/{candidate}`) with CV download
- [x] Applications list (`/admin/applications`)
- [x] Application detail (`/admin/applications/{application}`) with status update, notes, interview scheduling
- [x] Interviews list (`/admin/interviews`) with create/edit/delete
- [x] Positions list (`/admin/positions`)
- [x] Create position page (`/admin/positions/create`)
- [x] Edit position page (`/admin/positions/{position}/edit`) with image/logo uploads

### Phase 7 — Cleanup ✅
- [x] Delete unused API Resource classes (`app/Http/Resources/*`)
- [x] Delete empty Policy stubs (`app/Policies/*`)
- [x] Delete top-level `ProfileController` dead code
- [x] Delete `AdminMiddleware` + `JsonResponse` middleware (unused aliases)
- [x] Delete `MeController` (replaced by Blade `auth()->user()`)
- [x] Remove `HasApiTokens` + `MustVerifyEmail` from User model
- [x] Uninstall Fortify: remove from composer, delete `FortifyServiceProvider`, `app/Actions/Fortify/`, `config/fortify.php`
- [x] Uninstall Sanctum: remove from composer, delete `config/sanctum.php`
- [x] Delete `2025_12_20_*` migrations (two-factor columns, personal_access_tokens)
- [x] Update `.env.example` — drop `SANCTUM_STATEFUL_DOMAINS`, `SPA_URL`, `APP_FRONTEND_URL`, flip `SESSION_DRIVER` to `database`, `SESSION_DOMAIN` to `null`
- [x] Update `AppServiceProvider::ResetPassword::createUrlUsing` to point at the new in-app `password.reset` route
- [x] Static grep: no lingering references to `HasApiTokens`, `FortifyServiceProvider`, `Sanctum`, `api.php`, `AdminMiddleware`, `Http\Resources\`, `Policies\`

---

## Notes

- Pinned stack: Laravel 12, PHP 8.2+, Tailwind 4 (CSS-based config), Blade components, Vite 7.
- **The user still needs to run** after pulling:
  - `composer install`
  - `npm install`
  - `php artisan migrate`
  - `php artisan storage:link`
  - `npm run build` (prod) or `npm run dev` (local)
  - `php artisan db:seed` (optional, test accounts)
- Test accounts (unchanged):
  - Admin: `admin@seekrecruit.com` / `password`
  - Candidate: `juan@example.com` / `password`
