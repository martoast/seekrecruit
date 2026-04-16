# Seek & Recruit — Product Brief vs. Current Implementation

Source: `SR_Brief_Desarrollador.docx` (M.E.J. Group, April 2026). Spanish brief translated below for consistency with the rest of the codebase; checkmarks show where the current monolith already satisfies the requirement, 🟡 where it's partial, ❌ where it's missing.

---

## 1. What Seek & Recruit Is

Web recruitment and talent-development platform for companies in Tijuana, B.C. Connects candidates with openings, manages the full selection process from application to hiring, and — after hiring — provides an educational onboarding and professional-development module.

Not a generic job board — it's a recruitment operating system built for the manufacturing and engineering industry in the Tijuana–Ensenada region.

### Users

| User type | Access | Main function | Current status |
|---|---|---|---|
| Candidate | Public site | Register, build profile, apply to openings, track their own process | ✅ |
| HR Admin / Director | Admin panel | Manage openings, review candidates, move pipeline, schedule interviews | 🟡 — role exists (`jae_staff`), but no concept of **per-client** admin scoping |
| Super Admin (S&R) | Full admin panel | Full platform control, configuration, client management | ❌ — no separate tier; `jae_staff` has everything |

---

## 2. Public Site — Candidate View

### 2.1 Landing page (`/`)
- [x] Hero with headline, subhead, "Start Your Journey" + "View Open Positions" CTAs
- [x] Example position card with success-rate / avg-response metrics
- [x] Trusted-by professionals counter
- [x] Navigation: S&R logo, Positions link, Login/Register buttons

### 2.2 Open Positions (`/positions`)
- [x] Grid of position cards — only those in "Open" status
- [x] Each card: title, company, location, post date, description excerpt
- [x] "Open" badge on each card
- [x] "View Details" CTA per card

### 2.3 Position Detail (`/positions/{id}`)
- [x] Cover image
- [x] Title, company, location
- [x] Full description + requirements
- [x] "Apply" button — routes through login/register if unauthenticated
- ❌ **Salary range** — not in model or view
- ❌ **Modality** (Remote / Hybrid / On-site) — not in model or view

### 2.4 Candidate Registration (`/register`)
- [x] Full name, email, gender, password, confirm password
- [x] Terms & Conditions checkbox
- [x] "Create Account" button
- [x] Link to Login

### 2.5 Candidate Login (`/login`)
- [x] Email + password
- [x] "Remember me" checkbox + "Forgot password" link
- [x] "Sign In" button
- [x] "Register now" link

---

## 3. Admin Panel — HR Admin View

Fixed sidebar: Dashboard / Candidates / Applications / Interviews / Positions. User avatar + logout at the bottom.

✅ Sidebar implemented exactly as specified (`resources/views/partials/sidebar.blade.php`).

### 3.1 Dashboard (`/admin` — brief says `/admin/dashboard`)
- [x] 4 KPI cards: Total Candidates, Total Applications, Interviews This Week, Hired Candidates
- [x] Application Pipeline horizontal bar chart by status
- [x] Top Universities ranking table
- [x] "+ New Position" button top-right
- 🟡 URL is `/admin` (aliased), not `/admin/dashboard` — cosmetic difference

### 3.2 Candidates (`/admin/candidates`)
- ❌ **4 KPIs at top: Total / With CV / Universities represented / New this week**
- [x] Table columns: Candidate (name + email), Education (university + degree), Location, CV Status, Actions
- [x] "Missing" badge when no CV
- 🟡 **Search is server-side form submit, not real-time** (minor UX gap)
- [x] "View Profile" button per row

#### Candidate Profile (`/admin/candidates/{id}`)
- [x] Personal data, email, university, degree, location, skills tags
- [x] CV status with download/view
- [x] Applications history for the candidate

### 3.3 Applications (`/admin/applications`)
- ❌ **4 KPIs: Total / Pending / In Interview / Hired**
- [x] Search bar
- [x] Table columns: Candidate, Position, Status (badge), Applied date, Actions
- ❌ **"Last updated" column**
- [x] "Review" button per row

#### Application Detail (`/admin/applications/{id}`)
- [x] Candidate name, email, applied position
- [x] 7-stage status stepper (clickable): Registered → Preselected → Interview → Evaluation → Finalist → Hired → Discarded
- [x] Candidate summary: university, degree, location, CV status, skills
- [x] "View Full Profile" link
- [x] Timeline: Application Submitted + Last Updated
- 🟡 Quick Actions: Email Candidate ✅, **Schedule Interview** — we link to `/admin/interviews` instead of opening an inline modal (brief implies inline modal on the application page)
- [x] Interviews section linked to this application

### 3.4 Interviews (`/admin/interviews`)
- [x] 4 KPIs: Total / Upcoming / Technical / Final Round
- [x] Filters: Interview Type (All/HR/Technical/Final Round) + From–To date range
- [x] Table columns: Candidate, Position, Type, Schedule, Location, Status, Actions (edit/delete)
- [x] "+ Schedule Interview" opens modal

#### Modal: Schedule Interview
- [x] Application dropdown (candidate + position)
- [x] Date & Time picker
- [x] Interview Type dropdown (HR / Technical / Final Round)
- [x] Location field
- [x] Internal Notes textarea
- [x] Cancel + Schedule Interview buttons

### 3.5 Positions (`/admin/positions`)
- 🟡 **List only** — brief says "grid or list". Acceptable variation, but worth noting.
- 🟡 Status shown as Active/Inactive. Brief says **Open / Closed / Draft** (three states). Current model only has `is_active` boolean.
- 🟡 Options per position: edit ✅, delete ✅, change status — only via edit form, no quick toggle
- [x] Create new position button

#### Create / Edit Position (`/admin/positions/create` and `/admin/positions/{id}/edit`)
- [x] Position Title
- [x] Location
- [x] Company / Organization Name (optional)
- [x] Description
- [x] Requirements
- ❌ **Salary range**
- ❌ **Modality (Full-time / Part-time / Internship / Contract)**
- [x] Note about adding cover image + logo only after creation

---

## 4. Educational Module — BGM System (PHASE 2)

Activated when an application hits "Hired". Differentiates S&R from a conventional ATS.

### 4.1 Course Platform
- ❌ Course catalog by role and industry
- ❌ Modules with video / text / assessments
- ❌ Per-candidate progress tracking
- ❌ Digital certificates and badges

### 4.2 Post-Hire Onboarding Flow

| Stage | Name | Content | Status |
|---|---|---|---|
| 1 | Orientation | Company culture, safety, role description | ❌ |
| 2 | Skill Building | Technical training + soft skills | ❌ |
| 3 | Assessment | Progress evaluation + individual development plan | ❌ |
| 4 | Growth Path | Performance review + long-term growth projection | ❌ |

### 4.3 Manager Dashboard
- ❌ Per-employee learning progress (real-time)
- ❌ Badges and certifications earned
- ❌ Alerts for incomplete courses / pending assessments

### 4.4 AI Learning Paths
- ❌ AI-generated learning paths based on role, industry, skill gaps
- ❌ Personalized content that evolves with progress

**None of Phase 2 exists yet. This is a greenfield module.**

---

## 5. Analytics — Talent Dashboard

Business-intelligence panel for the S&R admin and each client company.

| Metric | Description | Status |
|---|---|---|
| Hiring Probability Score | 0–100% predictive score of being hired | ❌ (needs ML model) |
| Compatibility % | Match between candidate profile and position requirements | ❌ (heuristic possible) |
| Conversion Rate | % of applications that reach "Hired" | ❌ (trivial calc) |
| Response Rate | % of contacted candidates who responded | ❌ (needs outreach tracking) |
| Time-to-Fill | Avg days from posting to "Hired" | ❌ (trivial calc) |
| Hiring Rate | Hired vs. evaluated per client | ❌ (trivial calc) |
| Satisfaction Levels | Post-hire satisfaction from candidate + HR | ❌ (needs survey flow) |
| Performance Impact Score | Correlation between BGM progress and manager-reported performance | ❌ (needs BGM module + reviews) |
| Retention Forecast | 6- and 12-month tenure prediction | ❌ (needs ML + historical data) |

---

## 6. Value Add (physical / offline)

Out of scope for the platform — handled by S&R operations team:
- University / tech-school presentations
- Job-fair participation
- External recruiting campaigns (LinkedIn, OCC, Indeed)
- Technical + soft-skills workshops
- Ongoing system maintenance

---

# Gap Summary — What We Need to Build

Grouped by effort and dependency order:

### Tier A — Quick fixes in existing pages (hours, not days)
1. Add 4 KPI cards to `admin/candidates/index` — Total, With CV, Universities, New This Week
2. Add 4 KPI cards to `admin/applications/index` — Total, Pending, In Interview, Hired
3. Add "Last Updated" column to applications table
4. Convert the Application detail "Schedule Interview" quick action into an inline modal (reuse the one from interviews/index)
5. Optional: rename `/admin` route to `/admin/dashboard` for brief parity

### Tier B — Position model expansion (half day)
1. Migration: add `salary_min`, `salary_max`, `salary_currency`, `employment_type` (enum: full_time / part_time / internship / contract), `modality` (enum: on_site / remote / hybrid), `status` (enum: open / closed / draft — replaces `is_active`)
2. Update `PositionRequest` validation
3. Update create + edit forms with new fields
4. Update public `/positions` and `/positions/{id}` views to show salary + modality
5. Public positions list filters by `status = 'open'` instead of `is_active = true`
6. Quick status toggle on positions index (no full form submit)

### Tier C — Role model expansion (one day)
1. Split `jae_staff` into two tiers: add `UserRole::SUPER_ADMIN`
2. Introduce a `Client` model (tenant) — `hasMany` Users (admins), Positions, Applications
3. Add `client_id` to `Position` and scope admin queries per client
4. Super Admin sees everything; HR Admin sees only their client
5. Client switcher / client management pages for Super Admin

### Tier D — Analytics dashboard (1–2 weeks)
Build the **calculable** metrics first, then layer on ML later.

Phase D1 — trivial calculations:
- Conversion Rate (applications → hired %)
- Time-to-Fill (position create → application hired)
- Hiring Rate per client
- Compatibility % — start with a naive heuristic (skill overlap + degree match + location proximity)

Phase D2 — needs new data collection:
- Response Rate — requires outreach log (when admin contacted candidate, whether candidate responded)
- Satisfaction Levels — requires post-hire survey flow (candidate + HR)

Phase D3 — needs ML + historical data:
- Hiring Probability Score — can start with logistic regression on features (skill match, university tier, application recency)
- Retention Forecast — needs BGM progress + tenure signals; 6-month model after we have data
- Performance Impact Score — depends on BGM + manager review flow

### Tier E — BGM Module (Phase 2 — weeks to months)
Build order:

E1. Course data model
- `Course`, `Module`, `Lesson` (video/text/assessment), `CourseEnrollment`, `LessonProgress`, `Certificate`
- Admin CRUD for courses + lessons
- Trigger: when `Application::status` transitions to `HIRED`, auto-enroll in the assigned onboarding course

E2. Candidate (now "employee") learning UI
- Course player page (video embed, text content, quizzes)
- Progress tracker per module
- Badge + certificate display

E3. Onboarding flow stages
- Model the 4 stages (Orientation → Skill Building → Assessment → Growth Path) as a configurable template applied to new hires
- Per-hire progress view

E4. Manager dashboard
- Per-client list of hired employees with BGM progress
- Alerts for overdue assessments / incomplete modules
- Export / report views

E5. AI Learning Paths
- Integration with Claude API (this codebase will pair well — see `@claude-api` skill)
- Prompt: given employee role + skills + gaps, generate personalized module sequence
- Cache recommendations, allow manager override

### Tier F — Polish (as we go)
- Real-time search on candidates (debounced fetch → HTML fragment swap, or drop in Livewire if we decide to)
- Soft-delete UI (trashed positions, restore action) — model already supports it
- Wire the four unused `Notifications/*` classes: dispatch from Application status update, Interview store, new application, referral invite

---

# Suggested Roadmap

| Sprint | Focus | Deliverable |
|---|---|---|
| 1 | Tier A + Tier B | Candidates + Applications KPIs, Positions model (salary/modality/status tri-state) fully in UI |
| 2 | Tier C | Multi-tenant (Client model, Super Admin role, scoped admin views) |
| 3 | Tier D1 | Analytics dashboard with trivial + heuristic metrics |
| 4 | Tier D2 | Outreach logging + post-hire survey flow |
| 5–6 | Tier E1 + E2 | BGM course catalog + learner UI |
| 7 | Tier E3 + E4 | Onboarding stages + Manager dashboard |
| 8 | Tier E5 | AI Learning Paths (Claude API) |
| 9 | Tier D3 + F | ML-based scores + polish pass |

---

# Working Agreement

- **One task at a time.** When we start a tier, make a checklist in `tasks/` (like `tasks/tier-a.md`) and tick items as we go.
- **Phase 2 (BGM) is its own epic.** Don't mix BGM commits with ATS fixes — separate PRs.
- **Don't let ML metrics block delivery.** Ship the simple-calc versions first so the dashboard is usable; ML is an upgrade path.
- **Keep CLAUDE.md current.** When we add models (Client, Course, etc.) or new conventions, update the "Data model" and "Conventions" sections in `CLAUDE.md`.
