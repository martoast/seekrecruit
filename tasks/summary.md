# Seek & Recruit API Documentation

## Overview

This is the complete API documentation for the Seek & Recruit recruitment platform. The API serves two main user types:
- **Candidates** - Students/graduates looking for engineering positions
- **JAE Staff** - Recruiters/HR managing the hiring pipeline

**Base URL:** `http://localhost:8000/api` (update for production)

**Authentication:** Laravel Sanctum (Token-based)

---

## Authentication Flow

### 1. Register (Candidate)

**Endpoint:** `POST /auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "gender": "male"
}
```

**Response (201):**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "candidate",
    "email_verified_at": null,
    "created_at": "2025-12-29T10:00:00.000000Z",
    "updated_at": "2025-12-29T10:00:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz..."
}
```

**Notes:**
- Automatically creates a candidate profile
- Returns authentication token to use for subsequent requests
- Role is automatically set to "candidate"

---

### 2. Login

**Endpoint:** `POST /auth/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "candidate"
  },
  "token": "2|abcdefghijklmnopqrstuvwxyz..."
}
```

---

### 3. Logout

**Endpoint:** `POST /auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "message": "Logged out successfully"
}
```

---

### 4. Get Current User

**Endpoint:** `GET /auth/me`

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "candidate"
  }
}
```

---

## Public Endpoints (No Authentication Required)

### 1. List Active Positions

**Endpoint:** `GET /positions`

**Response (200):**
```json
{
  "positions": [
    {
      "id": 1,
      "title": "Software Engineer",
      "description": "We are looking for a talented software engineer...",
      "requirements": "- 2+ years experience\n- Proficient in Laravel...",
      "location": "Tijuana, Mexico",
      "is_active": true,
      "created_at": "2025-12-29T10:00:00.000000Z",
      "updated_at": "2025-12-29T10:00:00.000000Z"
    }
  ]
}
```

---

### 2. Get Position Details

**Endpoint:** `GET /positions/{id}`

**Response (200):**
```json
{
  "position": {
    "id": 1,
    "title": "Software Engineer",
    "description": "...",
    "requirements": "...",
    "location": "Tijuana, Mexico",
    "is_active": true
  }
}
```

---

## Candidate Endpoints (Requires: role = candidate)

**All candidate endpoints require:** `Authorization: Bearer {token}`

### 1. Get My Profile

**Endpoint:** `GET /candidate/profile`

**Response (200):**
```json
{
  "profile": {
    "id": 1,
    "university": "UABC",
    "degree": "Computer Science",
    "semester": 8,
    "graduation_year": 2025,
    "skills": ["Laravel", "React", "Python"],
    "location": "Tijuana",
    "age": 23,
    "gender": "male",
    "phone": "+52 664 123 4567",
    "linkedin_url": "https://linkedin.com/in/johndoe",
    "bio": "Passionate software engineer...",
    "has_cv": true
  }
}
```

---

### 2. Update My Profile

**Endpoint:** `PUT /candidate/profile`

**Request Body:**
```json
{
  "university": "UABC",
  "degree": "Computer Science",
  "semester": 8,
  "graduation_year": 2025,
  "skills": ["Laravel", "React", "Python", "Docker"],
  "location": "Tijuana",
  "age": 23,
  "gender": "male",
  "phone": "+52 664 123 4567",
  "linkedin_url": "https://linkedin.com/in/johndoe",
  "bio": "Passionate software engineer..."
}
```

**Validation Rules:**
- `university`: string, max 255 (optional)
- `degree`: string, max 255 (optional)
- `semester`: integer, 1-12 (optional)
- `graduation_year`: integer, 1900-2100 (optional)
- `skills`: array of strings, max 100 chars each (optional)
- `location`: string, max 255 (optional)
- `age`: integer, 16-100 (optional)
- `gender`: enum (male, female, other, prefer_not_to_say) (optional)
- `phone`: string, max 20 (optional)
- `linkedin_url`: valid URL, max 255 (optional)
- `bio`: string, max 1000 (optional)

---

### 3. Upload CV

**Endpoint:** `POST /candidate/profile/cv`

**Headers:**
```
Content-Type: multipart/form-data
Authorization: Bearer {token}
```

**Request Body (form-data):**
```
cv: [PDF file, max 5MB]
```

**Response (200):**
```json
{
  "message": "CV uploaded successfully",
  "profile": {
    "has_cv": true
  }
}
```

---

### 4. Download My CV

**Endpoint:** `GET /candidate/profile/cv`

**Response (200):**
```json
{
  "url": "https://signed-temporary-url-to-cv.pdf?expires=..."
}
```

**Notes:** URL is valid for 30 minutes

---

### 5. Delete My CV

**Endpoint:** `DELETE /candidate/profile/cv`

**Response (200):**
```json
{
  "message": "CV deleted successfully"
}
```

---

### 6. Get My Applications

**Endpoint:** `GET /candidate/applications`

**Response (200):**
```json
{
  "applications": [
    {
      "id": 1,
      "position": {
        "id": 1,
        "title": "Software Engineer",
        "location": "Tijuana, Mexico"
      },
      "status": "registered",
      "interviews": [],
      "created_at": "2025-12-29T11:00:00.000000Z"
    }
  ]
}
```

**Application Status Values:**
- `registered` - Initial application submitted
- `preselected` - Selected for next round
- `interview` - Scheduled for interview
- `evaluation` - Being evaluated
- `finalist` - Final candidate
- `hired` - Hired!
- `discarded` - Not selected

---

### 7. Apply to Position

**Endpoint:** `POST /candidate/applications`

**Request Body:**
```json
{
  "position_id": 1
}
```

**Response (201):**
```json
{
  "application": {
    "id": 1,
    "position": {
      "id": 1,
      "title": "Software Engineer"
    },
    "status": "registered",
    "created_at": "2025-12-29T11:00:00.000000Z"
  },
  "message": "Application submitted successfully"
}
```

**Error (422):** `"message": "You have already applied to this position"`

---

### 8. Get Application Details

**Endpoint:** `GET /candidate/applications/{id}`

**Response (200):**
```json
{
  "application": {
    "id": 1,
    "position": {
      "id": 1,
      "title": "Software Engineer"
    },
    "status": "interview",
    "interviews": [
      {
        "id": 1,
        "scheduled_at": "2025-12-30T14:00:00.000000Z",
        "location": "JAE Office, Room 301",
        "type": "technical",
        "notes": "Bring portfolio"
      }
    ],
    "created_at": "2025-12-29T11:00:00.000000Z"
  }
}
```

**Interview Types:**
- `technical` - Technical interview
- `hr` - HR interview
- `final` - Final interview

---

### 9. Get My Referrals

**Endpoint:** `GET /candidate/referrals`

**Response (200):**
```json
{
  "referrals": [
    {
      "id": 1,
      "referred_email": "friend@example.com",
      "status": "registered",
      "created_at": "2025-12-29T10:00:00.000000Z"
    }
  ]
}
```

**Referral Status Values:**
- `pending` - Invitation sent, not yet registered
- `registered` - Referred person registered
- `hired` - Referred person was hired
- `rewarded` - Referrer received reward

---

### 10. Send Referral Invitation

**Endpoint:** `POST /candidate/referrals`

**Request Body:**
```json
{
  "referred_email": "friend@example.com"
}
```

**Response (201):**
```json
{
  "referral": {
    "id": 1,
    "referred_email": "friend@example.com",
    "status": "pending"
  },
  "message": "Referral invitation sent successfully"
}
```

---

## Admin Endpoints (Requires: role = jae_staff)

**All admin endpoints require:** `Authorization: Bearer {token}` with `role: jae_staff`

### 1. List Candidates (with Filtering)

**Endpoint:** `GET /admin/candidates`

**Query Parameters:**
- `search` - Search in name, email, skills (optional)
- `university` - Filter by university (optional)
- `degree` - Filter by degree (optional)
- `status` - Filter by application status (optional)
- `from_date` - Registered after this date (optional)
- `to_date` - Registered before this date (optional)
- `per_page` - Items per page, default 15 (optional)
- `sort_by` - Column to sort by, default "created_at" (optional)
- `sort_dir` - Sort direction (asc/desc), default "desc" (optional)

**Example:** `GET /admin/candidates?search=John&university=UABC&per_page=20`

**Response (200):**
```json
{
  "candidates": [
    {
      "id": 1,
      "user": {
        "name": "John Doe",
        "email": "john@example.com"
      },
      "university": "UABC",
      "degree": "Computer Science",
      "skills": ["Laravel", "React"],
      "has_cv": true
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 73
  }
}
```

---

### 2. Get Candidate Details

**Endpoint:** `GET /admin/candidates/{id}`

**Response (200):** Returns full candidate profile with applications

---

### 3. Download Candidate CV

**Endpoint:** `GET /admin/candidates/{id}/cv`

**Response (200):**
```json
{
  "url": "https://signed-temporary-url-to-cv.pdf?expires=..."
}
```

---

### 4. List Applications (with Filtering)

**Endpoint:** `GET /admin/applications`

**Query Parameters:**
- `status` - Filter by status (optional)
- `position_id` - Filter by position (optional)
- `from_date` - Created after this date (optional)
- `to_date` - Created before this date (optional)
- `per_page` - Items per page, default 15 (optional)

**Response (200):** Returns paginated applications with candidate and position details

---

### 5. Get Application Details

**Endpoint:** `GET /admin/applications/{id}`

**Response (200):** Returns application with candidate profile, position, interviews, and notes

---

### 6. Update Application Status

**Endpoint:** `PUT /admin/applications/{id}/status`

**Request Body:**
```json
{
  "status": "interview"
}
```

**Valid Status Values:** `registered`, `preselected`, `interview`, `evaluation`, `finalist`, `hired`, `discarded`

**Response (200):**
```json
{
  "application": { /* updated application */ },
  "message": "Application status updated successfully"
}
```

**Notes:** Triggers email notification to candidate

---

### 7. Add Note to Application

**Endpoint:** `POST /admin/applications/{id}/notes`

**Request Body:**
```json
{
  "content": "Candidate showed excellent problem-solving skills."
}
```

**Validation:** `content` required, string, max 2000 characters

**Response (201):**
```json
{
  "note": {
    "id": 1,
    "content": "Candidate showed excellent problem-solving skills.",
    "author": {
      "name": "HR Manager"
    },
    "created_at": "2025-12-29T12:30:00.000000Z"
  },
  "message": "Note added successfully"
}
```

---

### 8. Get Application Notes

**Endpoint:** `GET /admin/applications/{id}/notes`

**Response (200):** Returns all notes for the application

---

### 9. List Interviews (with Filtering)

**Endpoint:** `GET /admin/interviews`

**Query Parameters:**
- `from_date` - Scheduled after this date (optional)
- `to_date` - Scheduled before this date (optional)
- `type` - Filter by type: technical, hr, final (optional)

**Response (200):** Returns all interviews matching filters

---

### 10. Schedule Interview

**Endpoint:** `POST /admin/interviews`

**Request Body:**
```json
{
  "application_id": 1,
  "scheduled_at": "2025-12-30T14:00:00",
  "location": "JAE Office, Room 301",
  "type": "technical",
  "notes": "Bring portfolio"
}
```

**Validation:**
- `application_id`: required, integer, must exist
- `scheduled_at`: required, date, must be in the future
- `location`: optional, string, max 255
- `type`: required, enum (technical, hr, final)
- `notes`: optional, string, max 2000

**Response (201):** Returns created interview, triggers email to candidate

---

### 11. Update Interview

**Endpoint:** `PUT /admin/interviews/{id}`

**Request Body:** All fields optional, same validation as create

**Response (200):**
```json
{
  "interview": { /* updated interview */ },
  "message": "Interview updated successfully"
}
```

---

### 12. Cancel Interview

**Endpoint:** `DELETE /admin/interviews/{id}`

**Response (200):**
```json
{
  "message": "Interview cancelled successfully"
}
```

---

### 13. List Positions

**Endpoint:** `GET /admin/positions`

**Response (200):** Returns all positions (active and inactive)

---

### 14. Create Position

**Endpoint:** `POST /admin/positions`

**Request Body:**
```json
{
  "title": "Senior Full Stack Developer",
  "description": "We are looking for an experienced full stack developer...",
  "requirements": "- 5+ years experience\n- Expert in Laravel and React",
  "location": "Tijuana, Mexico",
  "is_active": true
}
```

**Validation:**
- `title`: required, string, max 255
- `description`: required, string
- `requirements`: required, string
- `location`: required, string, max 255
- `is_active`: optional, boolean, default true

**Response (201):**
```json
{
  "position": { /* created position */ },
  "message": "Position created successfully"
}
```

---

### 15. Update Position

**Endpoint:** `PUT /admin/positions/{id}`

**Request Body:** Same as create

**Response (200):**
```json
{
  "position": { /* updated position */ },
  "message": "Position updated successfully"
}
```

---

### 16. Delete Position

**Endpoint:** `DELETE /admin/positions/{id}`

**Response (200):**
```json
{
  "message": "Position deleted successfully"
}
```

**Notes:** Soft deletes, existing applications preserved

---

### 17. Dashboard Statistics

**Endpoint:** `GET /admin/stats`

**Response (200):**
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
  "recent_applications": [ /* latest 5 applications */ ],
  "top_universities": [
    {
      "name": "UABC",
      "count": 45
    },
    {
      "name": "CETYS",
      "count": 30
    }
  ]
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "Unauthorized"
}
```

### Not Found (404)
```json
{
  "message": "Resource not found"
}
```

---

## Request Headers

### All Authenticated Requests
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

### File Upload Requests
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

---

## Important Notes for Frontend Development

1. **Token Management**
   - Store token securely (localStorage or secure cookie)
   - Include token in Authorization header for all authenticated requests
   - Handle 401 responses by redirecting to login

2. **Role-Based Access**
   - Check user role from `/auth/me` response
   - Only show candidate features to `role: candidate`
   - Only show admin features to `role: jae_staff`

3. **File Uploads**
   - CV uploads must be PDF only, max 5MB
   - Use `multipart/form-data` content type

4. **Date Formatting**
   - API returns dates in ISO 8601 format
   - Send dates to API in ISO 8601 format

5. **Pagination**
   - Use `meta` object for pagination controls
   - Default `per_page` is 15

6. **Error Handling**
   - Display validation errors next to form fields
   - Show user-friendly messages for server errors

7. **CV Download**
   - Signed URLs expire after 30 minutes
   - Open in new tab or trigger download

---

## Example Flow: Complete Application Process

1. **Candidate Registration**
   - POST `/auth/register` → Receive token
   - Redirect to profile completion

2. **Complete Profile**
   - PUT `/candidate/profile` → Update profile
   - POST `/candidate/profile/cv` → Upload CV

3. **Browse and Apply**
   - GET `/positions` → Display positions
   - POST `/candidate/applications` → Apply

4. **Admin Reviews**
   - GET `/admin/applications` → List applications
   - GET `/admin/candidates/{id}/cv` → Review CV
   - POST `/admin/applications/{id}/notes` → Add notes
   - PUT `/admin/applications/{id}/status` → Update status

5. **Schedule Interview**
   - POST `/admin/interviews` → Schedule
   - Candidate receives email notification

6. **Final Decision**
   - PUT `/admin/applications/{id}/status` → Update to hired/discarded
   - Candidate receives email notification

---

**API Version:** 1.0.0
**Last Updated:** December 29, 2025
