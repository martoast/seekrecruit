# Sprint 1 — Tier A fixes + Tier B Position model expansion ✅

Closing the small gaps vs. the brief, then expanding the Position domain with salary, modality, employment type, and tri-state status.

## Tier A — existing-page fixes ✅

- [x] Admin Candidates list: added 4 KPI cards (Total / With CV / Universities / New This Week) — computed in `CandidateController@index`
- [x] Admin Applications list: added 4 KPI cards (Total / Pending / In Interview / Hired) — computed via `DB::raw` group-by in `ApplicationController@index`
- [x] Admin Applications list: added "Last Updated" column (both mobile + desktop views)
- [x] Admin Application detail: "Schedule Interview" quick actions now open an inline modal on the same page; submits directly to `admin.interviews.store` with `application_id` hidden

## Tier B — Position model expansion ✅

- [x] New enums: `PositionStatus` (open/closed/draft), `EmploymentType` (full_time/part_time/internship/contract), `Modality` (on_site/remote/hybrid) — each with a `label()` helper for display
- [x] Migration `2026_04_16_134046_expand_positions_with_salary_modality_and_status` — adds salary_min/max/currency + employment_type + modality + status, backfills status from is_active, drops is_active; reversible
- [x] `Position` model — fillable + casts updated, added `salary_range` accessor and `isOpen()` helper
- [x] `PositionRequest` — salary_max gte:salary_min, currency size:3, employment_type/modality/status Rule::in validation
- [x] Admin create form — three new selects (Employment Type / Modality / Status), salary min/max/currency row, removed the Active checkbox
- [x] Admin edit form — same new fields, pre-populated from the model
- [x] Admin positions list — swapped Active/Inactive stats for Open/Draft/Closed (4-card layout), 3-state status badge via a local `$statusBadge` closure, inline `<select>` for quick status toggle
- [x] Public `/positions` index — filters by `status = 'open'` (was `is_active = true`)
- [x] Public `/positions/{id}` detail — added modality + employment type + salary range chip row; hero badge + apply button gated on `isOpen()`
- [x] Public `positions/_card` partial — added modality + type + salary chips
- [x] Fixed position-status badge in admin application detail sidebar (was reading `is_active`)
- [x] `PositionSeeder` — updated with realistic salaries, modalities, and employment types; added Data Analyst as a Draft example
- [x] `CLAUDE.md` — data-model section updated with new Position fields + enum list

## Loose ends for next sprint

- Candidates list search is still form-submit. Real-time/debounced search belongs in a later polish pass (Tier F).
- Positions list is a table; brief says "grid or list". Table is fine for admins — not worth changing now.
- Nothing dispatches the `Notifications/*` classes yet — still on the backlog.
