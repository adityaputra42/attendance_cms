# API Documentation - Attendance System

Base URL: `http://localhost:8000/api`

## Authentication

All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_token_here}
```

---

## Auth Endpoints

### 1. Register

Create a new user account.

**Endpoint:** `POST /register`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "081234567890",
  "address": "Jakarta, Indonesia"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "081234567890",
      "avatar": null,
      "role": "employee",
      "is_active": true,
      "address": "Jakarta, Indonesia",
      "created_at": "2026-02-09T09:00:00.000000Z",
      "updated_at": "2026-02-09T09:00:00.000000Z"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxx"
  }
}
```

---

### 2. Login

Authenticate user and get access token.

**Endpoint:** `POST /login`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "email": "john@attendance.com",
  "password": "password"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 2,
      "name": "John Doe",
      "email": "john@attendance.com",
      "phone": "081234567891",
      "avatar": null,
      "role": "employee",
      "is_active": true,
      "address": "Jakarta, Indonesia"
    },
    "token": "2|xxxxxxxxxxxxxxxxxxxxx"
  }
}
```

**Error Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

### 3. Get Current User

Get authenticated user information.

**Endpoint:** `GET /me`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "name": "John Doe",
    "email": "john@attendance.com",
    "phone": "081234567891",
    "avatar": null,
    "role": "employee",
    "is_active": true,
    "address": "Jakarta, Indonesia"
  }
}
```

---

### 4. Update Profile

Update user profile information.

**Endpoint:** `POST /update-profile`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body (Form Data):**
- `name` (optional): string
- `phone` (optional): string
- `address` (optional): string
- `avatar` (optional): image file (jpeg, png, jpg, max 2MB)

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 2,
    "name": "John Doe Updated",
    "email": "john@attendance.com",
    "phone": "081234567899",
    "avatar": "avatars/xxxxx.jpg",
    "role": "employee",
    "is_active": true,
    "address": "Updated Address"
  }
}
```

---

### 5. Change Password

Change user password.

**Endpoint:** `POST /change-password`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "current_password": "password",
  "new_password": "newpassword123",
  "new_password_confirmation": "newpassword123"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Password changed successfully"
}
```

---

### 6. Logout

Logout and revoke current access token.

**Endpoint:** `POST /logout`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## Attendance Endpoints

### 1. Check In

Record attendance check-in with location and photo.

**Endpoint:** `POST /attendance/check-in`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body (Form Data):**
- `latitude` (required): decimal (-90 to 90)
- `longitude` (required): decimal (-180 to 180)
- `photo` (required): image file (jpeg, png, jpg, max 5MB)
- `address` (optional): string

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Check in successful",
  "data": {
    "id": 1,
    "user_id": 2,
    "check_in_time": "2026-02-09T08:30:00.000000Z",
    "check_in_latitude": "-6.200000",
    "check_in_longitude": "106.816666",
    "check_in_photo": "attendance/check-in/xxxxx.jpg",
    "check_in_address": "Jakarta Office",
    "check_out_time": null,
    "check_out_latitude": null,
    "check_out_longitude": null,
    "check_out_photo": null,
    "check_out_address": null,
    "work_description": null,
    "work_duration_minutes": null,
    "status": "checked_in",
    "attendance_date": "2026-02-09",
    "created_at": "2026-02-09T08:30:00.000000Z",
    "updated_at": "2026-02-09T08:30:00.000000Z"
  }
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "You have already checked in today"
}
```

```json
{
  "success": false,
  "message": "You are too far from the office location"
}
```

---

### 2. Check Out

Record attendance check-out with location, photo, and work description.

**Endpoint:** `POST /attendance/check-out`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body (Form Data):**
- `latitude` (required): decimal (-90 to 90)
- `longitude` (required): decimal (-180 to 180)
- `photo` (required): image file (jpeg, png, jpg, max 5MB)
- `work_description` (required): string (min 10 characters)
- `address` (optional): string

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Check out successful",
  "data": {
    "id": 1,
    "user_id": 2,
    "check_in_time": "2026-02-09T08:30:00.000000Z",
    "check_in_latitude": "-6.200000",
    "check_in_longitude": "106.816666",
    "check_in_photo": "attendance/check-in/xxxxx.jpg",
    "check_in_address": "Jakarta Office",
    "check_out_time": "2026-02-09T17:00:00.000000Z",
    "check_out_latitude": "-6.200000",
    "check_out_longitude": "106.816666",
    "check_out_photo": "attendance/check-out/xxxxx.jpg",
    "check_out_address": "Jakarta Office",
    "work_description": "Completed daily tasks including meetings and code review",
    "work_duration_minutes": 510,
    "status": "checked_out",
    "attendance_date": "2026-02-09",
    "created_at": "2026-02-09T08:30:00.000000Z",
    "updated_at": "2026-02-09T17:00:00.000000Z"
  }
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "You have not checked in today or already checked out"
}
```

---

### 3. Today Status

Get today's attendance status for current user.

**Endpoint:** `GET /attendance/today-status`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "attendance": {
      "id": 1,
      "user_id": 2,
      "check_in_time": "2026-02-09T08:30:00.000000Z",
      "status": "checked_in",
      "attendance_date": "2026-02-09"
    },
    "has_checked_in": true,
    "has_checked_out": false
  }
}
```

**Response when no attendance today:**
```json
{
  "success": true,
  "data": {
    "attendance": null,
    "has_checked_in": false,
    "has_checked_out": false
  }
}
```

---

### 4. Attendance History

Get attendance history with pagination and filters.

**Endpoint:** `GET /attendance/history`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `per_page` (optional): integer (default: 15)
- `month` (optional): integer (1-12)
- `year` (optional): integer

**Example:** `GET /attendance/history?per_page=10&month=2&year=2026`

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 2,
        "check_in_time": "2026-02-09T08:30:00.000000Z",
        "check_in_latitude": "-6.200000",
        "check_in_longitude": "106.816666",
        "check_in_photo": "attendance/check-in/xxxxx.jpg",
        "check_in_address": "Jakarta Office",
        "check_out_time": "2026-02-09T17:00:00.000000Z",
        "check_out_latitude": "-6.200000",
        "check_out_longitude": "106.816666",
        "check_out_photo": "attendance/check-out/xxxxx.jpg",
        "check_out_address": "Jakarta Office",
        "work_description": "Completed daily tasks",
        "work_duration_minutes": 510,
        "status": "checked_out",
        "attendance_date": "2026-02-09"
      }
    ],
    "first_page_url": "http://localhost:8000/api/attendance/history?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/attendance/history?page=1",
    "next_page_url": null,
    "path": "http://localhost:8000/api/attendance/history",
    "per_page": 15,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  }
}
```

---

### 5. Attendance Detail

Get specific attendance record detail.

**Endpoint:** `GET /attendance/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 2,
    "check_in_time": "2026-02-09T08:30:00.000000Z",
    "check_in_latitude": "-6.200000",
    "check_in_longitude": "106.816666",
    "check_in_photo": "attendance/check-in/xxxxx.jpg",
    "check_in_address": "Jakarta Office",
    "check_out_time": "2026-02-09T17:00:00.000000Z",
    "check_out_latitude": "-6.200000",
    "check_out_longitude": "106.816666",
    "check_out_photo": "attendance/check-out/xxxxx.jpg",
    "check_out_address": "Jakarta Office",
    "work_description": "Completed daily tasks",
    "work_duration_minutes": 510,
    "status": "checked_out",
    "attendance_date": "2026-02-09"
  }
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Attendance not found"
}
```

---

### 6. Attendance Statistics

Get attendance statistics for a specific month.

**Endpoint:** `GET /attendance/statistics`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `month` (optional): integer (1-12, default: current month)
- `year` (optional): integer (default: current year)

**Example:** `GET /attendance/statistics?month=2&year=2026`

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "month": 2,
    "year": 2026,
    "total_attendance_days": 20,
    "total_completed_days": 18,
    "total_work_hours": 153.0,
    "average_work_hours": 8.5
  }
}
```

---

### 7. Get Attendance Settings

Get current attendance settings (office location, radius, etc).

**Endpoint:** `GET /attendance/settings`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "office_latitude": "-6.200000",
    "office_longitude": "106.816666",
    "allowed_radius_meters": 500,
    "work_start_time": "08:00",
    "work_end_time": "17:00"
  }
}
```

---

## Error Responses

### Validation Error (422 Unprocessable Entity)
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password field is required."
    ]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "Your account is inactive. Please contact administrator."
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Internal server error"
}
```

---

## Image URLs

All uploaded images can be accessed via:
```
http://localhost:8000/storage/{image_path}
```

For example:
- Avatar: `http://localhost:8000/storage/avatars/xxxxx.jpg`
- Check-in photo: `http://localhost:8000/storage/attendance/check-in/xxxxx.jpg`
- Check-out photo: `http://localhost:8000/storage/attendance/check-out/xxxxx.jpg`

---

## Notes

1. **Location Validation**: The system validates that check-in/check-out locations are within the allowed radius from the office location using the Haversine formula.

2. **Daily Attendance**: Users can only check-in once per day. They must check-in before they can check-out.

3. **Work Duration**: Automatically calculated when checking out, stored in minutes.

4. **Photo Requirements**: 
   - Formats: JPEG, PNG, JPG
   - Max size: 5MB for attendance photos, 2MB for avatars

5. **Token Management**: Login automatically revokes all previous tokens for the user.

6. **Timestamps**: All timestamps are in UTC and follow ISO 8601 format.
