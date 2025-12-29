# Seek & Recruit Network — Laravel API Development Plan

## Project Overview

Seek & Recruit is a private recruitment platform for JAE Tijuana to manage engineering talent from local universities. The API serves two main user types: **Candidates** (students/graduates looking for jobs) and **JAE Staff** (recruiters/HR who manage the hiring pipeline).

The core flow: candidates register, build their profile, upload a CV, and apply to positions. JAE staff can filter candidates, move them through a hiring pipeline, schedule interviews, and add internal notes.

---

## Tech Stack

- Laravel 11
- MySQL 8
- Laravel Sanctum for API authentication
- Laravel Storage for CV file uploads (digital ocean bucket or local)
- Laravel Notifications for email

---

## Database Schema

### users

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| name | string | |
| email | string | unique |
| password | string | |
| role | enum | `candidate`, `jae_staff` |
| email_verified_at | timestamp | nullable |
| created_at | timestamp | |
| updated_at | timestamp | |

### candidate_profiles

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK → users |
| university | string | |
| degree | string | engineering field |
| semester | integer | nullable, current semester |
| graduation_year | integer | nullable |
| skills | json | array of skill strings |
| cv_path | string | nullable, file path |
| location | string | city/zone |
| age | integer | nullable |
| gender | enum | `male`, `female`, `other`, `prefer_not_to_say` |
| phone | string | nullable |
| linkedin_url | string | nullable |
| bio | text | nullable, short intro |
| created_at | timestamp | |
| updated_at | timestamp | |

### positions

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| title | string | |
| description | text | |
| requirements | text | |
| location | string | |
| is_active | boolean | default true |
| created_at | timestamp | |
| updated_at | timestamp | |

### applications

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| candidate_id | bigint | FK → candidate_profiles |
| position_id | bigint | FK → positions |
| status | enum | see below |
| created_at | timestamp | |
| updated_at | timestamp | |

**Application statuses:** `registered`, `preselected`, `interview`, `evaluation`, `finalist`, `hired`, `discarded`

### interviews

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| application_id | bigint | FK → applications |
| scheduled_at | datetime | |
| location | string | nullable, room or link |
| type | enum | `technical`, `hr`, `final` |
| notes | text | nullable, internal notes |
| created_at | timestamp | |
| updated_at | timestamp | |

### application_notes

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| application_id | bigint | FK → applications |
| author_id | bigint | FK → users (JAE staff) |
| content | text | |
| created_at | timestamp | |

### referrals

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| referrer_id | bigint | FK → users (who referred) |
| referred_email | string | |
| referred_user_id | bigint | nullable, FK → users |
| status | enum | `pending`, `registered`, `hired`, `rewarded` |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## API Endpoints

### Auth
```
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/auth/me
POST   /api/auth/forgot-password
POST   /api/auth/reset-password
```

### Candidate Profile
```
GET    /api/candidate/profile
POST   /api/candidate/profile
PUT    /api/candidate/profile
POST   /api/candidate/profile/cv          (file upload)
DELETE /api/candidate/profile/cv
GET    /api/candidate/profile/cv          (download CV via signed URL)
```

### Positions (public + candidate)
```
GET    /api/positions                     (list active positions)
GET    /api/positions/{id}                (position detail)
```

### Applications (candidate)
```
GET    /api/candidate/applications        (my applications)
POST   /api/candidate/applications        (apply to position)
GET    /api/candidate/applications/{id}   (application detail + interviews)
```

### Referrals (candidate)
```
GET    /api/candidate/referrals           (my referrals)
POST   /api/candidate/referrals           (send referral invite)
```

### JAE Staff — Candidates Management
```
GET    /api/admin/candidates              (list all with filters)
GET    /api/admin/candidates/{id}         (full profile + applications)
GET    /api/admin/candidates/{id}/cv      (download candidate CV)
```

### JAE Staff — Applications Management
```
GET    /api/admin/applications            (all applications, filterable)
GET    /api/admin/applications/{id}       (detail)
PUT    /api/admin/applications/{id}/status (change status)
POST   /api/admin/applications/{id}/notes (add internal note)
GET    /api/admin/applications/{id}/notes (list notes)
```

### JAE Staff — Interviews
```
GET    /api/admin/interviews              (list all, filterable by date)
POST   /api/admin/interviews              (schedule new)
PUT    /api/admin/interviews/{id}         (update)
DELETE /api/admin/interviews/{id}         (cancel)
```

### JAE Staff — Positions Management
```
GET    /api/admin/positions
POST   /api/admin/positions
PUT    /api/admin/positions/{id}
DELETE /api/admin/positions/{id}
```

### JAE Staff — Dashboard Stats
```
GET    /api/admin/stats                   (counts, pipeline metrics)
```

---

## File Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── RegisterController.php
│   │   │   ├── LoginController.php
│   │   │   └── PasswordResetController.php
│   │   ├── Candidate/
│   │   │   ├── ProfileController.php
│   │   │   ├── ApplicationController.php
│   │   │   └── ReferralController.php
│   │   ├── Admin/
│   │   │   ├── CandidateController.php
│   │   │   ├── ApplicationController.php
│   │   │   ├── InterviewController.php
│   │   │   ├── PositionController.php
│   │   │   └── StatsController.php
│   │   └── PositionController.php (public)
│   ├── Middleware/
│   │   └── EnsureUserHasRole.php
│   ├── Requests/
│   │   ├── Auth/
│   │   │   ├── RegisterRequest.php
│   │   │   └── LoginRequest.php
│   │   ├── Candidate/
│   │   │   ├── UpdateProfileRequest.php
│   │   │   ├── UploadCvRequest.php
│   │   │   └── CreateApplicationRequest.php
│   │   └── Admin/
│   │       ├── UpdateApplicationStatusRequest.php
│   │       ├── CreateNoteRequest.php
│   │       ├── CreateInterviewRequest.php
│   │       ├── UpdateInterviewRequest.php
│   │       └── PositionRequest.php
│   └── Resources/
│       ├── UserResource.php
│       ├── CandidateProfileResource.php
│       ├── PositionResource.php
│       ├── ApplicationResource.php
│       ├── InterviewResource.php
│       └── NoteResource.php
├── Models/
│   ├── User.php
│   ├── CandidateProfile.php
│   ├── Position.php
│   ├── Application.php
│   ├── Interview.php
│   ├── ApplicationNote.php
│   └── Referral.php
├── Notifications/
│   ├── ApplicationReceived.php
│   ├── ApplicationStatusChanged.php
│   ├── InterviewScheduled.php
│   └── ReferralInvite.php
├── Services/
│   ├── StatsService.php
│   └── CvStorageService.php
├── Enums/
│   ├── UserRole.php
│   ├── ApplicationStatus.php
│   ├── InterviewType.php
│   ├── Gender.php
│   └── ReferralStatus.php
└── Policies/
    ├── ApplicationPolicy.php
    ├── CandidateProfilePolicy.php
    └── InterviewPolicy.php

database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_candidate_profiles_table.php
│   ├── create_positions_table.php
│   ├── create_applications_table.php
│   ├── create_interviews_table.php
│   ├── create_application_notes_table.php
│   └── create_referrals_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── UserSeeder.php
│   └── PositionSeeder.php
└── factories/
    ├── UserFactory.php
    ├── CandidateProfileFactory.php
    ├── PositionFactory.php
    └── ApplicationFactory.php

routes/
└── api.php
```

---

## Authentication Setup

1. Install and configure Sanctum for SPA authentication
2. Candidates register via `/api/auth/register` → creates user with role `candidate` + empty profile record
3. JAE staff accounts are created manually via seeder or future admin endpoint
4. All protected routes use `auth:sanctum` middleware
5. Admin routes additionally use custom `role:jae_staff` middleware

**Middleware registration in bootstrap/app.php:**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
})
```

---

## Enums

### UserRole.php
```php
enum UserRole: string
{
    case CANDIDATE = 'candidate';
    case JAE_STAFF = 'jae_staff';
}
```

### ApplicationStatus.php
```php
enum ApplicationStatus: string
{
    case REGISTERED = 'registered';
    case PRESELECTED = 'preselected';
    case INTERVIEW = 'interview';
    case EVALUATION = 'evaluation';
    case FINALIST = 'finalist';
    case HIRED = 'hired';
    case DISCARDED = 'discarded';
}
```

### InterviewType.php
```php
enum InterviewType: string
{
    case TECHNICAL = 'technical';
    case HR = 'hr';
    case FINAL = 'final';
}
```

### Gender.php
```php
enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
    case PREFER_NOT_TO_SAY = 'prefer_not_to_say';
}
```

### ReferralStatus.php
```php
enum ReferralStatus: string
{
    case PENDING = 'pending';
    case REGISTERED = 'registered';
    case HIRED = 'hired';
    case REWARDED = 'rewarded';
}
```

---

## Key Model Relationships

### User.php
```php
public function candidateProfile(): HasOne
public function authoredNotes(): HasMany  // for JAE staff
public function referralsSent(): HasMany
```

### CandidateProfile.php
```php
public function user(): BelongsTo
public function applications(): HasMany
```

### Position.php
```php
public function applications(): HasMany
```

### Application.php
```php
public function candidate(): BelongsTo  // CandidateProfile
public function position(): BelongsTo
public function interviews(): HasMany
public function notes(): HasMany
```

### Interview.php
```php
public function application(): BelongsTo
```

### ApplicationNote.php
```php
public function application(): BelongsTo
public function author(): BelongsTo  // User
```

---

## Filtering Logic for Admin Candidates Endpoint

`GET /api/admin/candidates` should support these query parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| search | string | Search in name, email, skills |
| university | string | Filter by university |
| degree | string | Filter by degree |
| status | string | Filter by application status |
| from_date | date | Registered after this date |
| to_date | date | Registered before this date |
| per_page | int | Pagination (default 15) |
| sort_by | string | Column to sort by |
| sort_dir | string | asc or desc |

---

## CV Storage

- Store CVs privately using Laravel's Storage facade
- Accept only PDF files, max 5MB
- Generate signed temporary URLs for secure access
- Use a dedicated `CvStorageService` for upload/delete/URL generation
```php
class CvStorageService
{
    public function upload(UploadedFile $file, int $userId): string
    public function delete(string $path): bool
    public function getSignedUrl(string $path, int $minutes = 30): string
}
```

---

## Stats Service

`GET /api/admin/stats` returns:
```json
{
    "total_candidates": 150,
    "total_applications": 200,
    "applications_by_status": {
        "registered": 50,
        "preselected": 40,
        "interview": 30,
        "evaluation": 25,
        "finalist": 20,
        "hired": 15,
        "discarded": 20
    },
    "interviews_this_week": 8,
    "recent_applications": [],
    "top_universities": [
        {"name": "UABC", "count": 45},
        {"name": "CETYS", "count": 30}
    ]
}
```

---

## Development Phases

### Phase 1 — Foundation

- Project setup with Sanctum configured for SPA auth
- All database migrations
- All Eloquent models with relationships
- Enums
- User authentication endpoints (register, login, logout, me)
- Role middleware
- Basic seeders (JAE staff user, sample positions)
- API Resource classes

### Phase 2 — Candidate Features

- Profile CRUD endpoints
- CV upload with validation and storage
- Public positions list and detail
- Application creation and listing
- Referral system endpoints

### Phase 3 — JAE Staff Features

- Candidates list with filtering and pagination
- Candidate detail endpoint
- Application status management
- Notes system
- Interview CRUD
- Positions management
- Stats endpoint

### Phase 4 — Polish

- Email notifications (ApplicationReceived, StatusChanged, InterviewScheduled)
- Policies for authorization
- Comprehensive FormRequest validation
- API rate limiting
- Feature tests for critical flows
- API documentation (or OpenAPI spec)

---

## Environment Variables
```env
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000

SANCTUM_STATEFUL_DOMAINS=localhost:3000

FILESYSTEM_DISK=local
# For production:
# FILESYSTEM_DISK=s3
# AWS_ACCESS_KEY_ID=
# AWS_SECRET_ACCESS_KEY=
# AWS_DEFAULT_REGION=
# AWS_BUCKET=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Implementation Notes

1. Use `FormRequest` classes for all validation — never validate in controllers
2. Use `API Resources` for all JSON responses to maintain consistent structure
3. CVs must be stored privately and never publicly accessible — always use signed URLs
4. Status changes on applications should trigger the `ApplicationStatusChanged` notification
5. When scheduling interviews, trigger the `InterviewScheduled` notification to the candidate
6. Consider adding an `application_status_logs` table later for audit trail
7. Use database transactions when creating applications (to handle any related records)
8. Implement soft deletes on positions (so existing applications don't break)
9. Add indexes on frequently filtered columns: `applications.status`, `candidate_profiles.university`, `candidate_profiles.degree`

---

This document provides everything needed to build the Laravel API. Start with Phase 1 and proceed sequentially.